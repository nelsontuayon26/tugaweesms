<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SchoolYear;

class SubjectController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $section = $student->section;
        $gradeLevel = $section ? $section->gradeLevel : null;

        // Get subjects based on grade level
        $subjects = collect();
        if ($gradeLevel) {
            $subjects = Subject::where('grade_level_id', $gradeLevel->id)
                ->orderBy('name')
                ->get();
        }

        // Get active school year
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        $schoolYear = $activeSchoolYear ? $activeSchoolYear->name : '2025-2026';

        return view('student.subjects.index', compact('subjects', 'schoolYear', 'student', 'section', 'gradeLevel'));
    }
}