<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnrollmentDocument extends Model
{
    protected $fillable = [
        'enrollment_application_id',
        'document_type',
        'document_name',
        'file_path',
        'file_type',
        'file_size',
        'status',
        'admin_notes',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'verified_at' => 'datetime',
    ];

    // Document types
    const TYPE_BIRTH_CERTIFICATE = 'birth_certificate';
    const TYPE_REPORT_CARD = 'report_card';
    const TYPE_GOOD_MORAL = 'good_moral';
    const TYPE_TRANSFER_CREDENTIAL = 'transfer_credential';
    const TYPE_BAPTISMAL = 'baptismal';
    const TYPE_MEDICAL_RECORD = 'medical_record';
    const TYPE_ID_PHOTO = 'id_photo';
    const TYPE_OTHER = 'other';

    public static function getDocumentTypes(): array
    {
        return [
            self::TYPE_BIRTH_CERTIFICATE => 'Birth Certificate',
            self::TYPE_REPORT_CARD => 'Report Card (Form 138)',
            self::TYPE_GOOD_MORAL => 'Certificate of Good Moral',
            self::TYPE_TRANSFER_CREDENTIAL => 'Transfer Credential',
            self::TYPE_BAPTISMAL => 'Baptismal Certificate',
            self::TYPE_MEDICAL_RECORD => 'Medical Record',
            self::TYPE_ID_PHOTO => '2x2 ID Photo',
            self::TYPE_OTHER => 'Other Document',
        ];
    }

    public static function getRequiredDocuments(string $applicationType): array
    {
        $base = [
            self::TYPE_BIRTH_CERTIFICATE,
            self::TYPE_REPORT_CARD,
            self::TYPE_ID_PHOTO,
        ];

        if ($applicationType === 'transfer') {
            $base[] = self::TYPE_TRANSFER_CREDENTIAL;
            $base[] = self::TYPE_GOOD_MORAL;
        }

        return $base;
    }

    // Relationships
    public function enrollmentApplication(): BelongsTo
    {
        return $this->belongsTo(EnrollmentApplication::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Accessors
    public function getDocumentTypeLabelAttribute(): string
    {
        return self::getDocumentTypes()[$this->document_type] ?? 'Unknown';
    }

    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;

        while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
            $bytes /= 1024;
            $unitIndex++;
        }

        return round($bytes, 2) . ' ' . $units[$unitIndex];
    }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function getIconClassAttribute(): string
    {
        return match($this->file_type) {
            'pdf' => 'fa-file-pdf text-red-500',
            'jpg', 'jpeg', 'png' => 'fa-file-image text-blue-500',
            'doc', 'docx' => 'fa-file-word text-blue-700',
            default => 'fa-file text-gray-500',
        };
    }

    // Helper methods
    public function verify(int $verifierId, ?string $notes = null): void
    {
        $this->update([
            'status' => 'verified',
            'verified_by' => $verifierId,
            'verified_at' => now(),
            'admin_notes' => $notes,
        ]);
    }

    public function reject(int $verifierId, string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'verified_by' => $verifierId,
            'verified_at' => now(),
            'admin_notes' => $reason,
        ]);
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function isImage(): bool
    {
        return in_array($this->file_type, ['jpg', 'jpeg', 'png', 'gif']);
    }

    public function isPdf(): bool
    {
        return $this->file_type === 'pdf';
    }
}
