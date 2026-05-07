<?php

namespace App\Services;

use Cose\Algorithm\Manager;
use Cose\Algorithm\Signature\ECDSA\ES256;
use Cose\Algorithm\Signature\RSA\RS256;
use Illuminate\Support\Facades\Log;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;
use Webauthn\AttestationStatement\NoneAttestationStatementSupport;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\AuthenticatorAssertionResponseValidator;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\CeremonyStep\CeremonyStepManagerFactory;
use Webauthn\CredentialRecord;
use Webauthn\Denormalizer\WebauthnSerializerFactory;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialDescriptor;
use Webauthn\PublicKeyCredentialParameters;
use Webauthn\PublicKeyCredentialRequestOptions;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialSource;
use Webauthn\PublicKeyCredentialUserEntity;

class WebAuthnService
{
    private CeremonyStepManagerFactory $ceremonyFactory;
    private $serializer;
    private string $rpId;
    private string $rpName;

    public function __construct()
    {
        $this->rpName = config('app.name', 'TESSMS');
        $this->rpId = parse_url(config('app.url', 'http://localhost'), PHP_URL_HOST) ?? 'localhost';

        $algorithmManager = Manager::create()->add(ES256::create(), RS256::create());
        $attestationManager = new AttestationStatementSupportManager([
            new NoneAttestationStatementSupport(),
        ]);

        $this->ceremonyFactory = new CeremonyStepManagerFactory();
        $this->ceremonyFactory->setAlgorithmManager($algorithmManager);
        $this->ceremonyFactory->setAttestationStatementSupportManager($attestationManager);
        $this->ceremonyFactory->setAllowedOrigins([config('app.url', 'http://localhost')]);

        $serializerFactory = new WebauthnSerializerFactory($attestationManager);
        $this->serializer = $serializerFactory->create();
    }

    /**
     * Generate options for registering a new biometric credential
     */
    public function getRegistrationOptions(\App\Models\User $user): array
    {
        $challenge = random_bytes(32);
        session(['webauthn_challenge' => base64_encode($challenge)]);

        $rp = PublicKeyCredentialRpEntity::create($this->rpName, $this->rpId);
        $userEntity = PublicKeyCredentialUserEntity::create(
            $user->first_name . ' ' . $user->last_name,
            (string) $user->id,
            $user->first_name . ' ' . $user->last_name
        );

        $options = PublicKeyCredentialCreationOptions::create(
            $rp,
            $userEntity,
            $challenge,
            [
                PublicKeyCredentialParameters::create('public-key', -7),   // ES256
                PublicKeyCredentialParameters::create('public-key', -257), // RS256
            ],
            authenticatorSelection: \Webauthn\AuthenticatorSelectionCriteria::create(
                authenticatorAttachment: null,
                userVerification: 'required',
                residentKey: 'preferred'
            ),
            attestation: 'none',
            timeout: 60000
        );

        $optionsArray = json_decode($this->serializer->serialize($options, 'json'), true);
        session(['webauthn_registration_options' => $optionsArray]);

        return $optionsArray;
    }

    /**
     * Verify a registration response and return the credential record
     */
    public function verifyRegistration(array $data, array $options, string $host): CredentialRecord
    {
        $publicKeyCredential = $this->serializer->deserialize(
            json_encode($data),
            PublicKeyCredential::class,
            'json'
        );

        $publicKeyCredentialOptions = $this->serializer->deserialize(
            json_encode($options),
            PublicKeyCredentialCreationOptions::class,
            'json'
        );

        if (! $publicKeyCredential->response instanceof AuthenticatorAttestationResponse) {
            throw new \InvalidArgumentException('Invalid response type');
        }

        $validator = AuthenticatorAttestationResponseValidator::create(
            $this->ceremonyFactory->creationCeremony()
        );

        $credentialRecord = $validator->check(
            $publicKeyCredential->response,
            $publicKeyCredentialOptions,
            $host
        );

        return $credentialRecord;
    }

    /**
     * Generate options for authenticating with a biometric credential
     */
    public function getAuthenticationOptions(?\App\Models\User $user = null): array
    {
        $challenge = random_bytes(32);
        session(['webauthn_auth_challenge' => base64_encode($challenge)]);

        $allowCredentials = [];
        if ($user) {
            $credentials = \DB::table('biometric_credentials')
                ->where('user_id', $user->id)
                ->get();

            foreach ($credentials as $cred) {
                $json = $cred->credential_record ?: $cred->public_key;
                if (empty($json)) {
                    continue;
                }
                $record = $this->deserializeCredentialRecord($json);
                $allowCredentials[] = $record->getPublicKeyCredentialDescriptor();
            }
        }

        $options = PublicKeyCredentialRequestOptions::create(
            $challenge,
            rpId: $this->rpId,
            allowCredentials: $allowCredentials,
            userVerification: 'required',
            timeout: 60000
        );

        $optionsArray = json_decode($this->serializer->serialize($options, 'json'), true);
        session(['webauthn_auth_options' => $optionsArray]);

        return $optionsArray;
    }

    /**
     * Verify an authentication response
     */
    public function verifyAuthentication(
        array $data,
        array $options,
        CredentialRecord $credentialRecord,
        string $host
    ): CredentialRecord {
        $publicKeyCredential = $this->serializer->deserialize(
            json_encode($data),
            PublicKeyCredential::class,
            'json'
        );

        $publicKeyCredentialOptions = $this->serializer->deserialize(
            json_encode($options),
            PublicKeyCredentialRequestOptions::class,
            'json'
        );

        if (! $publicKeyCredential->response instanceof AuthenticatorAssertionResponse) {
            throw new \InvalidArgumentException('Invalid response type');
        }

        $validator = AuthenticatorAssertionResponseValidator::create(
            $this->ceremonyFactory->requestCeremony()
        );

        $updatedRecord = $validator->check(
            $credentialRecord,
            $publicKeyCredential->response,
            $publicKeyCredentialOptions,
            $host,
            $credentialRecord->userHandle
        );

        return $updatedRecord;
    }

    /**
     * Deserialize a CredentialRecord from JSON string
     */
    public function deserializeCredentialRecord(string $json): CredentialRecord
    {
        return $this->serializer->deserialize($json, CredentialRecord::class, 'json');
    }

    /**
     * Serialize a CredentialRecord to JSON string
     */
    public function serializeCredentialRecord(CredentialRecord $record): string
    {
        return $this->serializer->serialize($record, 'json');
    }

    /**
     * Find a credential record by credential ID from the database
     */
    public function findCredentialRecord(string $credentialId): ?CredentialRecord
    {
        $cred = \DB::table('biometric_credentials')
            ->where('credential_id', $credentialId)
            ->first();

        if (! $cred) {
            return null;
        }

        try {
            $json = $cred->credential_record ?: $cred->public_key;
            if (empty($json)) {
                return null;
            }
            return $this->deserializeCredentialRecord($json);
        } catch (\Throwable $e) {
            Log::error('Failed to deserialize credential record', [
                'credential_id' => $credentialId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
