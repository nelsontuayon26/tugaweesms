<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolLocation extends Model
{
    protected $fillable = [
        'name',
        'type',
        'latitude',
        'longitude',
        'radius_meters',
        'address',
        'is_active',
        'require_location',
        'allowed_schedules'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
        'require_location' => 'boolean',
        'allowed_schedules' => 'array'
    ];

    /**
     * Get active school locations
     */
    public static function getActive()
    {
        return self::where('is_active', true)->get();
    }

    /**
     * Check if coordinates are within allowed radius
     */
    public function isWithinRange($lat, $lng)
    {
        $distance = $this->calculateDistance($lat, $lng);
        return $distance <= $this->radius_meters;
    }

    /**
     * Calculate distance between two coordinates in meters using Haversine formula
     */
    public function calculateDistance($lat, $lng)
    {
        $earthRadius = 6371000; // Earth's radius in meters

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($lat);
        $lonTo = deg2rad($lng);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        return $angle * $earthRadius;
    }

    /**
     * Find nearest school location to given coordinates
     */
    public static function findNearest($lat, $lng)
    {
        $locations = self::where('is_active', true)->get();
        
        if ($locations->isEmpty()) {
            return null;
        }

        $nearest = null;
        $minDistance = PHP_FLOAT_MAX;

        foreach ($locations as $location) {
            $distance = $location->calculateDistance($lat, $lng);
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $nearest = $location;
            }
        }

        return $nearest;
    }

    /**
     * Check if attendance is allowed at current time
     */
    public function isTimeAllowed()
    {
        if (empty($this->allowed_schedules)) {
            return true; // No restrictions
        }

        $now = now();
        $currentTime = $now->format('H:i');
        $currentDay = strtolower($now->format('l'));

        foreach ($this->allowed_schedules as $schedule) {
            if (in_array($currentDay, $schedule['days'] ?? [])) {
                if ($currentTime >= $schedule['start'] && $currentTime <= $schedule['end']) {
                    return true;
                }
            }
        }

        return false;
    }
}
