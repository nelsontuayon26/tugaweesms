<?php
// app/Http/Controllers/Teacher/TeacherSettingController.php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($session) {
                $session->is_current = $session->id === session()->getId();
                $session->device_name = $this->getDeviceName($session->user_agent);
                $session->device_type = $this->getDeviceType($session->user_agent);
                $session->location = 'Manila, Philippines'; // Use IP geolocation in production
                $session->last_active = now(); // Use last_activity timestamp in production
                return $session;
            });
        
        return view('teacher.settings.index', compact('sessions'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Handle password change
        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Current password is incorrect.');
            }
            
            $request->validate([
                'new_password' => ['required', \App\Services\SettingsEnforcer::getPasswordRules(), 'confirmed'],
            ]);
            
            $user->password = Hash::make($request->new_password);
            $user->password_updated_at = now();
        }
        
        // Handle 2FA toggle
        if ($request->has('two_factor_enabled')) {
            $user->two_factor_enabled = $request->boolean('two_factor_enabled');
        }
        
        // Handle settings
        if ($request->has('settings')) {
            $currentSettings = $user->settings ?? [];
            $newSettings = array_merge($currentSettings, $request->settings);
            $user->settings = $newSettings;
        }
        
        $user->save();
        
        return back()->with('success', 'Settings updated successfully.');
    }

    public function revokeSession($sessionId)
    {
        DB::table('sessions')->where('id', $sessionId)->delete();
        return back()->with('success', 'Session revoked successfully.');
    }

    public function revokeAllSessions()
    {
        DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', '!=', session()->getId())
            ->delete();
        return back()->with('success', 'All other sessions revoked.');
    }

    public function exportData()
    {
        $user = Auth::user();
        $data = [
            'personal_info' => $user->only(['first_name', 'last_name', 'email', 'username']),
            'teacher_profile' => $user->teacher ? $user->teacher->toArray() : null,
            'settings' => $user->settings,
            'export_date' => now()->toDateTimeString(),
        ];
        
        return response()->json($data, 200, [
            'Content-Disposition' => 'attachment; filename="my-data.json"'
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirmation' => 'required|in:DELETE',
        ]);
        
        $user = Auth::user();
        
        // Logout and delete
        Auth::logout();
        $user->delete();
        
        return redirect('/')->with('success', 'Your account has been deleted.');
    }

    private function getDeviceName($userAgent)
    {
        if (str_contains($userAgent, 'Windows')) return 'Windows PC';
        if (str_contains($userAgent, 'Macintosh')) return 'Mac';
        if (str_contains($userAgent, 'iPhone')) return 'iPhone';
        if (str_contains($userAgent, 'iPad')) return 'iPad';
        if (str_contains($userAgent, 'Android')) return 'Android Device';
        return 'Unknown Device';
    }

    private function getDeviceType($userAgent)
    {
        if (str_contains($userAgent, 'Mobile')) return 'mobile';
        if (str_contains($userAgent, 'iPad') || str_contains($userAgent, 'Tablet')) return 'tablet';
        return 'desktop';
    }
}