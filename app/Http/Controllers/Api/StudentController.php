<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    /**
     * Look up student by LRN
     */
    public function lookupByLrn(Request $request)
    {
        try {
            $request->validate([
                'lrn' => 'required|string|size:12'
            ]);

            $student = Student::where('lrn', $request->lrn)
                ->with(['gradeLevel', 'schoolYear', 'user'])
                ->first();

            if (!$student) {
                return response()->json([
                    'found' => false,
                    'message' => 'Student not found'
                ]);
            }

            // Safely get student data
            $studentData = [
                'id' => $student->id,
                'lrn' => $student->lrn,
                'full_name' => $student->user ? 
                    trim($student->user->first_name . ' ' . ($student->user->middle_name ? $student->user->middle_name . ' ' : '') . $student->user->last_name) :
                    'Unknown',
                'first_name' => $student->user?->first_name ?? '',
                'middle_name' => $student->user?->middle_name ?? '',
                'last_name' => $student->user?->last_name ?? '',
                'grade_level' => $student->gradeLevel?->name ?? 'Not assigned',
                'school_year' => $student->schoolYear?->name ?? \App\Models\SchoolYear::where('is_active', true)->first()?->name ?? 'N/A',
            ];

            return response()->json([
                'found' => true,
                'student' => $studentData
            ]);

        } catch (\Exception $e) {
            Log::error('Student lookup error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'lrn' => $request->lrn ?? 'N/A'
            ]);
            
            return response()->json([
                'found' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}
