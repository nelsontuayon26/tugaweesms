<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Grade;
use App\Models\GradeLevel;
use App\Models\GradeWeight;
use App\Models\Subject;
use App\Models\Setting;
use App\Models\SchoolYear;
use App\Services\FinalizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    protected $finalizationService;

    public function __construct(FinalizationService $finalizationService)
    {
        $this->finalizationService = $finalizationService;
    }

    /**
     * Check if the current teacher can access grades for this section/subject
     */
    private function canAccessGrades(Section $section, ?Subject $subject = null): bool
    {
        $teacher = auth()->user()->teacher;
        
        // Adviser always has access
        if ($section->teacher_id === $teacher->id) {
            return true;
        }
        
        // Non-adviser must have a subject assignment for this section
        $query = DB::table('teacher_subject')
            ->where('teacher_id', $teacher->id)
            ->where('section_id', $section->id);
        
        if ($subject) {
            $query->where('subject_id', $subject->id);
        }
        
        return $query->exists();
    }

    public function index(Request $request, Section $section)
    {
        if (!$this->canAccessGrades($section)) {
            abort(403, 'You are not authorized to access grades for this section.');
        }

        $activeSchoolYear = SchoolYear::where('is_active', true)->first();

        // Security: section must belong to active school year
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }
        
        // Check finalization status
        $finalization = null;
        $isEditable = true;
        $quarterInfo = null;
        // Auto-detect current quarter from admin-set dates, fallback to request or Q1
        $currentQuarterFromDates = $activeSchoolYear?->currentQuarter();
        $defaultQuarter = $currentQuarterFromDates?->quarter_number ?? 1;
        $requestedQuarter = (int) $request->get('quarter', $defaultQuarter);
        
        if ($activeSchoolYear) {
            $finalization = $this->finalizationService->getOrCreateFinalization($section->id, $activeSchoolYear->id);
            
            // Editable only if section is not locked AND grades are not finalized
            $isEditable = !$finalization->is_locked && !$finalization->grades_finalized;
            
            // Load quarter info for display and date validation
            $activeSchoolYear->load('quarters');
            $quarterInfo = $activeSchoolYear->quarters->firstWhere('quarter_number', $requestedQuarter);
            
            // If quarter dates are set, lock editing outside the quarter date range
            if ($quarterInfo && $quarterInfo->start_date && $quarterInfo->end_date) {
                $today = now()->startOfDay();
                $qStart = $quarterInfo->start_date->startOfDay();
                $qEnd = $quarterInfo->end_date->startOfDay();
                
                if ($today->lt($qStart) || $today->gt($qEnd)) {
                    $isEditable = false;
                }
            }
        }

        $students = $section->students()
            ->whereNotIn('status', ['completed', 'inactive'])
            ->with('user')
            ->get();

        $gradeLevel = $section->gradeLevel;
        $gradeLevels = collect([$gradeLevel]);

        $selectedGradeLevel = null;
        $filteredSubjects = collect();
        $selectedSubject = null;
        $grades = collect();
        $existingGrades = collect();
        $gradeWeights = null; // Store custom weights

        $selectedGradeLevel = $gradeLevel;
        
        // Filter subjects based on teacher role
        $teacher = auth()->user()->teacher;
        $isAdviser = $section->teacher_id === $teacher->id;
        
        if ($isAdviser) {
            $filteredSubjects = $gradeLevel->subjects ?? collect();
        } else {
            // Non-adviser: only show assigned subjects
            $assignedSubjectIds = DB::table('teacher_subject')
                ->where('teacher_id', $teacher->id)
                ->where('section_id', $section->id)
                ->pluck('subject_id');
            
            $filteredSubjects = Subject::whereIn('id', $assignedSubjectIds)->get();
        }

        if ($request->filled('subject')) {
            $selectedSubject = Subject::find($request->subject);
            
            if ($selectedSubject) {
                $grades = Grade::where('section_id', $section->id)
                    ->where('subject_id', $selectedSubject->id)
                    ->where('quarter', $requestedQuarter)
                    ->get()
                    ->keyBy(function ($item) {
                        return $item->student_id . '_' . $item->component_type;
                    });
                
                foreach ($grades as $key => $grade) {
                    $existingGrades[$key] = [
                        'scores' => json_decode($grade->scores, true) ?? [],
                        'titles' => json_decode($grade->titles, true) ?? [],
                        'total_items' => json_decode($grade->total_items, true) ?? [],
                        'total_score' => $grade->total_score,
                        'percentage_score' => $grade->percentage_score,
                    ];
                }
                
                $wwGrade = $grades->firstWhere('component_type', 'written_work');
                $ptGrade = $grades->firstWhere('component_type', 'performance_task');
                $qeGrade = $grades->firstWhere('component_type', 'quarterly_exam');
                
                $existingGrades['ww_titles'] = $wwGrade ? (json_decode($wwGrade->titles, true) ?? []) : [];
                $existingGrades['pt_titles'] = $ptGrade ? (json_decode($ptGrade->titles, true) ?? []) : [];
                $existingGrades['ww_total_items'] = $wwGrade ? (json_decode($wwGrade->total_items, true) ?? []) : [];
                $existingGrades['pt_total_items'] = $ptGrade ? (json_decode($ptGrade->total_items, true) ?? []) : [];
                $existingGrades['qe_total_items'] = $qeGrade ? ($qeGrade->total_items ?? 100) : 100;

                // Load saved custom weights if they exist
                if ($activeSchoolYear) {
                    $currentQuarter = $requestedQuarter;
                    
                    // First, try to get weights for the current quarter
                    $gradeWeights = GradeWeight::where([
                        'section_id' => $section->id,
                        'subject_id' => $selectedSubject->id,
                        'school_year_id' => $activeSchoolYear->id,
                        'quarter' => $currentQuarter,
                    ])->first();
                    
                    // If no weights for current quarter, check if teacher has preferred weights from other quarters
                    if (!$gradeWeights) {
                        $preferredWeights = GradeWeight::where([
                            'section_id' => $section->id,
                            'subject_id' => $selectedSubject->id,
                            'school_year_id' => $activeSchoolYear->id,
                        ])->where('quarter', '!=', $currentQuarter)
                          ->orderBy('updated_at', 'desc') // Get the most recently updated
                          ->first();
                        
                        // If teacher has preferred weights from other quarters, use those
                        if ($preferredWeights) {
                            $gradeWeights = (object) [
                                'ww_weight' => $preferredWeights->ww_weight,
                                'pt_weight' => $preferredWeights->pt_weight,
                                'qe_weight' => $preferredWeights->qe_weight,
                            ];
                        }
                    }
                }
            }
        }

        // Default weights if no custom weights saved at all
        if (!$gradeWeights) {
            $gradeWeights = (object) [
                'ww_weight' => 40,
                'pt_weight' => 40,
                'qe_weight' => 20,
            ];
        }

        return view('teacher.grades.index', compact(
            'section', 
            'students', 
            'gradeLevels', 
            'selectedGradeLevel', 
            'filteredSubjects', 
            'selectedSubject',
            'grades',
            'existingGrades',
            'finalization',
            'isEditable',
            'gradeWeights',
            'quarterInfo',
            'requestedQuarter',
            'isAdviser'
        ));
    }

    public function create()
    {
        return view('teacher.grades.create');
    }

    public function store(Request $request, Section $section)
    {
        $subject = Subject::find($request->subject_id);
        if (!$this->canAccessGrades($section, $subject)) {
            abort(403, 'You are not authorized to encode grades for this subject.');
        }

        // Check if section is finalized/locked
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear) {
            $finalization = $this->finalizationService->getOrCreateFinalization($section->id, $activeSchoolYear->id);
            
            // Block if section is locked OR grades are already finalized
            if ($finalization->is_locked || $finalization->grades_finalized) {
                return back()->with('error', 'Grades have been finalized and are locked. Contact the administrator if you need to make changes.');
            }
        }

        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'quarter' => 'required|in:1,2,3,4',
            'ww_weight' => 'required|numeric|min:0|max:100',
            'pt_weight' => 'required|numeric|min:0|max:100',
            'qe_weight' => 'required|numeric|min:0|max:100',
            'ww' => 'nullable|array',
            'pt' => 'nullable|array',
            'qe' => 'nullable|array',
            'ww_titles' => 'nullable|array',
            'pt_titles' => 'nullable|array',
            'ww_total_items' => 'nullable|array',
            'pt_total_items' => 'nullable|array',
            'qe_total_items' => 'nullable|numeric|min:1',
        ]);

        $totalWeight = $request->ww_weight + $request->pt_weight + $request->qe_weight;
        if (round($totalWeight, 2) != 100) {
            return back()->with('error', 'Component weights must sum to 100%. Current: ' . $totalWeight . '%');
        }

        $subjectId = $request->subject_id;
        $quarter = $request->quarter;

        if (!$activeSchoolYear) {
            return back()->with('error', 'No active school year found.');
        }
        
        $schoolYearId = $activeSchoolYear->id;

        // Save or update the custom weights
        GradeWeight::updateOrCreate(
            [
                'section_id' => $section->id,
                'subject_id' => $subjectId,
                'school_year_id' => $schoolYearId,
                'quarter' => $quarter,
            ],
            [
                'ww_weight' => $request->ww_weight,
                'pt_weight' => $request->pt_weight,
                'qe_weight' => $request->qe_weight,
            ]
        );

        if ($request->has('ww')) {
            $wwTitles = $request->ww_titles ?? [];
            $wwTotalItems = $request->ww_total_items ?? [];
            foreach ($request->ww as $studentId => $scores) {
                $this->saveGradeComponents($section->id, $studentId, $subjectId, $quarter, 'written_work', $scores, $wwTitles, $wwTotalItems, $schoolYearId);
            }
        }

        if ($request->has('pt')) {
            $ptTitles = $request->pt_titles ?? [];
            $ptTotalItems = $request->pt_total_items ?? [];
            foreach ($request->pt as $studentId => $scores) {
                $this->saveGradeComponents($section->id, $studentId, $subjectId, $quarter, 'performance_task', $scores, $ptTitles, $ptTotalItems, $schoolYearId);
            }
        }

        if ($request->has('qe')) {
            $qeTotalItems = $request->qe_total_items ?? 100;
            foreach ($request->qe as $studentId => $score) {
                if ($score !== null && $score !== '') {
                    $this->saveQuarterlyExam($section->id, $studentId, $subjectId, $quarter, $score, $qeTotalItems, $schoolYearId);
                }
            }
        }

        $students = $section->students()
            ->whereNotIn('status', ['completed', 'inactive'])
            ->pluck('id');
            
        foreach ($students as $studentId) {
            $this->calculateAndSaveFinalGrade(
                $section->id, 
                $studentId, 
                $subjectId, 
                $quarter,
                $request->ww_weight,
                $request->pt_weight,
                $request->qe_weight,
                $schoolYearId
            );
        }

        return redirect()->route('teacher.sections.grades', [
            'section' => $section,
            'subject' => $subjectId,
            'quarter' => $quarter,
        ])->with('success', 'Grades saved and calculated successfully.');
    }

    /**
     * Finalize grades for this section
     */
    public function finalizeGrades(Request $request, Section $section)
    {
        if ($section->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if (!$activeSchoolYear) {
            $message = 'No active school year found.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message]);
            }
            return redirect()->route('teacher.sections.grades', $section)
                ->with('error', $message);
        }

        $result = $this->finalizationService->finalizeGrades(
            $section->id,
            $activeSchoolYear->id,
            auth()->id()
        );

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return redirect()->route('teacher.sections.grades', $section)
                ->with('success', $result['message']);
        }

        return redirect()->route('teacher.sections.grades', $section)
            ->with('error', $result['message'])
            ->with('validation_errors', $result['errors'] ?? []);
    }

    /**
     * Save grade components (Written Work or Performance Tasks)
     */
    private function saveGradeComponents($sectionId, $studentId, $subjectId, $quarter, $componentType, $scores, $titles = [], $totalItems = [], $schoolYearId = null)
    {
        $validScores = array_filter($scores, function($score) {
            return $score !== null && $score !== '';
        });

        if (empty($validScores)) {
            return;
        }

        $totalScore = array_sum($validScores);
        $count = count($validScores);

        $totalPossible = 0;
        foreach ($validScores as $index => $score) {
            $itemCount = isset($totalItems[$index]) && $totalItems[$index] > 0 ? $totalItems[$index] : 100;
            $totalPossible += $itemCount;
        }
        
        $percentageScore = $totalPossible > 0 ? ($totalScore / $totalPossible) * 100 : 0;

        Grade::updateOrCreate(
            [
                'section_id' => $sectionId,
                'student_id' => $studentId,
                'subject_id' => $subjectId,
                'quarter' => $quarter,
                'component_type' => $componentType,
                'school_year_id' => $schoolYearId,
            ],
            [
                'school_year_id' => $schoolYearId,
                'scores' => json_encode(array_values($validScores)),
                'titles' => json_encode(array_values($titles)),
                'total_items' => json_encode(array_values($totalItems)),
                'total_score' => $totalScore,
                'percentage_score' => round($percentageScore, 2),
            ]
        );
    }

    /**
     * Save Quarterly Exam grade
     */
    private function saveQuarterlyExam($sectionId, $studentId, $subjectId, $quarter, $score, $totalItems = 100, $schoolYearId = null)
    {
        $percentageScore = ($score / $totalItems) * 100;

        Grade::updateOrCreate(
            [
                'section_id' => $sectionId,
                'student_id' => $studentId,
                'subject_id' => $subjectId,
                'quarter' => $quarter,
                'component_type' => 'quarterly_exam',
                'school_year_id' => $schoolYearId,
            ],
            [
                'school_year_id' => $schoolYearId,
                'total_score' => $score,
                'total_items' => $totalItems,
                'percentage_score' => round($percentageScore, 2),
            ]
        );
    }

    /**
     * Calculate weighted scores and final grade with transmutation
     */
    private function calculateAndSaveFinalGrade($sectionId, $studentId, $subjectId, $quarter, $wwWeight, $ptWeight, $qeWeight, $schoolYearId = null)
    {
        $wwGrade = Grade::where([
            'section_id' => $sectionId,
            'student_id' => $studentId,
            'subject_id' => $subjectId,
            'quarter' => $quarter,
            'component_type' => 'written_work',
            'school_year_id' => $schoolYearId,
        ])->first();

        $ptGrade = Grade::where([
            'section_id' => $sectionId,
            'student_id' => $studentId,
            'subject_id' => $subjectId,
            'quarter' => $quarter,
            'component_type' => 'performance_task',
            'school_year_id' => $schoolYearId,
        ])->first();

        $qeGrade = Grade::where([
            'section_id' => $sectionId,
            'student_id' => $studentId,
            'subject_id' => $subjectId,
            'quarter' => $quarter,
            'component_type' => 'quarterly_exam',
            'school_year_id' => $schoolYearId,
        ])->first();

        $wwWeighted = $wwGrade ? ($wwGrade->percentage_score * ($wwWeight / 100)) : 0;
        $ptWeighted = $ptGrade ? ($ptGrade->percentage_score * ($ptWeight / 100)) : 0;
        $qeWeighted = $qeGrade ? ($qeGrade->percentage_score * ($qeWeight / 100)) : 0;

        $initialGrade = $wwWeighted + $ptWeighted + $qeWeighted;
        $transmutedGrade = $this->transmuteGrade($initialGrade);

        Grade::updateOrCreate(
            [
                'section_id' => $sectionId,
                'student_id' => $studentId,
                'subject_id' => $subjectId,
                'quarter' => $quarter,
                'component_type' => 'final_grade',
                'school_year_id' => $schoolYearId,
            ],
            [
                'school_year_id' => $schoolYearId,
                'ww_weighted' => round($wwWeighted, 2),
                'pt_weighted' => round($ptWeighted, 2),
                'qe_weighted' => round($qeWeighted, 2),
                'initial_grade' => round($initialGrade, 2),
                'final_grade' => $transmutedGrade,
                'remarks' => $this->getRemarks($transmutedGrade),
            ]
        );
    }

    /**
     * DepEd Transmutation Table
     */
    private function transmuteGrade($initialGrade)
    {
        $transmutationTable = [
            ['min' => 100.00, 'max' => 100.00, 'grade' => 100],
            ['min' => 98.40, 'max' => 99.99, 'grade' => 99],
            ['min' => 96.80, 'max' => 98.39, 'grade' => 98],
            ['min' => 95.20, 'max' => 96.79, 'grade' => 97],
            ['min' => 93.60, 'max' => 95.19, 'grade' => 96],
            ['min' => 92.00, 'max' => 93.59, 'grade' => 95],
            ['min' => 90.40, 'max' => 91.99, 'grade' => 94],
            ['min' => 88.80, 'max' => 90.39, 'grade' => 93],
            ['min' => 87.20, 'max' => 88.79, 'grade' => 92],
            ['min' => 85.60, 'max' => 87.19, 'grade' => 91],
            ['min' => 84.00, 'max' => 85.59, 'grade' => 90],
            ['min' => 82.40, 'max' => 83.99, 'grade' => 89],
            ['min' => 80.80, 'max' => 82.39, 'grade' => 88],
            ['min' => 79.20, 'max' => 80.79, 'grade' => 87],
            ['min' => 77.60, 'max' => 79.19, 'grade' => 86],
            ['min' => 76.00, 'max' => 77.59, 'grade' => 85],
            ['min' => 74.40, 'max' => 75.99, 'grade' => 84],
            ['min' => 72.80, 'max' => 74.39, 'grade' => 83],
            ['min' => 71.20, 'max' => 72.79, 'grade' => 82],
            ['min' => 69.60, 'max' => 71.19, 'grade' => 81],
            ['min' => 68.00, 'max' => 69.59, 'grade' => 80],
            ['min' => 66.40, 'max' => 67.99, 'grade' => 79],
            ['min' => 64.80, 'max' => 66.39, 'grade' => 78],
            ['min' => 63.20, 'max' => 64.79, 'grade' => 77],
            ['min' => 61.60, 'max' => 63.19, 'grade' => 76],
            ['min' => 60.00, 'max' => 61.59, 'grade' => 75],
            ['min' => 56.00, 'max' => 59.99, 'grade' => 74],
            ['min' => 52.00, 'max' => 55.99, 'grade' => 73],
            ['min' => 48.00, 'max' => 51.99, 'grade' => 72],
            ['min' => 44.00, 'max' => 47.99, 'grade' => 71],
            ['min' => 40.00, 'max' => 43.99, 'grade' => 70],
            ['min' => 36.00, 'max' => 39.99, 'grade' => 69],
            ['min' => 32.00, 'max' => 35.99, 'grade' => 68],
            ['min' => 28.00, 'max' => 31.99, 'grade' => 67],
            ['min' => 24.00, 'max' => 27.99, 'grade' => 66],
            ['min' => 20.00, 'max' => 23.99, 'grade' => 65],
            ['min' => 16.00, 'max' => 19.99, 'grade' => 64],
            ['min' => 12.00, 'max' => 15.99, 'grade' => 63],
            ['min' => 8.00, 'max' => 11.99, 'grade' => 62],
            ['min' => 4.00, 'max' => 7.99, 'grade' => 61],
            ['min' => 0.00, 'max' => 3.99, 'grade' => 60],
        ];

        foreach ($transmutationTable as $range) {
            if ($initialGrade >= $range['min'] && $initialGrade <= $range['max']) {
                return $range['grade'];
            }
        }

        return 60;
    }

    /**
     * Get remarks based on transmuted grade
     */
    private function getRemarks($grade)
    {
        if ($grade >= 75) {
            return 'Passed';
        } elseif ($grade >= 70) {
            return 'Almost Passed';
        } else {
            return 'Failed';
        }
    }

    /**
     * Quick Grade Entry - Spreadsheet view for all subjects
     */
    public function quickEntry(Section $section)
    {
        if (!$this->canAccessGrades($section)) {
            abort(403, 'You are not authorized to access grades for this section.');
        }

        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        
        // Check finalization status
        $finalization = null;
        $isEditable = true;
        if ($activeSchoolYear) {
            $finalization = $this->finalizationService->getOrCreateFinalization($section->id, $activeSchoolYear->id);
            $isEditable = !$finalization->is_locked && !$finalization->grades_finalized;
        }

        $students = $section->students()
            ->whereNotIn('status', ['completed', 'inactive'])
            ->with('user')
            ->orderBy('last_name')
            ->get();

        // Filter subjects based on teacher role
        $teacher = auth()->user()->teacher;
        $isAdviser = $section->teacher_id === $teacher->id;
        
        if ($isAdviser) {
            $subjects = $section->gradeLevel->subjects ?? collect();
        } else {
            $assignedSubjectIds = DB::table('teacher_subject')
                ->where('teacher_id', $teacher->id)
                ->where('section_id', $section->id)
                ->pluck('subject_id');
            
            $subjects = Subject::whereIn('id', $assignedSubjectIds)->get();
        }

        $currentQuarter = Setting::get('current_quarter', 1);

        $grades = Grade::where('section_id', $section->id)
            ->where('school_year_id', $activeSchoolYear?->id)
            ->where('quarter', $currentQuarter)
            ->where('component_type', 'final_grade')
            ->get()
            ->keyBy(function ($item) {
                return $item->student_id . '_' . $item->subject_id;
            });

        return view('teacher.grades.quick-entry', compact(
            'section',
            'students',
            'subjects',
            'grades',
            'currentQuarter',
            'finalization',
            'isEditable'
        ));
    }

    /**
     * Save quick grades
     */
    public function saveQuickGrades(Request $request, Section $section)
    {
        if (!$this->canAccessGrades($section)) {
            abort(403, 'You are not authorized to save grades for this section.');
        }
        
        $teacher = auth()->user()->teacher;
        $isAdviser = $section->teacher_id === $teacher->id;
        
        // Non-advisers can only save grades for their assigned subjects
        $allowedSubjectIds = null;
        if (!$isAdviser) {
            $allowedSubjectIds = DB::table('teacher_subject')
                ->where('teacher_id', $teacher->id)
                ->where('section_id', $section->id)
                ->pluck('subject_id')
                ->toArray();
        }

        // Check if section is finalized/locked
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear) {
            $finalization = $this->finalizationService->getOrCreateFinalization($section->id, $activeSchoolYear->id);
            
            if ($finalization->is_locked || $finalization->grades_finalized) {
                return back()->with('error', 'Grades have been finalized and are locked. Contact the administrator if you need to make changes.');
            }
        }

        $request->validate([
            'grades' => 'required|array',
            'quarter' => 'required|in:1,2,3,4',
        ]);

        if (!$activeSchoolYear) {
            return back()->with('error', 'No active school year found.');
        }

        DB::beginTransaction();
        try {
            foreach ($request->grades as $studentId => $subjects) {
                foreach ($subjects as $subjectId => $grade) {
                    // Non-advisers can only save grades for their assigned subjects
                    if (!$isAdviser && !in_array((int)$subjectId, $allowedSubjectIds)) {
                        continue;
                    }
                    if ($grade !== null && $grade !== '') {
                        Grade::updateOrCreate(
                            [
                                'section_id' => $section->id,
                                'student_id' => $studentId,
                                'subject_id' => $subjectId,
                                'quarter' => $request->quarter,
                                'component_type' => 'final_grade',
                                'school_year_id' => $activeSchoolYear->id,
                            ],
                            [
                                'school_year_id' => $activeSchoolYear->id,
                                'final_grade' => $grade,
                                'remarks' => $this->getRemarks($grade),
                            ]
                        );
                    }
                }
            }
            DB::commit();
            return back()->with('success', 'Grades saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to save grades: ' . $e->getMessage());
        }
    }
}
