<?php

if (!function_exists('setting')) {
    /**
     * Get or set application settings
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key = null, $default = null): mixed
    {
        if (is_null($key)) {
            return \App\Models\Setting::getAll();
        }

        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('setting_set')) {
    /**
     * Set application setting
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string $group
     * @return void
     */
    function setting_set(string $key, $value, string $type = 'string', string $group = 'general'): void
    {
        \App\Models\Setting::set($key, $value, $type, $group);
    }
}