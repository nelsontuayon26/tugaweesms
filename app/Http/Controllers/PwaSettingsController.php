<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PwaSettingsController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect('/login');
            }
            
            $roleName = strtolower($user->role?->name ?? '');
            
            // Map role to sidebar view
            $sidebarView = null;
            if ($roleName === 'teacher') {
                $sidebarView = 'teacher.includes.sidebar';
            } elseif ($roleName === 'pupil' || $roleName === 'student') {
                $sidebarView = 'student.includes.sidebar';
            } elseif ($roleName === 'principal') {
                $sidebarView = 'principal.includes.sidebar';
            } elseif ($roleName === 'admin' || $roleName === 'system admin') {
                $sidebarView = 'admin.includes.sidebar';
            }
            
            // Determine main content margin class
            $mainClass = ($roleName === 'principal') ? 'ml-0 lg:ml-[260px]' : 'ml-0 lg:ml-72';
            
            // Determine body background
            $bodyBg = ($roleName === 'principal') ? 'bg-[#fafaf9]' : 'bg-slate-50';
            
            return view('pwa-settings', compact('sidebarView', 'mainClass', 'bodyBg', 'roleName'));
        } catch (\Exception $e) {
            \Log::error('PwaSettingsController error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return view('pwa-settings', [
                'sidebarView' => null,
                'mainClass' => 'ml-0 lg:ml-72',
                'bodyBg' => 'bg-slate-50',
                'roleName' => '',
            ]);
        }
    }
}
