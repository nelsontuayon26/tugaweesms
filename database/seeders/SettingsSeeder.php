<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // We intentionally do NOT truncate and do NOT update existing
        // settings so we don't delete or overwrite live user data.
        $settings = [
            // General Settings
            ['key' => 'system_name', 'value' => 'Tugawe Elementary School', 'type' => 'string', 'group' => 'general', 'description' => 'The name of the system displayed throughout the application'],
            ['key' => 'timezone', 'value' => 'Asia/Manila', 'type' => 'string', 'group' => 'general', 'description' => 'Default timezone for the application'],
            ['key' => 'date_format', 'value' => 'F d, Y', 'type' => 'string', 'group' => 'general', 'description' => 'Format for displaying dates'],
            ['key' => 'default_language', 'value' => 'en', 'type' => 'string', 'group' => 'general', 'description' => 'Default language for the application'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'general', 'description' => 'Enable maintenance mode'],
            ['key' => 'user_registration', 'value' => '1', 'type' => 'boolean', 'group' => 'general', 'description' => 'Allow new user registration'],
            ['key' => 'email_verification', 'value' => '1', 'type' => 'boolean', 'group' => 'general', 'description' => 'Require email verification for new accounts'],

            // School Information
            ['key' => 'school_name', 'value' => 'Tugawe Elementary School', 'type' => 'string', 'group' => 'school', 'description' => 'Official name of the school'],
            ['key' => 'school_code', 'value' => 'TES-2024', 'type' => 'string', 'group' => 'school', 'description' => 'School code identifier'],
            ['key' => 'deped_school_id', 'value' => '', 'type' => 'string', 'group' => 'school', 'description' => 'DepEd assigned school ID'],
            ['key' => 'school_address', 'value' => '', 'type' => 'string', 'group' => 'school', 'description' => 'Complete school address'],
            ['key' => 'school_email', 'value' => '', 'type' => 'string', 'group' => 'school', 'description' => 'School contact email'],
            ['key' => 'school_phone', 'value' => '', 'type' => 'string', 'group' => 'school', 'description' => 'School contact phone number'],
            ['key' => 'school_logo', 'value' => '', 'type' => 'string', 'group' => 'school', 'description' => 'Path to school logo image'],

            // Legacy school keys (preserve existing DB data)
            ['key' => 'school_division', 'value' => '', 'type' => 'string', 'group' => 'school', 'description' => 'School division'],
            ['key' => 'school_region', 'value' => '', 'type' => 'string', 'group' => 'school', 'description' => 'School region'],
            ['key' => 'school_head', 'value' => '', 'type' => 'string', 'group' => 'school', 'description' => 'School head/principal'],
            ['key' => 'active_school_year_id', 'value' => '', 'type' => 'string', 'group' => 'school', 'description' => 'Active school year ID'],
            ['key' => 'school_district', 'value' => '', 'type' => 'string', 'group' => 'school', 'description' => 'School district'],

            // Academic Settings
            ['key' => 'current_school_year', 'value' => '2024-2025', 'type' => 'string', 'group' => 'academic', 'description' => 'Current active school year'],
            ['key' => 'school_year_start', 'value' => '', 'type' => 'string', 'group' => 'academic', 'description' => 'Start date of the current school year'],
            ['key' => 'school_year_end', 'value' => '', 'type' => 'string', 'group' => 'academic', 'description' => 'End date of the current school year'],
            ['key' => 'grading_system', 'value' => 'quarterly', 'type' => 'string', 'group' => 'academic', 'description' => 'Grading period system'],
            ['key' => 'passing_grade', 'value' => '75', 'type' => 'integer', 'group' => 'academic', 'description' => 'Minimum passing grade percentage'],
            ['key' => 'enrollment_start_date', 'value' => '', 'type' => 'string', 'group' => 'academic', 'description' => 'Enrollment period start date'],
            ['key' => 'enrollment_end_date', 'value' => '', 'type' => 'string', 'group' => 'academic', 'description' => 'Enrollment period end date'],
            ['key' => 'allow_late_enrollment', 'value' => '0', 'type' => 'boolean', 'group' => 'academic', 'description' => 'Allow enrollment after the deadline'],
            ['key' => 'enrollment_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'enrollment', 'description' => 'Allow students to submit enrollment requests'],

            // Notification Settings
            ['key' => 'notify_new_student', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications', 'description' => 'Send email on new student enrollment'],
            ['key' => 'notify_attendance', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications', 'description' => 'Notify parents of student absences'],
            ['key' => 'notify_grades', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications', 'description' => 'Send notification when grades are published'],
            ['key' => 'notify_announcements', 'value' => '1', 'type' => 'boolean', 'group' => 'notifications', 'description' => 'Send notification for new announcements'],
            ['key' => 'sms_enabled', 'value' => '0', 'type' => 'boolean', 'group' => 'notifications', 'description' => 'Enable SMS notifications'],
            ['key' => 'sms_provider', 'value' => '', 'type' => 'string', 'group' => 'notifications', 'description' => 'SMS gateway provider'],

            // Email (SMTP) Settings
            ['key' => 'mail_driver', 'value' => 'smtp', 'type' => 'string', 'group' => 'email', 'description' => 'Mail driver'],
            ['key' => 'mail_host', 'value' => '', 'type' => 'string', 'group' => 'email', 'description' => 'SMTP host'],
            ['key' => 'mail_port', 'value' => '587', 'type' => 'integer', 'group' => 'email', 'description' => 'SMTP port'],
            ['key' => 'mail_username', 'value' => '', 'type' => 'string', 'group' => 'email', 'description' => 'SMTP username'],
            ['key' => 'mail_password', 'value' => '', 'type' => 'string', 'group' => 'email', 'description' => 'SMTP password'],
            ['key' => 'mail_encryption', 'value' => 'tls', 'type' => 'string', 'group' => 'email', 'description' => 'SMTP encryption'],
            ['key' => 'mail_from_address', 'value' => '', 'type' => 'string', 'group' => 'email', 'description' => 'From email address'],
            ['key' => 'mail_from_name', 'value' => 'Tugawe Elementary School', 'type' => 'string', 'group' => 'email', 'description' => 'From name'],

            // Security Settings
            ['key' => 'min_password_length', 'value' => '8', 'type' => 'integer', 'group' => 'security', 'description' => 'Minimum required password length'],
            ['key' => 'password_expiry', 'value' => '90', 'type' => 'integer', 'group' => 'security', 'description' => 'Days until password expires (0 = never)'],
            ['key' => 'strong_passwords', 'value' => '1', 'type' => 'boolean', 'group' => 'security', 'description' => 'Require complex passwords'],
            ['key' => 'require_2fa', 'value' => '0', 'type' => 'boolean', 'group' => 'security', 'description' => 'Require two-factor authentication'],
            ['key' => 'session_timeout', 'value' => '30', 'type' => 'integer', 'group' => 'security', 'description' => 'Session timeout in minutes'],
            ['key' => 'max_login_attempts', 'value' => '5', 'type' => 'integer', 'group' => 'security', 'description' => 'Maximum failed login attempts per minute'],
            ['key' => 'login_notifications', 'value' => '1', 'type' => 'boolean', 'group' => 'security', 'description' => 'Send email on new login'],

            // Appearance Settings
            ['key' => 'primary_color', 'value' => '#6366f1', 'type' => 'string', 'group' => 'appearance', 'description' => 'Primary brand color'],
            ['key' => 'secondary_color', 'value' => '#10b981', 'type' => 'string', 'group' => 'appearance', 'description' => 'Secondary accent color'],
            ['key' => 'accent_color', 'value' => '#f59e0b', 'type' => 'string', 'group' => 'appearance', 'description' => 'Accent color'],
            ['key' => 'compact_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'appearance', 'description' => 'Use compact spacing'],
            ['key' => 'dark_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'appearance', 'description' => 'Enable dark theme'],
            ['key' => 'animations', 'value' => '1', 'type' => 'boolean', 'group' => 'appearance', 'description' => 'Enable UI animations'],

            // Backup Settings
            ['key' => 'auto_backup', 'value' => '0', 'type' => 'boolean', 'group' => 'backup', 'description' => 'Enable automatic daily backups'],
            ['key' => 'last_backup', 'value' => 'Never', 'type' => 'string', 'group' => 'backup', 'description' => 'Last backup timestamp'],

            // Advanced Settings
            ['key' => 'api_enabled', 'value' => '0', 'type' => 'boolean', 'group' => 'advanced', 'description' => 'Enable API access'],
            ['key' => 'api_key', 'value' => '', 'type' => 'string', 'group' => 'advanced', 'description' => 'API authentication key'],
        ];

        foreach ($settings as $setting) {
            // Only create if missing — never overwrite existing user settings
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        // Clear cache so new defaults are picked up immediately
        Setting::clearCache();

        $this->command->info('Settings seeded/updated successfully!');
    }
}
