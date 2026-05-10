<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    // Display list of teachers
    public function index()
    {
        $teachers = Teacher::with(['user', 'subjects', 'sections'])
            ->whereHas('user', function ($q) {
                $q->whereHas('role', function ($r) {
                    $r->where('name', 'Teacher');
                });
            })
            ->where(function ($q) {
                $q->whereNotNull('first_name')->where('first_name', '!=', '')
                  ->orWhereNotNull('last_name')->where('last_name', '!=', '');
            })
            ->latest()
            ->paginate(20);

        // Pre-compute aggregate stats so the view doesn't need to load all teachers
        $teacherStats = [
            'total' => Teacher::count(),
            'active' => Teacher::where('status', 'active')->count(),
            'on_leave' => Teacher::where('status', 'on_leave')->count(),
            'inactive' => Teacher::where('status', 'inactive')->count(),
            'subjects' => \App\Models\Subject::count(),
            'sections' => \App\Models\Section::count(),
        ];

        return view('admin.teachers.index', compact('teachers', 'teacherStats'));
    }

    // Show form to create a teacher
    public function create()
    {
        return view('admin.teachers.create');
    }

    // Store a new teacher
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Personal Info
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'date_of_birth' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Male,Female',
            'civil_status' => 'nullable|string|max:50',
            'nationality' => 'nullable|string|max:50',
            'religion' => 'nullable|string|max:50',
            'blood_type' => 'nullable|string|max:5',

            // Contact Info
            'email' => 'required|email',
            'mobile_number' => 'nullable|string|max:15',
            'telephone_number' => 'nullable|string|max:15',
            'street_address' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'city_municipality' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'region' => 'nullable|string|max:50',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:50',
            'emergency_contact_number' => 'nullable|string|max:15',
            'emergency_contact_address' => 'nullable|string|max:255',

            // Employment
            'employment_status' => 'nullable|in:Permanent,Probationary,Contractual,Substitute,Part-time',
            'date_hired' => 'nullable|date',
            'date_regularized' => 'nullable|date',
            'current_status' => 'nullable|in:Active,On Leave,Inactive',
            'teaching_level' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'is_class_adviser' => 'boolean',
            'advisory_class' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',

            // Education
            'highest_education' => 'nullable|string|max:255',
            'degree_program' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'minor' => 'nullable|string|max:255',
            'school_graduated' => 'nullable|string|max:255',
            'year_graduated' => 'nullable|integer|min:1900|max:' . date('Y'),
            'honors_received' => 'nullable|string|max:255',

            // Licenses / Certificates
            'prc_license_number' => 'nullable|string|max:50',
            'prc_license_validity' => 'nullable|date',
            'let_passer' => 'nullable|boolean',
            'board_rating' => 'nullable|string|max:50',
            'tesda_nc' => 'nullable|string|max:50',
            'tesda_sector' => 'nullable|string|max:50',

            // Experience
            'years_of_experience' => 'nullable|integer|min:0',
            'previous_school' => 'nullable|string|max:255',
            'previous_position' => 'nullable|string|max:255',

            // IDs
            'gsis_id' => 'nullable|string|max:20',
            'pagibig_id' => 'nullable|string|max:20',
            'philhealth_id' => 'nullable|string|max:20',
            'sss_id' => 'nullable|string|max:20',
            'tin_id' => 'nullable|string|max:20',
            'pagibig_rtn' => 'nullable|string|max:20',

            // Salary / Bank
            'salary_grade' => 'nullable|integer',
            'step_increment' => 'nullable|integer',
            'basic_salary' => 'nullable|numeric',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:50',

            // Family
            'spouse_name' => 'nullable|string|max:255',
            'spouse_occupation' => 'nullable|string|max:255',
            'spouse_contact' => 'nullable|string|max:15',
            'number_of_children' => 'nullable|integer|min:0',
            'father_name' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',

            // Health
            'medical_conditions' => 'nullable|string|max:255',
            'medications' => 'nullable|string|max:255',
            'covid_vaccinated' => 'nullable|boolean',
            'covid_vaccine_type' => 'nullable|string|max:50',
            'covid_vaccine_date' => 'nullable|date',

            // Files
            'photo_path' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'resume_path' => 'nullable|file|mimes:pdf|max:5120',
            'prc_id_path' => 'nullable|file|mimes:pdf|max:5120',
            'transcript_path' => 'nullable|file|mimes:pdf|max:5120',
            'clearance_path' => 'nullable|file|mimes:pdf|max:5120',
            'medical_cert_path' => 'nullable|file|mimes:pdf|max:5120',
            'nbi_clearance_path' => 'nullable|file|mimes:pdf|max:5120',
            'service_record_path' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        DB::beginTransaction();

        try {
            // Create linked user
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'] ?? null,
                'username' => $request->username ?? strtolower($validated['first_name'] . $validated['last_name']),
                'password' => Hash::make($request->password ?? '12345678'),
                'role_id' => Role::where('name', 'Teacher')->value('id'), // Teacher role
                'is_active' => true,
            ]);

            // Handle file uploads
            $fileFields = [
                'photo_path', 'resume_path', 'prc_id_path', 'transcript_path', 
                'clearance_path', 'medical_cert_path', 'nbi_clearance_path', 'service_record_path'
            ];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $validated[$field] = $request->file($field)->store('teachers/' . $field, 'public');
                }
            }

            // Assign user_id and default status
            $validated['user_id'] = $user->id;
            $validated['status'] = $validated['status'] ?? 'active';
            $validated['current_status'] = $validated['current_status'] ?? 'Active';

            // Create teacher
            Teacher::create($validated);

            DB::commit();

            return redirect()->route('admin.teachers.index')
                ->with('success', 'Teacher created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to create teacher: ' . $e->getMessage())->withInput();
        }
    }

    // Show teacher
    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'sections.gradeLevel', 'subjects']);
        
        $schoolSettings = Setting::where('group', 'school')->get()->keyBy('key')->map->value;
        $schoolId = $schoolSettings['deped_school_id'] ?? '';
        $schoolName = $schoolSettings['school_name'] ?? '';
        $schoolDivision = $schoolSettings['school_division'] ?? '';
        $schoolRegion = $schoolSettings['school_region'] ?? '';
        $schoolHead = $schoolSettings['school_head'] ?? Setting::where('key', 'school_head')->value('value') ?? '';
        
        $activityLogs = \App\Models\ActivityLog::forEntity('Teacher', $teacher->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.teachers.show', compact('teacher', 'schoolId', 'schoolName', 'schoolDivision', 'schoolRegion', 'schoolHead', 'activityLogs'));
    }

    // Show edit form
    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    // Update teacher
 public function update(Request $request, Teacher $teacher)
{
    $validated = $request->validate([
        // Personal Info
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'suffix' => 'nullable|string|max:10',
        'date_of_birth' => 'nullable|date',
        'place_of_birth' => 'nullable|string|max:255',
        'gender' => 'nullable|in:Male,Female',
        'civil_status' => 'nullable|string|max:50',
        'nationality' => 'nullable|string|max:50',
        'religion' => 'nullable|string|max:50',
        'blood_type' => 'nullable|string|max:5',

        // Contact Info
        'email' => 'required|email',
        'mobile_number' => 'nullable|string|max:15',
        'telephone_number' => 'nullable|string|max:15',
        'street_address' => 'nullable|string|max:255',
        'barangay' => 'nullable|string|max:255',
        'city_municipality' => 'nullable|string|max:255',
        'province' => 'nullable|string|max:255',
        'zip_code' => 'nullable|string|max:10',
        'region' => 'nullable|string|max:50',
        'emergency_contact_name' => 'nullable|string|max:255',
        'emergency_contact_relationship' => 'nullable|string|max:50',
        'emergency_contact_number' => 'nullable|string|max:15',
        'emergency_contact_address' => 'nullable|string|max:255',

        // Employment
        'employment_status' => 'nullable|in:Permanent,Probationary,Contractual,Substitute,Part-time',
        'date_hired' => 'nullable|date',
        'date_regularized' => 'nullable|date',
        'status' => 'nullable|in:active,on_leave,inactive', // Form field
        'current_status' => 'nullable|in:Active,On Leave,Inactive', // Database field (backup)
        'teaching_level' => 'nullable|string|max:50',
        'position' => 'nullable|string|max:255',
        'designation' => 'nullable|string|max:255',
        'is_class_adviser' => 'boolean',
        'advisory_class' => 'nullable|string|max:255',
        'department' => 'nullable|string|max:255',

        // Education
        'highest_education' => 'nullable|string|max:255',
        'degree_program' => 'nullable|string|max:255',
        'major' => 'nullable|string|max:255',
        'minor' => 'nullable|string|max:255',
        'school_graduated' => 'nullable|string|max:255',
        'year_graduated' => 'nullable|integer|min:1900|max:' . date('Y'),
        'honors_received' => 'nullable|string|max:255',

        // Licenses / Certificates
        'prc_license_number' => 'nullable|string|max:50',
        'prc_license_validity' => 'nullable|date',
        'let_passer' => 'nullable|boolean',
        'board_rating' => 'nullable|string|max:50',
        'tesda_nc' => 'nullable|string|max:50',
        'tesda_sector' => 'nullable|string|max:50',

        // Experience
        'years_of_experience' => 'nullable|integer|min:0',
        'previous_school' => 'nullable|string|max:255',
        'previous_position' => 'nullable|string|max:255',

        // IDs
        'gsis_id' => 'nullable|string|max:20',
        'pagibig_id' => 'nullable|string|max:20',
        'philhealth_id' => 'nullable|string|max:20',
        'sss_id' => 'nullable|string|max:20',
        'tin_id' => 'nullable|string|max:20',
        'pagibig_rtn' => 'nullable|string|max:20',

        // Salary / Bank
        'salary_grade' => 'nullable|integer',
        'step_increment' => 'nullable|integer',
        'basic_salary' => 'nullable|numeric',
        'bank_account_number' => 'nullable|string|max:50',
        'bank_name' => 'nullable|string|max:50',

        // Family
        'spouse_name' => 'nullable|string|max:255',
        'spouse_occupation' => 'nullable|string|max:255',
        'spouse_contact' => 'nullable|string|max:15',
        'number_of_children' => 'nullable|integer|min:0',
        'father_name' => 'nullable|string|max:255',
        'father_occupation' => 'nullable|string|max:255',
        'mother_name' => 'nullable|string|max:255',
        'mother_occupation' => 'nullable|string|max:255',

        // Health
        'medical_conditions' => 'nullable|string|max:255',
        'medications' => 'nullable|string|max:255',
        'covid_vaccinated' => 'nullable|boolean',
        'covid_vaccine_type' => 'nullable|string|max:50',
        'covid_vaccine_date' => 'nullable|date',

        // Files
        'photo_path' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'resume_path' => 'nullable|file|mimes:pdf|max:5120',
        'prc_id_path' => 'nullable|file|mimes:pdf|max:5120',
        'transcript_path' => 'nullable|file|mimes:pdf|max:5120',
        'clearance_path' => 'nullable|file|mimes:pdf|max:5120',
        'medical_cert_path' => 'nullable|file|mimes:pdf|max:5120',
        'nbi_clearance_path' => 'nullable|file|mimes:pdf|max:5120',
        'service_record_path' => 'nullable|file|mimes:pdf|max:5120',
    ]);

    DB::beginTransaction();
    try {
        // Handle file uploads
        $fileFields = [
            'photo_path', 'resume_path', 'prc_id_path', 'transcript_path',
            'clearance_path', 'medical_cert_path', 'nbi_clearance_path', 'service_record_path'
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($teacher->$field) {
                    Storage::disk('public')->delete($teacher->$field);
                }
                $validated[$field] = $request->file($field)->store('teachers/' . $field, 'public');
            }
        }

        // Map status form field to current_status database field
        if (isset($validated['status'])) {
            $statusMap = [
                'active' => 'Active',
                'on_leave' => 'On Leave',
                'inactive' => 'Inactive',
            ];
            $validated['current_status'] = $statusMap[$validated['status']] ?? 'Active';
            
            // Also set the status column if it exists in your database
            $validated['status'] = $validated['status'];
        }

        // Update teacher
        $teacher->update($validated);

        // Update linked user
        if ($teacher->user) {
            $teacher->user->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'] ?? $teacher->user->email,
            ]);
        }

        DB::commit();
        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully.');
    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Update failed: ' . $e->getMessage())->withInput();
    }
}

    // Delete teacher
    public function destroy(Teacher $teacher)
    {
        $relations = [];
        if ($teacher->sections()->exists()) {
            $relations[] = 'assigned sections';
        }
        if ($teacher->assignments()->exists()) {
            $relations[] = 'assignments';
        }
        if ($teacher->schedules()->exists()) {
            $relations[] = 'schedules';
        }
        if ($teacher->interventions()->exists()) {
            $relations[] = 'interventions';
        }
        if (\App\Models\SectionFinalization::where('teacher_id', $teacher->id)->exists()) {
            $relations[] = 'section finalizations';
        }
        if (\App\Models\TeachingProgram::where('teacher_id', $teacher->id)->exists()) {
            $relations[] = 'teaching programs';
        }
        if ($teacher->subjects()->exists()) {
            $relations[] = 'subjects';
        }

        if (!empty($relations)) {
            return redirect()->route('admin.teachers.index')
                ->with('error', 'Cannot delete teacher. Please reassign or remove related records first: ' . implode(', ', $relations) . '.');
        }

        DB::beginTransaction();
        try {
            if ($teacher->user) {
                // Remove user references from section finalizations to avoid FK constraint errors
                \App\Models\SectionFinalization::where('unlocked_by', $teacher->user->id)
                    ->update(['unlocked_by' => null]);
                \App\Models\SectionFinalization::where('finalized_by', $teacher->user->id)
                    ->update(['finalized_by' => null]);

                // Delete the user ONLY if they are a Teacher (not Admin/Registrar/Student)
                if ($teacher->user->role && $teacher->user->role->name === 'Teacher') {
                    $teacher->user->delete();
                }
            }
            $teacher->delete();
            DB::commit();

            return redirect()->route('admin.teachers.index')->with('success', 'Teacher deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.teachers.index')->with('error', 'Failed to delete teacher: ' . $e->getMessage());
        }
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:teachers,id']);
        $teachers = Teacher::whereIn('id', $request->ids)->get();
        $skipped = [];
        $deleted = 0;

        DB::beginTransaction();
        try {
            foreach ($teachers as $teacher) {
                $relations = [];
                if ($teacher->sections()->exists()) $relations[] = 'sections';
                if ($teacher->assignments()->exists()) $relations[] = 'assignments';
                if ($teacher->schedules()->exists()) $relations[] = 'schedules';
                if ($teacher->interventions()->exists()) $relations[] = 'interventions';
                if (\App\Models\SectionFinalization::where('teacher_id', $teacher->id)->exists()) $relations[] = 'finalizations';
                if (\App\Models\TeachingProgram::where('teacher_id', $teacher->id)->exists()) $relations[] = 'programs';
                if ($teacher->subjects()->exists()) $relations[] = 'subjects';

                if (!empty($relations)) {
                    $skipped[] = $teacher->full_name;
                    continue;
                }

                if ($teacher->user) {
                    \App\Models\SectionFinalization::where('unlocked_by', $teacher->user->id)->update(['unlocked_by' => null]);
                    \App\Models\SectionFinalization::where('finalized_by', $teacher->user->id)->update(['finalized_by' => null]);
                    if ($teacher->user->role && $teacher->user->role->name === 'Teacher') {
                        $teacher->user->delete();
                    }
                }
                $teacher->delete();
                $deleted++;
            }
            DB::commit();

            $msg = "Deleted {$deleted} teacher(s).";
            if (!empty($skipped)) {
                $msg .= ' Skipped (' . implode(', ', $skipped) . ') due to related records.';
                return redirect()->route('admin.teachers.index')->with('warning', $msg);
            }
            return redirect()->route('admin.teachers.index')->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.teachers.index')->with('error', 'Bulk delete failed: ' . $e->getMessage());
        }
    }

    public function exportCsv(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:teachers,id']);
        $teachers = Teacher::with(['sections.gradeLevel', 'subjects'])->whereIn('id', $request->ids)->get()->sortBy(fn($t) => [strtoupper($t->last_name ?? ''), strtoupper($t->first_name ?? '')]);

        $headers = [
            'NO.', 'Employee ID', 'DepEd ID', 'Name',
            'Gender', 'Date of Birth', 'Place of Birth', 'Civil Status', 'Nationality', 'Religion', 'Blood Type',
            'Email', 'Mobile Number', 'Telephone Number',
            'Address', 'Region',
            'Emergency Contact Name', 'Emergency Contact Relationship', 'Emergency Contact Number', 'Emergency Contact Address',
            'Employment Status', 'Date Hired', 'Date Regularized', 'Current Status', 'Teaching Level', 'Position', 'Designation', 'Department',
            'Class Adviser', 'Advisory Class', 'Salary Grade', 'Step Increment', 'Basic Salary', 'Bank Name', 'Bank Account Number',
            'Highest Education', 'Degree Program', 'Major', 'Minor', 'School Graduated', 'Year Graduated', 'Honors Received',
            'PRC License Number', 'License Validity', 'LET Passer', 'Board Rating', 'TESDA NC', 'TESDA Sector',
            'Years of Experience', 'Previous School', 'Previous Position',
            'GSIS ID', 'Pag-IBIG ID', 'PhilHealth ID', 'SSS ID', 'TIN ID', 'Pag-IBIG RTN',
            'Spouse Name', 'Spouse Occupation', 'Spouse Contact', 'Number of Children',
            'Father\'s Name', 'Father\'s Occupation', 'Mother\'s Name', 'Mother\'s Occupation',
            'Sections', 'Subjects', 'Remarks'
        ];
        $csv = "\xEF\xBB\xBF" . implode(',', array_map(fn($h) => '"' . str_replace('"', '""', $h) . '"', $headers)) . "\n";

        $no = 1;
        foreach ($teachers as $teacher) {
            $sections = $teacher->sections->map(fn($s) => $s->name . ($s->gradeLevel ? ' (' . $s->gradeLevel->name . ')' : ''))->join('; ');
            $subjects = $teacher->subjects->pluck('name')->join('; ');
            $dob = $teacher->date_of_birth ? Carbon::parse($teacher->date_of_birth)->format('m/d/Y') : '';
            $hired = $teacher->date_hired ? Carbon::parse($teacher->date_hired)->format('m/d/Y') : '';
            $regularized = $teacher->date_regularized ? Carbon::parse($teacher->date_regularized)->format('m/d/Y') : '';
            $prcValidity = $teacher->prc_license_validity ? Carbon::parse($teacher->prc_license_validity)->format('m/d/Y') : '';
            $row = [
                $no++,
                $teacher->employee_id ?? 'EMP-' . str_pad($teacher->id, 4, '0', STR_PAD_LEFT),
                $teacher->deped_id ?? '',
                trim(($teacher->last_name ?? '') . ', ' . ($teacher->first_name ?? '') . ' ' . ($teacher->middle_name ?? '') . ' ' . ($teacher->suffix ?? '')),
                $teacher->gender ?? '',
                $dob,
                $teacher->place_of_birth ?? '',
                $teacher->civil_status ?? '',
                $teacher->nationality ?? '',
                $teacher->religion ?? '',
                $teacher->blood_type ?? '',
                $teacher->email ?? '',
                $teacher->mobile_number ?? '',
                $teacher->telephone_number ?? '',
                implode(', ', array_filter([$teacher->street_address, $teacher->barangay, $teacher->city_municipality, $teacher->province, $teacher->zip_code])),
                $teacher->region ?? '',
                $teacher->emergency_contact_name ?? '',
                $teacher->emergency_contact_relationship ?? '',
                $teacher->emergency_contact_number ?? '',
                $teacher->emergency_contact_address ?? '',
                $teacher->employment_status ?? '',
                $hired,
                $regularized,
                $teacher->current_status ?? '',
                $teacher->teaching_level ?? '',
                $teacher->position ?? '',
                $teacher->designation ?? '',
                $teacher->department ?? '',
                $teacher->is_class_adviser ? 'Yes' : 'No',
                $teacher->advisory_class ?? '',
                $teacher->salary_grade ?? '',
                $teacher->step_increment ?? '',
                $teacher->basic_salary ?? '',
                $teacher->bank_name ?? '',
                $teacher->bank_account_number ?? '',
                $teacher->highest_education ?? '',
                $teacher->degree_program ?? '',
                $teacher->major ?? '',
                $teacher->minor ?? '',
                $teacher->school_graduated ?? '',
                $teacher->year_graduated ?? '',
                $teacher->honors_received ?? '',
                $teacher->prc_license_number ?? '',
                $prcValidity,
                $teacher->let_passer ? 'Yes' : 'No',
                $teacher->board_rating ?? '',
                $teacher->tesda_nc ?? '',
                $teacher->tesda_sector ?? '',
                $teacher->years_of_experience ?? '',
                $teacher->previous_school ?? '',
                $teacher->previous_position ?? '',
                $teacher->gsis_id ?? '',
                $teacher->pagibig_id ?? '',
                $teacher->philhealth_id ?? '',
                $teacher->sss_id ?? '',
                $teacher->tin_id ?? '',
                $teacher->pagibig_rtn ?? '',
                $teacher->spouse_name ?? '',
                $teacher->spouse_occupation ?? '',
                $teacher->spouse_contact ?? '',
                $teacher->number_of_children ?? '',
                $teacher->father_name ?? '',
                $teacher->father_occupation ?? '',
                $teacher->mother_name ?? '',
                $teacher->mother_occupation ?? '',
                $sections,
                $subjects,
                $teacher->remarks ?? '',
            ];
            $csv .= implode(',', array_map(fn($cell) => '"' . str_replace('"', '""', (string)$cell) . '"', $row)) . "\n";
        }

        return response($csv, 200, ['Content-Type' => 'text/csv; charset=UTF-8', 'Content-Disposition' => 'attachment; filename="Teachers_Full_Export_' . now()->format('Y-m-d_His') . '.csv"']);
    }

    public function printProfiles(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:teachers,id']);
        $teachers = Teacher::with(['sections.gradeLevel', 'subjects', 'user'])->whereIn('id', $request->ids)->get()->sortBy(fn($t) => [strtoupper($t->last_name ?? ''), strtoupper($t->first_name ?? '')]);
        $schoolHead = Setting::where('key', 'school_head')->value('value') ?? '';

        // Pre-load photos as base64 data URIs so they print instantly without async loading
        $photos = [];
        foreach ($teachers as $teacher) {
            $photoData = null;
            if ($teacher->photo_path && Storage::disk('public')->exists($teacher->photo_path)) {
                $mime = Storage::disk('public')->mimeType($teacher->photo_path) ?: 'image/jpeg';
                $base64 = base64_encode(Storage::disk('public')->get($teacher->photo_path));
                $photoData = 'data:' . $mime . ';base64,' . $base64;
            } elseif ($teacher->user && $teacher->user->photo && str_starts_with($teacher->user->photo, 'data:')) {
                $photoData = $teacher->user->photo;
            }
            $photos[$teacher->id] = $photoData;
        }

        return view('admin.teachers.print', compact('teachers', 'schoolHead', 'photos'));
    }
}