<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\SchoolYear;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function index(Section $section)
    {
        if ($section->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        $assignments = Assignment::where('section_id', $section->id)
            ->with(['subject', 'submissions'])
            ->orderBy('due_date', 'desc')
            ->paginate(10);

        return view('teacher.assignments.index', compact('section', 'assignments'));
    }

    public function create(Section $section)
    {
        if ($section->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        $subjects = $section->gradeLevel->subjects ?? collect();
        
        return view('teacher.assignments.create', compact('section', 'subjects'));
    }

    public function store(Request $request, Section $section)
    {
        if ($section->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'type' => 'required|in:homework,quiz,project,exam,activity',
            'total_points' => 'required|integer|min:1|max:1000',
            'due_date' => 'required|date|after_or_equal:today',
            'due_time' => 'nullable|date_format:H:i',
            'instructions' => 'nullable|string',
            'allow_late_submission' => 'boolean',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $attachments[] = $file->store('assignments/' . $section->id, 'public');
            }
        }

        Assignment::create([
            'section_id' => $section->id,
            'subject_id' => $request->subject_id,
            'teacher_id' => auth()->user()->teacher->id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'total_points' => $request->total_points,
            'due_date' => $request->due_date,
            'due_time' => $request->due_time,
            'instructions' => $request->instructions,
            'allow_late_submission' => $request->boolean('allow_late_submission'),
            'attachments' => $attachments,
            'status' => 'published',
        ]);

        return redirect()->route('teacher.assignments.index', $section)
            ->with('success', 'Assignment created successfully.');
    }

    public function show(Section $section, Assignment $assignment)
    {
        if ($section->teacher_id !== auth()->user()->teacher->id || $assignment->section_id !== $section->id) {
            abort(403);
        }

        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        $assignment->load(['submissions.student.user', 'subject']);
        
        $students = $section->students()
            ->whereNotIn('status', ['completed', 'inactive'])
            ->with(['user'])
            ->get();

        return view('teacher.assignments.show', compact('section', 'assignment', 'students'));
    }

    public function grade(Request $request, Section $section, Assignment $assignment)
    {
        if ($section->teacher_id !== auth()->user()->teacher->id || $assignment->section_id !== $section->id) {
            abort(403);
        }

        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        $request->validate([
            'submission_id' => 'required|exists:assignment_submissions,id',
            'score' => 'required|integer|min:0|max:' . $assignment->total_points,
            'feedback' => 'nullable|string',
        ]);

        $submission = AssignmentSubmission::findOrFail($request->submission_id);
        $submission->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
            'graded_at' => now(),
            'graded_by' => auth()->id(),
            'status' => 'graded',
        ]);

        return back()->with('success', 'Submission graded successfully.');
    }

    public function destroy(Section $section, Assignment $assignment)
    {
        if ($section->teacher_id !== auth()->user()->teacher->id || $assignment->section_id !== $section->id) {
            abort(403);
        }

        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        // Delete attachments
        if ($assignment->attachments) {
            foreach ($assignment->attachments as $attachment) {
                Storage::disk('public')->delete($attachment);
            }
        }

        $assignment->delete();

        return redirect()->route('teacher.assignments.index', $section)
            ->with('success', 'Assignment deleted successfully.');
    }
}
