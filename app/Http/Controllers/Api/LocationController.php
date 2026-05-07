<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    /**
     * Get all active school locations
     */
    public function getSchoolLocations()
    {
        $locations = SchoolLocation::where('is_active', true)
            ->select('id', 'name', 'type', 'latitude', 'longitude', 'radius_meters', 'address', 'require_location')
            ->get();

        return response()->json([
            'success' => true,
            'locations' => $locations
        ]);
    }

    /**
     * Verify if given coordinates are within school range
     */
    public function verifyLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric|min:0',
            'location_id' => 'nullable|exists:school_locations,id'
        ]);

        $lat = $request->latitude;
        $lng = $request->longitude;
        $accuracy = $request->accuracy;

        // Get school location (specific or nearest)
        if ($request->location_id) {
            $school = SchoolLocation::find($request->location_id);
        } else {
            $school = SchoolLocation::findNearest($lat, $lng);
        }

        if (!$school) {
            return response()->json([
                'success' => false,
                'error' => 'No school location configured',
                'verified' => false
            ], 404);
        }

        // Check if location verification is required
        if (!$school->require_location) {
            return response()->json([
                'success' => true,
                'verified' => true,
                'location_required' => false,
                'school' => [
                    'id' => $school->id,
                    'name' => $school->name,
                    'latitude' => $school->latitude,
                    'longitude' => $school->longitude,
                    'radius' => $school->radius_meters
                ]
            ]);
        }

        // Calculate distance
        $distance = $school->calculateDistance($lat, $lng);
        $withinRange = $distance <= $school->radius_meters;

        // Check time restrictions
        $timeAllowed = $school->isTimeAllowed();

        return response()->json([
            'success' => true,
            'verified' => $withinRange && $timeAllowed,
            'location_required' => true,
            'within_range' => $withinRange,
            'distance' => round($distance, 2),
            'radius' => $school->radius_meters,
            'time_allowed' => $timeAllowed,
            'accuracy' => $accuracy,
            'school' => [
                'id' => $school->id,
                'name' => $school->name,
                'latitude' => $school->latitude,
                'longitude' => $school->longitude,
                'radius' => $school->radius_meters,
                'address' => $school->address
            ],
            'position' => [
                'latitude' => $lat,
                'longitude' => $lng
            ]
        ]);
    }

    /**
     * Get nearest school location
     */
    public function getNearestLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        $school = SchoolLocation::findNearest($request->latitude, $request->longitude);

        if (!$school) {
            return response()->json([
                'success' => false,
                'error' => 'No school location found'
            ], 404);
        }

        $distance = $school->calculateDistance($request->latitude, $request->longitude);

        return response()->json([
            'success' => true,
            'school' => [
                'id' => $school->id,
                'name' => $school->name,
                'latitude' => $school->latitude,
                'longitude' => $school->longitude,
                'radius' => $school->radius_meters,
                'address' => $school->address,
                'require_location' => $school->require_location
            ],
            'distance' => round($distance, 2)
        ]);
    }

    /**
     * Admin: Get all school locations
     */
    public function getAllLocations()
    {
        $user = Auth::user();
        
        if (!$user || !in_array(strtolower($user->role?->name ?? ''), ['admin', 'system admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $locations = SchoolLocation::all();

        return response()->json([
            'success' => true,
            'locations' => $locations
        ]);
    }

    /**
     * Admin: Create new school location
     */
    public function createLocation(Request $request)
    {
        $user = Auth::user();
        
        if (!$user || !in_array(strtolower($user->role?->name ?? ''), ['admin', 'system admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_meters' => 'required|integer|min:10|max:5000',
            'address' => 'nullable|string',
            'require_location' => 'boolean',
            'allowed_schedules' => 'nullable|array'
        ]);

        $location = SchoolLocation::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'School location created successfully',
            'location' => $location
        ]);
    }

    /**
     * Admin: Update school location
     */
    public function updateLocation(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user || !in_array(strtolower($user->role?->name ?? ''), ['admin', 'system admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $location = SchoolLocation::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|max:50',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'radius_meters' => 'sometimes|integer|min:10|max:5000',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
            'require_location' => 'boolean',
            'allowed_schedules' => 'nullable|array'
        ]);

        $location->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'School location updated successfully',
            'location' => $location
        ]);
    }

    /**
     * Admin: Delete school location
     */
    public function deleteLocation($id)
    {
        $user = Auth::user();
        
        if (!$user || !in_array(strtolower($user->role?->name ?? ''), ['admin', 'system admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $location = SchoolLocation::findOrFail($id);
        $location->delete();

        return response()->json([
            'success' => true,
            'message' => 'School location deleted successfully'
        ]);
    }
}
