<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\SchoolYear;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessengerController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $roleName = strtolower($user->role?->name ?? '');
        
        $contacts = collect();
        
        try {
            if ($roleName === 'teacher') {
                // Auto-create teacher record if missing
                $teacher = Teacher::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'first_name' => $user->first_name ?? 'Teacher',
                        'last_name'  => $user->last_name ?? '',
                        'email'      => $user->email,
                    ]
                );
                
                // Get active school year
                $activeSchoolYear = SchoolYear::where('is_active', true)->first();
                
                // Build list of section IDs this teacher is assigned to
                $teacherSectionIds = collect();
                
                // 1) Sections where teacher is the adviser (teacher_id)
                $teacherSectionIds = $teacherSectionIds->merge(
                    Section::where('teacher_id', $teacher->id)->pluck('id')
                );
                
                // 2) Sections where teacher is assigned via teacher_sections pivot
                try {
                    $pivotSectionIds = \DB::table('teacher_sections')
                        ->where('teacher_id', $teacher->id)
                        ->pluck('section_id');
                    $teacherSectionIds = $teacherSectionIds->merge($pivotSectionIds)->unique()->values();
                } catch (\Exception $e) {
                    \Log::warning('Messenger: teacher_sections table query failed: ' . $e->getMessage());
                }
                
                \Log::info('Messenger: Teacher ' . $teacher->id . ' section IDs: ' . $teacherSectionIds->implode(', '));
                
                if ($teacherSectionIds->isNotEmpty()) {
                    // Find enrollments in teacher's sections for active school year
                    $enrollmentQuery = Enrollment::whereIn('section_id', $teacherSectionIds)
                        ->where('status', 'enrolled');
                    
                    if ($activeSchoolYear) {
                        $enrollmentQuery->where('school_year_id', $activeSchoolYear->id);
                    }
                    
                    $studentIds = $enrollmentQuery->pluck('student_id');
                    
                    // Get user IDs from those students
                    $userIds = Student::whereIn('id', $studentIds)
                        ->whereNotNull('user_id')
                        ->pluck('user_id');
                    
                    // Load contact users
                    $contacts = User::whereIn('id', $userIds)
                        ->where('id', '!=', $user->id)
                        ->get();
                    
                    \Log::info('Messenger: Found ' . $contacts->count() . ' contacts for teacher ' . $teacher->id);
                } else {
                    \Log::info('Messenger: No sections found for teacher ' . $teacher->id);
                }
                    
            } elseif ($roleName === 'pupil') {
                // Get active school year
                $activeSchoolYear = SchoolYear::where('is_active', true)->first();
                
                if ($activeSchoolYear) {
                    // Get student's current enrollment in the active school year
                    $enrollment = Enrollment::whereHas('student', function ($q) use ($user) {
                            $q->where('user_id', $user->id);
                        })
                        ->where('school_year_id', $activeSchoolYear->id)
                        ->where('status', 'enrolled')
                        ->with('section')
                        ->first();
                    
                    // Get teacher from the student's current section
                    if ($enrollment && $enrollment->section && $enrollment->section->teacher_id) {
                        $contacts = User::whereHas('teacher', function ($query) use ($enrollment) {
                                $query->where('id', $enrollment->section->teacher_id);
                            })->get();
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('MessengerController error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id,
                'role' => $roleName,
            ]);
        }
        
        return view('messenger.index', compact('contacts'));
    }
}
