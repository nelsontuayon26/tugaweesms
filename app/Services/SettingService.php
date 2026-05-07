<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SettingService
{
    /**
     * Get all settings for the settings page
     */
    public function getAllSettings(): array
    {
        return Setting::getAll();
    }

    /**
     * Update multiple settings at once
     */
    public function updateSettings(array $data): void
    {
        $settingGroups = [
            'general' => [
                'system_name', 'timezone', 'date_format', 'default_language',
                'maintenance_mode', 'user_registration', 'email_verification'
            ],
            'school' => [
                'school_name', 'school_code', 'deped_school_id', 'school_address',
                'school_email', 'school_phone', 'school_logo',
                'school_division', 'school_region', 'school_district', 'school_head',
                'active_school_year_id'
            ],
            'academic' => [
                'current_school_year', 'school_year_start', 'school_year_end',
                'grading_system', 'passing_grade', 'enrollment_start_date',
                'enrollment_end_date', 'allow_late_enrollment'
            ],
            'notifications' => [
                'notify_new_student', 'notify_attendance', 'notify_grades',
                'notify_announcements', 'sms_enabled', 'sms_provider'
            ],
            'email' => [
                'mail_driver', 'mail_host', 'mail_port', 'mail_username',
                'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name'
            ],
            'security' => [
                'min_password_length', 'password_expiry', 'strong_passwords',
                'require_2fa', 'session_timeout', 'max_login_attempts', 'login_notifications'
            ],
            'appearance' => [
                'primary_color', 'secondary_color', 'accent_color',
                'compact_mode', 'dark_mode', 'animations'
            ],
            'backup' => [
                'auto_backup', 'last_backup'
            ],
            'advanced' => [
                'api_enabled', 'api_key'
            ],
            'enrollment' => [
                'enrollment_enabled'
            ]
        ];

        foreach ($settingGroups as $group => $keys) {
            foreach ($keys as $key) {
                if (array_key_exists($key, $data)) {
                    $this->updateSetting($key, $data[$key], $group);
                }
            }
        }

        // Clear cache after bulk update
        Setting::clearCache();
    }

    /**
     * Update a single setting
     */
    public function updateSetting(string $key, $value, string $group = 'general'): void
    {
        $type = $this->determineType($key, $value);
        
        Setting::set($key, $value, $type, $group);
    }

    /**
     * Handle file upload for settings
     */
    public function handleFileUpload(UploadedFile $file, string $key): string
    {
        // Delete old file if exists
        $oldValue = Setting::get($key);
        if ($oldValue && Storage::disk('public')->exists($oldValue)) {
            Storage::disk('public')->delete($oldValue);
        }

        // Store new file
        $path = $file->store('settings', 'public');
        
        // Update setting
        Setting::set($key, $path, 'string', 'school');

        return $path;
    }

    /**
     * Delete a setting
     */
    public function deleteSetting(string $key): void
    {
        Setting::where('key', $key)->delete();
        Setting::clearCache();
    }

    /**
     * Reset all settings to defaults
     */
    public function resetToDefaults(): void
    {
        Setting::truncate();
        $this->seedDefaultSettings();
        Setting::clearCache();
    }

    /**
     * Seed default settings
     */
    public function seedDefaultSettings(): void
    {
        $defaults = [
            // General
            ['key' => 'system_name', 'value' => 'Tugawe Elementary School', 'type' => 'string', 'group' => 'general'],
            ['key' => 'timezone', 'value' => 'Asia/Manila', 'type' => 'string', 'group' => 'general'],
            ['key' => 'date_format', 'value' => 'F d, Y', 'type' => 'string', 'group' => 'general'],
            ['key' => 'default_language', 'value' => 'en', 'type' => 'string', 'group' => 'general'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'general'],
            ['key' => 'user_registration', 'value' => '1', 'type' => 'boolean', 'group' => 'general'],
            ['key' => 'email_verification', 'value' => '1', 'type' => 'boolean', 'group' => 'general'],

            // School
            ['key' => 'school_name', 'value' => 'Tugawe Elementary School', 'type' => 'string', 'group' => 'school'],
            ['key' => 'school_code', 'value' => 'TES-2024', 'type' => 'string', 'group' => 'school'],
            ['key' => 'deped_school_id', 'value' => '', 'type' => 'string', 'group' => 'school'],
            ['key' => 'school_address', 'value' => '', 'type' => 'string', 'group' => 'school'],
            ['key' => 'school_email', 'value' => '', 'type' => 'string', 'group' => 'school'],
            ['key' => 'school_phone', 'value' => '', 'type' => 'string', 'group' => 'school'],
            ['key' => 'school_logo', 'value' => '', 'type' => 'string', 'group' => 'school'],
            // Legacy keys preserved
            ['key' => 'school_division', 'value' => '', 'type' => 'string', 'group' => 'school'],
            ['key' => 'school_region', 'value' => '', 'type' => 'string', 'group' => 'school'],
            ['key' => 'school_head', 'value' => '', 'type' => 'string', 'group' => 'school'],
            ['key' => 'active_school_year_id', 'value' => '', 'type' => 'string', 'group' => 'school'],
            ['key' => 'school_district', 'value' => '', 'type' => 'string', 'group' => 'school'],

            // Academic
            ['key' => 'current_school_year', 'value' => '2024-2025', 'type' => 'string', 'group' => 'academic'],
            ['key' => 'school_year_start', 'value' => '', 'type' => 'string', 'group' => 'academic'],
            ['key' => 'school_year_end', 'value' => '', 'type' => 'string', 'group' => 'academic'],
            ['key' => 'grading_system', 'value' => 'quarterly', 'type' => 'string', 'group' => 'academic'],
            ['key' => 'passing_grade', 'value' => '75', 'type' => 'integer', 'group' => 'academic'],
            ['key' => 'enrollment_start_date', 'value' => '', 'type' => 'string', 'group' => 'academic'],
            ['key' => 'enrollment_end_date', 'value' => '', 'type' => 'string', 'group' => 'academic'],
            ['key' => 'allow_late_enrollment', 'value' => '0', 'type' => 'boolean', 'group' => 'academic'],

            // Notifications
            ['key' => 'notify_new_student', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications'],
            ['key' => 'notify_attendance', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications'],
            ['key' => 'notify_grades', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications'],
            ['key' => 'notify_announcements', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications'],
            ['key' => 'sms_enabled', 'value' => '0', 'type' => 'boolean', 'group' => 'notifications'],
            ['key' => 'sms_provider', 'value' => '', 'type' => 'string', 'group' => 'notifications'],

            // Email
            ['key' => 'mail_driver', 'value' => 'resend', 'type' => 'string', 'group' => 'email'],
            ['key' => 'mail_host', 'value' => '', 'type' => 'string', 'group' => 'email'],
            ['key' => 'mail_port', 'value' => '587', 'type' => 'integer', 'group' => 'email'],
            ['key' => 'mail_username', 'value' => '', 'type' => 'string', 'group' => 'email'],
            ['key' => 'mail_password', 'value' => '', 'type' => 'string', 'group' => 'email'],
            ['key' => 'mail_encryption', 'value' => 'tls', 'type' => 'string', 'group' => 'email'],
            ['key' => 'mail_from_address', 'value' => '', 'type' => 'string', 'group' => 'email'],
            ['key' => 'mail_from_name', 'value' => 'Tugawe Elementary School', 'type' => 'string', 'group' => 'email'],

            // Security
            ['key' => 'min_password_length', 'value' => '8', 'type' => 'integer', 'group' => 'security'],
            ['key' => 'password_expiry', 'value' => '90', 'type' => 'integer', 'group' => 'security'],
            ['key' => 'strong_passwords', 'value' => '1', 'type' => 'boolean', 'group' => 'security'],
            ['key' => 'require_2fa', 'value' => '0', 'type' => 'boolean', 'group' => 'security'],
            ['key' => 'session_timeout', 'value' => '30', 'type' => 'integer', 'group' => 'security'],
            ['key' => 'max_login_attempts', 'value' => '5', 'type' => 'integer', 'group' => 'security'],
            ['key' => 'login_notifications', 'value' => '1', 'type' => 'boolean', 'group' => 'security'],

            // Appearance
            ['key' => 'primary_color', 'value' => '#6366f1', 'type' => 'string', 'group' => 'appearance'],
            ['key' => 'secondary_color', 'value' => '#10b981', 'type' => 'string', 'group' => 'appearance'],
            ['key' => 'accent_color', 'value' => '#f59e0b', 'type' => 'string', 'group' => 'appearance'],
            ['key' => 'compact_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'appearance'],
            ['key' => 'dark_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'appearance'],
            ['key' => 'animations', 'value' => '1', 'type' => 'boolean', 'group' => 'appearance'],

            // Backup
            ['key' => 'auto_backup', 'value' => '0', 'type' => 'boolean', 'group' => 'backup'],
            ['key' => 'last_backup', 'value' => 'Never', 'type' => 'string', 'group' => 'backup'],

            // Advanced
            ['key' => 'api_enabled', 'value' => '0', 'type' => 'boolean', 'group' => 'advanced'],
            ['key' => 'api_key', 'value' => '', 'type' => 'string', 'group' => 'advanced'],

            // Enrollment
            ['key' => 'enrollment_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'enrollment'],
        ];

        foreach ($defaults as $setting) {
            Setting::create($setting);
        }
    }

    /**
     * Determine the type of setting based on key and value
     */
    private function determineType(string $key, $value): string
    {
        // Boolean fields
        $booleans = [
            'maintenance_mode', 'user_registration', 'email_verification',
            'notify_new_student', 'notify_attendance', 'notify_grades', 'notify_announcements',
            'sms_enabled', 'strong_passwords', 'require_2fa', 'login_notifications',
            'compact_mode', 'dark_mode', 'animations', 'auto_backup', 'api_enabled',
            'allow_late_enrollment', 'enrollment_enabled'
        ];

        // Integer fields
        $integers = [
            'passing_grade', 'min_password_length', 'password_expiry',
            'session_timeout', 'max_login_attempts', 'mail_port'
        ];

        if (in_array($key, $booleans)) {
            return 'boolean';
        }

        if (in_array($key, $integers)) {
            return 'integer';
        }

        return 'string';
    }

    /**
     * Create database backup
     */
    public function createBackup(): string
    {
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $filename);

        // Ensure backup directory exists
        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        // Get database credentials from config
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        // Create backup using mysqldump
        $command = sprintf(
            'mysqldump -h %s -u %s -p%s %s > %s',
            escapeshellarg($host),
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($database),
            escapeshellarg($path)
        );

        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            Setting::set('last_backup', now()->format('F d, Y h:i A'), 'string', 'backup');
            return $path;
        }

        throw new \Exception('Backup creation failed');
    }

    /**
     * Clear application cache
     */
    public function clearCache(): void
    {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        Setting::clearCache();
    }
}
