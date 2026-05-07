<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Student;
use App\Models\CoreValue;
use App\Models\SchoolYear;
use App\Services\FinalizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CoreValueController extends Controller
{
    protected $finalizationService;

    public function __construct(FinalizationService $finalizationService)
    {
        $this->finalizationService = $finalizationService;
    }

    /**
     * Display the core values rating page for a section
     */
    public function index(Section $section, Request $request)
    {
        if ($section->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $activeSchoolYear = SchoolYear::where('is_active', true)->first();

        // Security: section must belong to active school year
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        // Auto-detect current quarter from admin-set dates, fallback to request or Q1
        $currentQuarterFromDates = $activeSchoolYear?->currentQuarter();
        $defaultQuarter = $currentQuarterFromDates?->quarter_number ?? 1;
        $currentQuarter = (int) $request->get('quarter', $defaultQuarter);
        
        // Check finalization status
        $finalization = null;
        $isEditable = true;
        if ($activeSchoolYear) {
            $finalization = $this->finalizationService->getOrCreateFinalization($section->id, $activeSchoolYear->id);
            $isEditable = !$finalization->is_locked && !$finalization->core_values_finalized;
        }
        
        $students = $section->students()
            ->whereNotIn('status', ['completed', 'inactive'])
            ->with([
                'user:id,first_name,last_name',
                'coreValues' => function($query) use ($currentQuarter, $activeSchoolYear) {
                    $query->where('quarter', $currentQuarter)
                          ->where('school_year_id', $activeSchoolYear?->id);
                }
            ])
            ->get()
            ->sortBy('user.last_name');

        // Get all core values for all quarters to show completion status
        $allCoreValues = [];
        if ($activeSchoolYear) {
            foreach ($students as $student) {
                $allCoreValues[$student->id] = CoreValue::where('student_id', $student->id)
                    ->where('school_year_id', $activeSchoolYear->id)
                    ->get()
                    ->groupBy('quarter');
            }
        }

        return view('teacher.core-values.index', compact(
            'section',
            'students',
            'currentQuarter',
            'activeSchoolYear',
            'allCoreValues',
            'finalization',
            'isEditable'
        ));
    }

    /**
     * Store core value ratings for a student
     */
    public function store(Section $section, Request $request)
    {
        // Check if section is finalized/locked
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear) {
            $finalization = $this->finalizationService->getOrCreateFinalization($section->id, $activeSchoolYear->id);
            
            if ($finalization->is_locked) {
                return response()->json([
                    'success' => false,
                    'message' => 'This section has been finalized and is locked. Contact the administrator if you need to make changes.'
                ], 403);
            }
        }

        Log::info('Core Values Store Request', [
            'section_id' => $section->id,
            'teacher_id' => Auth::id(),
            'all_input' => $request->all()
        ]);

        try {
            if (!$activeSchoolYear) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active school year found. Please contact administrator.'
                ], 422);
            }

            $validated = $request->validate([
                'student_id' => 'required|exists:students,id',
                'quarter' => 'required|integer|between:1,4',
                'ratings' => 'required|array|min:1',
                'ratings.*.core_value' => 'required|in:Maka-Diyos,Makatao,Maka-Kalikasan,Maka-bansa',
                'ratings.*.behavior_statement' => 'required|string|max:500',
                'ratings.*.statement_key' => 'required|string|max:50',
                'ratings.*.rating' => 'required|in:AO,SO,RO,NO',
                'ratings.*.remarks' => 'nullable|string|max:1000',
            ]);

            DB::beginTransaction();

            $studentId = $validated['student_id'];
            $quarter = $validated['quarter'];
            $teacherId = Auth::id();

            foreach ($validated['ratings'] as $index => $rating) {
                Log::info("Processing rating {$index}", $rating);

                CoreValue::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'core_value' => $rating['core_value'],
                        'statement_key' => $rating['statement_key'],
                        'quarter' => $quarter,
                        'school_year_id' => $activeSchoolYear->id,
                    ],
                    [
                        'behavior_statement' => $rating['behavior_statement'],
                        'rating' => $rating['rating'],
                        'remarks' => $rating['remarks'] ?? null,
                        'recorded_by' => $teacherId,
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Core values saved successfully!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation failed', ['errors' => $e->errors()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving core values', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk save core values for multiple students
     */
    public function bulkStore(Section $section, Request $request)
    {
        // Check if section is finalized/locked
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear) {
            $finalization = $this->finalizationService->getOrCreateFinalization($section->id, $activeSchoolYear->id);
            
            if ($finalization->is_locked || $finalization->core_values_finalized) {
                return response()->json([
                    'success' => false,
                    'message' => 'Core values have been finalized and are locked.'
                ], 403);
            }
        }

        try {
            if (!$activeSchoolYear) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active school year found.'
                ], 422);
            }

            $validated = $request->validate([
                'quarter' => 'required|integer|between:1,4',
                'ratings_by_student' => 'required|array',
                'ratings_by_student.*' => 'array',
                'ratings_by_student.*.*.core_value' => 'required|in:Maka-Diyos,Makatao,Maka-Kalikasan,Maka-bansa',
                'ratings_by_student.*.*.statement_key' => 'required|string|max:50',
                'ratings_by_student.*.*.rating' => 'required|in:AO,SO,RO,NO',
            ]);

            DB::beginTransaction();

            $quarter = $validated['quarter'];
            $teacherId = Auth::id();

            foreach ($validated['ratings_by_student'] as $studentId => $ratings) {
                foreach ($ratings as $rating) {
                    CoreValue::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'core_value' => $rating['core_value'],
                            'statement_key' => $rating['statement_key'],
                            'quarter' => $quarter,
                            'school_year_id' => $activeSchoolYear->id,
                        ],
                        [
                            'behavior_statement' => $rating['behavior_statement'] ?? '',
                            'rating' => $rating['rating'],
                            'remarks' => $rating['remarks'] ?? null,
                            'recorded_by' => $teacherId,
                        ]
                    );
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'All core values saved successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error bulk saving core values', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Finalize core values for this section
     */
    public function finalizeCoreValues(Request $request, Section $section)
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
            return redirect()->route('teacher.sections.core-values.index', $section)
                ->with('error', $message);
        }

        $result = $this->finalizationService->finalizeCoreValues(
            $section->id,
            $activeSchoolYear->id,
            auth()->id()
        );

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return redirect()->route('teacher.sections.core-values.index', $section)
                ->with('success', $result['message']);
        }

        return redirect()->route('teacher.sections.core-values.index', $section)
            ->with('error', $result['message'])
            ->with('validation_errors', $result['errors'] ?? []);
    }

    /**
     * Get current quarter based on date
     */
    private function getCurrentQuarter(): int
    {
        $month = now()->month;
        
        return match(true) {
            $month >= 6 && $month <= 8 => 1,
            $month >= 9 && $month <= 11 => 2,
            $month == 12 || $month <= 2 => 3,
            default => 4,
        };
    }

    /**
     * Get completion status for all quarters
     */
    public function getCompletionStatus(Section $section)
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if (!$activeSchoolYear) {
            return response()->json(['error' => 'No active school year'], 400);
        }

        $students = $section->students()
            ->whereNotIn('status', ['completed', 'inactive'])
            ->pluck('id');

        $coreValues = ['Maka-Diyos', 'Makatao', 'Maka-Kalikasan', 'Maka-bansa'];
        $completion = [];

        foreach (range(1, 4) as $quarter) {
            $totalExpected = $students->count() * count($coreValues);
            $totalRecorded = CoreValue::whereIn('student_id', $students)
                ->where('school_year_id', $activeSchoolYear->id)
                ->where('quarter', $quarter)
                ->distinct('student_id', 'core_value')
                ->count();

            $completion["q{$quarter}"] = [
                'expected' => $totalExpected,
                'recorded' => $totalRecorded,
                'percentage' => $totalExpected > 0 ? round(($totalRecorded / $totalExpected) * 100) : 0,
                'complete' => $totalRecorded >= $totalExpected,
            ];
        }

        return response()->json($completion);
    }
}
