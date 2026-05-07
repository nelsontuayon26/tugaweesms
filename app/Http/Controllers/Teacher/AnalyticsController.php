<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Grade;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request, Section $section)
    {
        if ($section->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        $quarter = $request->get('quarter', 1);

        $students = $section->students()
            ->whereNotIn('status', ['completed', 'inactive'])
            ->with('user')
            ->get();

        $subjects = $section->gradeLevel->subjects ?? collect();

        // Get all grades for this section
        $grades = Grade::where('section_id', $section->id)
            ->where('school_year_id', $activeSchoolYear?->id)
            ->where('quarter', $quarter)
            ->where('component_type', 'final_grade')
            ->get();

        // Calculate analytics
        $analytics = $this->calculateAnalytics($students, $subjects, $grades);

        return view('teacher.analytics.index', compact(
            'section',
            'students',
            'subjects',
            'grades',
            'analytics',
            'quarter'
        ));
    }

    private function calculateAnalytics($students, $subjects, $grades)
    {
        $analytics = [
            'class_average' => 0,
            'highest_grade' => 0,
            'lowest_grade' => 100,
            'passing_rate' => 0,
            'grade_distribution' => [
                'excellent' => 0, // 90-100
                'very_good' => 0, // 85-89
                'good' => 0,      // 80-84
                'satisfactory' => 0, // 75-79
                'needs_improvement' => 0, // 70-74
                'poor' => 0,      // below 70
            ],
            'subject_averages' => [],
            'top_performers' => [],
            'needs_help' => [],
        ];

        if ($grades->isEmpty()) {
            return $analytics;
        }

        // Class average
        $analytics['class_average'] = round($grades->avg('final_grade'), 2);
        $analytics['highest_grade'] = $grades->max('final_grade');
        $analytics['lowest_grade'] = $grades->min('final_grade');

        // Passing rate (75 and above)
        $passingCount = $grades->where('final_grade', '>=', 75)->count();
        $analytics['passing_rate'] = $grades->count() > 0 
            ? round(($passingCount / $grades->count()) * 100, 1) 
            : 0;

        // Grade distribution
        foreach ($grades as $grade) {
            $fg = $grade->final_grade;
            if ($fg >= 90) $analytics['grade_distribution']['excellent']++;
            elseif ($fg >= 85) $analytics['grade_distribution']['very_good']++;
            elseif ($fg >= 80) $analytics['grade_distribution']['good']++;
            elseif ($fg >= 75) $analytics['grade_distribution']['satisfactory']++;
            elseif ($fg >= 70) $analytics['grade_distribution']['needs_improvement']++;
            else $analytics['grade_distribution']['poor']++;
        }

        // Subject averages
        $subjectGrades = $grades->groupBy('subject_id');
        foreach ($subjectGrades as $subjectId => $subjectGradeList) {
            $subject = $subjectGradeList->first()->subject;
            $analytics['subject_averages'][] = [
                'subject' => $subject?->name ?? 'Unknown',
                'average' => round($subjectGradeList->avg('final_grade'), 2),
                'highest' => $subjectGradeList->max('final_grade'),
                'lowest' => $subjectGradeList->min('final_grade'),
            ];
        }

        // Top performers (average across all subjects)
        $studentAverages = $grades->groupBy('student_id')->map(function($studentGrades) {
            return $studentGrades->avg('final_grade');
        })->sortDesc();

        $analytics['top_performers'] = $studentAverages->take(5)->map(function($avg, $studentId) {
            $student = \App\Models\Student::find($studentId);
            return [
                'name' => $student?->full_name ?? 'Unknown',
                'average' => round($avg, 2),
            ];
        })->values();

        // Students needing help (below 75 average)
        $analytics['needs_help'] = $studentAverages->filter(function($avg) {
            return $avg < 75;
        })->take(5)->map(function($avg, $studentId) {
            $student = \App\Models\Student::find($studentId);
            return [
                'name' => $student?->full_name ?? 'Unknown',
                'average' => round($avg, 2),
            ];
        })->values();

        return $analytics;
    }
}
