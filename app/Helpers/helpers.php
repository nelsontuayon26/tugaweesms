<?php

use Carbon\Carbon;

if (!function_exists('profile_photo_url')) {
    /**
     * Get profile photo URL, handling both file paths and base64 data URIs.
     *
     * @param string|null $photo
     * @return string|null
     */
    function profile_photo_url($photo)
    {
        if (!$photo) {
            return null;
        }

        // If it's already a data URI, return as-is
        if (str_starts_with($photo, 'data:image')) {
            return $photo;
        }

        // Otherwise, treat as a storage path
        return asset('storage/' . $photo);
    }
}

if (!function_exists('calculateAge')) {
    /**
     * Calculate age from birth date and optional reference date.
     *
     * @param string|null $birthDate
     * @param string|null $referenceDate
     * @return int|null
     */
    function calculateAge($birthDate, $referenceDate = null)
    {
        if (!$birthDate) {
            return null;
        }

        $birth = Carbon::parse($birthDate);
        $reference = $referenceDate ? Carbon::parse($referenceDate) : now();

        return $birth->diffInYears($reference);
    }
}