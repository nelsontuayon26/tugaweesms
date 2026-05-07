<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\GradeLevel;
use App\Services\SettingsEnforcer;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view (for Admin and Teachers).
     */
    public function create(Request $request): \Illuminate\Http\Response
    {
        // Ensure session is started with a valid CSRF token
        if (!$request->session()->isStarted()) {
            $request->session()->start();
        }
        
        $announcements = \App\Models\Announcement::with(['author.role', 'author.teacher.sections.gradeLevel'])
            ->whereHas('author.role', function ($q) {
                $q->where('name', 'System Admin');
            })
            ->latest()
            ->take(6)
            ->get();

        $teachers = \App\Models\Teacher::with(['sections.gradeLevel', 'user'])
            ->whereHas('user', function ($q) {
                $q->whereHas('role', function ($r) {
                    $r->where('name', 'Teacher');
                });
            })
            ->where(function ($q) {
                $q->whereNotNull('first_name')->where('first_name', '!=', '')
                  ->orWhereNotNull('last_name')->where('last_name', '!=', '');
            })
            ->get();

        $studentCount = \App\Models\Student::whereHas('enrollments', function($q) {
            $q->where('status', 'enrolled');
        })->count();

        $sectionCount = \App\Models\Section::whereHas('schoolYear', function($q) {
            $q->where('is_active', true);
        })->count();

        $gradeLevels = GradeLevel::all();

        $principal = $teachers->where('position', 'Principal')->first();
        $vicePrincipals = $teachers->whereIn('position', ['Vice Principal', 'Assistant Principal']);
        $teachingStaff = $teachers->whereNotIn('position', ['Principal', 'Vice Principal', 'Assistant Principal']);

        $response = response()->view('auth.login', compact(
            'announcements', 'teachers', 'studentCount', 'sectionCount',
            'gradeLevels', 'principal', 'vicePrincipals', 'teachingStaff'
        ));
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        return $response;
    }
    
    /**
     * Handle an incoming authentication request using username.
     * Handles all roles: Admin, Teacher, Registrar, and Student.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Check password expiry
            if (SettingsEnforcer::isPasswordExpired($user)) {
                Auth::logout();
                return redirect()->route('password.expired');
            }

            $roleName = strtolower($user->role?->name ?? '');
            $displayRole = $user->role?->name ?? 'User';

            // Check if student is approved/active
            if ($roleName === 'pupil') {
                $student = $user->student;
                if (!$student || $student->status !== 'active') {
                    Auth::logout();
                    return redirect()->route('login')
                        ->with('pending_approval', true)
                        ->withErrors([
                            'login' => 'Your registration is pending admin approval. You cannot log in yet.'
                        ]);
                }

                session()->flash('signing_in_role', $displayRole);
                session()->flash('signing_in_redirect', route('student.dashboard'));
                return redirect()->route('auth.signing-in');
            }

            // Role-based redirect for Admin/Teacher/Registrar
            $redirectUrl = match ($roleName) {
                'system admin', 'admin' => Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin/dashboard'),
                'principal' => Route::has('principal.dashboard') ? route('principal.dashboard') : url('/principal/dashboard'),
                'registrar' => Route::has('registrar.dashboard') ? route('registrar.dashboard') : url('/registrar/dashboard'),
                'teacher' => Route::has('teacher.dashboard') ? route('teacher.dashboard') : url('/teacher/dashboard'),
                default => null,
            };

            if (!$redirectUrl) {
                return redirect()->route('login')->withErrors([
                    'login' => 'Access denied. Unrecognized user role.',
                ]);
            }

            session()->flash('signing_in_role', $displayRole);
            session()->flash('signing_in_redirect', $redirectUrl);

            if (Route::has('auth.signing-in')) {
                return redirect()->route('auth.signing-in');
            }

            return redirect($redirectUrl);
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        
        // Regenerate session ID but keep session data to avoid 419 errors
        $request->session()->regenerate(true);
        
        // Flash message for successful logout
        return redirect('/')->with('status', 'You have been logged out successfully.');
    }
}