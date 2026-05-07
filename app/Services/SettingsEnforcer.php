<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rules\Password;

class SettingsEnforcer
{
    /**
     * Apply mail configuration from database settings to Laravel config.
     */
    public static function applyMailConfig(): void
    {
        $driver = Setting::get('mail_driver');
        if (empty($driver)) {
            return;
        }

        Config::set('mail.default', $driver);
        Config::set('mail.from.address', Setting::get('mail_from_address', config('mail.from.address')));
        Config::set('mail.from.name', Setting::get('mail_from_name', config('mail.from.name')));

        if ($driver === 'smtp') {
            Config::set('mail.mailers.smtp.host', Setting::get('mail_host', config('mail.mailers.smtp.host')));
            Config::set('mail.mailers.smtp.port', (int) Setting::get('mail_port', config('mail.mailers.smtp.port')));
            Config::set('mail.mailers.smtp.username', Setting::get('mail_username', config('mail.mailers.smtp.username')));
            Config::set('mail.mailers.smtp.password', Setting::get('mail_password', config('mail.mailers.smtp.password')));
            Config::set('mail.mailers.smtp.encryption', Setting::get('mail_encryption', config('mail.mailers.smtp.encryption')));
        }
    }

    /**
     * Apply session timeout from database settings.
     */
    public static function applySessionConfig(): void
    {
        // Only override if a valid database setting exists; otherwise respect .env/config default
        $dbTimeout = Setting::get('session_timeout');
        if ($dbTimeout !== null && $dbTimeout !== '' && (int) $dbTimeout > 0) {
            Config::set('session.lifetime', (int) $dbTimeout);
        }
        // If no DB setting, keep the value from config/session.php (which reads SESSION_LIFETIME from .env)
    }

    /**
     * Get dynamic password validation rules based on security settings.
     */
    public static function getPasswordRules(): Password
    {
        $minLength = (int) Setting::get('min_password_length', 8);
        $strong = (bool) Setting::get('strong_passwords', true);

        $rule = Password::min($minLength);

        if ($strong) {
            $rule = $rule->mixedCase()->numbers()->symbols();
        }

        return $rule;
    }

    /**
     * Check if a user's password has expired.
     */
    public static function isPasswordExpired(\App\Models\User $user): bool
    {
        $expiryDays = (int) Setting::get('password_expiry', 90);
        if ($expiryDays <= 0) {
            return false;
        }

        $lastChanged = $user->password_updated_at ?? $user->created_at ?? now();
        return $lastChanged->diffInDays(now()) >= $expiryDays;
    }

    /**
     * Get appearance CSS variables and body classes.
     */
    public static function getAppearanceData(): array
    {
        return [
            'css' => self::buildAppearanceCss(),
            'body_class' => self::buildBodyClass(),
        ];
    }

    private static function buildAppearanceCss(): string
    {
        $primary = Setting::get('primary_color', '#6366f1');
        $secondary = Setting::get('secondary_color', '#10b981');
        $accent = Setting::get('accent_color', '#f59e0b');

        return <<<CSS
            :root {
                --primary-color: {$primary};
                --secondary-color: {$secondary};
                --accent-color: {$accent};
            }
        CSS;
    }

    private static function buildBodyClass(): string
    {
        $classes = [];

        if ((bool) Setting::get('dark_mode', false)) {
            $classes[] = 'dark-mode';
        }

        if ((bool) Setting::get('compact_mode', false)) {
            $classes[] = 'compact-mode';
        }

        if (!(bool) Setting::get('animations', true)) {
            $classes[] = 'animations-disabled';
        }

        return implode(' ', $classes);
    }

    /**
     * Get the configured max login attempts per minute.
     */
    public static function getMaxLoginAttempts(): int
    {
        return (int) Setting::get('max_login_attempts', 5);
    }

    /**
     * Check if user registration is enabled.
     */
    public static function isRegistrationEnabled(): bool
    {
        $val = Setting::get('user_registration', true);
        return $val === true || $val === '1' || $val === 1;
    }

    /**
     * Check if login notifications are enabled.
     */
    public static function isLoginNotificationsEnabled(): bool
    {
        $val = Setting::get('login_notifications', true);
        return $val === true || $val === '1' || $val === 1;
    }
}
