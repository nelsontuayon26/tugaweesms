<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Role;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use App\Services\SettingsEnforcer;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        $schoolYearId = $request->get('school_year_id', $activeSchoolYear?->id);
        $schoolYear = \App\Models\SchoolYear::find($schoolYearId) ?? $activeSchoolYear;
        
        $isActiveYear = $schoolYearId == $activeSchoolYear?->id;
        $enrollmentStatuses = $isActiveYear ? ['enrolled'] : ['enrolled', 'completed'];
        
        $query = Student::with([
                'user',
                'section',
                'gradeLevel',
                'enrollments' => function ($query) use ($schoolYearId, $enrollmentStatuses) {
                    $query->where('school_year_id', $schoolYearId)
                          ->whereIn('status', $enrollmentStatuses);
                },
                'enrollments.section.gradeLevel',
            ])
            ->whereHas('enrollments', function ($query) use ($schoolYearId, $enrollmentStatuses) {
                $query->where('school_year_id', $schoolYearId)
                      ->whereIn('status', $enrollmentStatuses)
                      ->whereNotNull('section_id');
            });
        
        if ($isActiveYear) {
            $query->where('students.status', 'active');
        }
        
        $query->join('users', 'users.id', '=', 'students.user_id');

        // Server-side grade filter
        if ($request->filled('grade')) {
            $gradeName = $request->grade;
            $query->whereHas('enrollments.section.gradeLevel', function ($q) use ($gradeName) {
                $q->where('name', $gradeName);
            });
        }

        // Server-side section filter
        if ($request->filled('section')) {
            $sectionName = $request->section;
            $query->whereHas('enrollments.section', function ($q) use ($sectionName) {
                $q->where('name', $sectionName);
            });
        }

        // Apply sort using JOINs instead of correlated subqueries for better performance
        $sortBy = $request->get('sort', 'default');
        if ($sortBy === 'name') {
            $query->orderBy('users.last_name', 'asc')->orderBy('users.first_name', 'asc');
        } elseif ($sortBy === 'grade') {
            $query->leftJoin('enrollments as e_sort', function ($join) use ($schoolYearId, $enrollmentStatuses) {
                $join->on('e_sort.student_id', '=', 'students.id')
                     ->where('e_sort.school_year_id', $schoolYearId)
                     ->whereIn('e_sort.status', $enrollmentStatuses);
            })
            ->leftJoin('sections as s_sort', 's_sort.id', '=', 'e_sort.section_id')
            ->leftJoin('grade_levels as gl_sort', 'gl_sort.id', '=', 's_sort.grade_level_id')
            ->orderBy('gl_sort.name', 'asc')
            ->orderBy('users.last_name', 'asc')
            ->orderBy('users.first_name', 'asc');
        } else {
            // Default: section → males first → last name → first name
            $query->leftJoin('enrollments as e_sort', function ($join) use ($schoolYearId, $enrollmentStatuses) {
                $join->on('e_sort.student_id', '=', 'students.id')
                     ->where('e_sort.school_year_id', $schoolYearId)
                     ->whereIn('e_sort.status', $enrollmentStatuses);
            })
            ->leftJoin('sections as s_sort', 's_sort.id', '=', 'e_sort.section_id')
            ->orderBy('s_sort.name', 'asc')
            ->orderByRaw("CASE WHEN students.gender = 'Male' THEN 0 ELSE 1 END")
            ->orderBy('users.last_name', 'asc')
            ->orderBy('users.first_name', 'asc');
        }

        $students = $query
            ->select('students.*')
            ->paginate(10)
            ->appends($request->only(['grade', 'section', 'sort', 'school_year_id']));
        
        return view('admin.students.index', compact('students', 'activeSchoolYear', 'schoolYear'));
    }

    public function create()
    {
        $gradeLevels = \App\Models\GradeLevel::orderBy('name')->get();
        $sections = \App\Models\Section::withCount('students')->orderBy('name')->get();
        
        // Get active school year for display
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        
        return view('admin.students.create', compact('gradeLevels', 'sections', 'activeSchoolYear'));
    }

        /**
     * Build full name from split name parts
     */
    private function buildFullName(?string $last, ?string $first, ?string $middle): ?string
    {
        $parts = array_filter([$first, $middle, $last]);
        return empty($parts) ? null : implode(' ', $parts);
    }

        public function store(Request $request)
    {
        $studentType = $request->input('type', 'new');
        
        // Build full LRN: if "With LRN?" = Yes, use the full 12-digit LRN from input
        // For new pupils without LRN, leave null (school will generate later)
        $hasLrn = $request->boolean('has_lrn', false);
        if ($hasLrn && $request->filled('lrn_suffix')) {
            $fullLrn = $request->lrn_suffix; // Full 12-digit LRN as entered
        } else {
            $fullLrn = null;
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'lrn_suffix' => [
                'nullable',
                function ($attribute, $value, $fail) use ($hasLrn, $fullLrn) {
                    if ($hasLrn) {
                        // If "With LRN?" = Yes, must provide exactly 12 digits
                        if (empty($value)) {
                            $fail('The LRN is required when "With LRN?" is Yes.');
                            return;
                        }
                        if (!preg_match('/^\d{12}$/', $value)) {
                            $fail('The LRN must be exactly 12 digits.');
                            return;
                        }
                    }
                    if ($fullLrn && Student::where('lrn', $fullLrn)->exists()) {
                        $fail('The LRN is already taken.');
                    }
                },
            ],
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'email' => 'required|email|max:255|unique:users',
            'gender' => 'required|in:Male,Female,Other',
            'birthday' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'religion' => 'required|string|max:255',
            'ethnicity' => 'required|string|max:100',
            'mother_tongue' => 'required|string|max:100',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'section_id' => 'required|exists:sections,id',
            'type' => 'required|in:new,transferee,continuing',
            
            // New DepEd fields
            'psa_birth_cert_no' => 'nullable|string|max:50',
            'is_ip' => 'nullable|boolean',
            'ip_specification' => 'nullable|string|max:100|required_if:is_ip,1',
            'is_4ps_beneficiary' => 'nullable|boolean',
            'household_id_4ps' => 'nullable|string|max:50|required_if:is_4ps_beneficiary,1',
            'is_returning_balik_aral' => 'nullable|boolean',
            'has_lrn' => 'nullable|boolean',
            
            // Parent/Guardian - split name fields (matching register-page)
            'father_last_name' => 'nullable|string|max:255',
            'father_first_name' => 'nullable|string|max:255',
            'father_middle_name' => 'nullable|string|max:255',
            'father_contact' => 'nullable|string|size:11|regex:/^\d{11}$/',
            'father_occupation' => 'nullable|string|max:255',
            
            'mother_last_name' => 'nullable|string|max:255',
            'mother_first_name' => 'nullable|string|max:255',
            'mother_middle_name' => 'nullable|string|max:255',
            'mother_contact' => 'nullable|string|size:11|regex:/^\d{11}$/',
            'mother_occupation' => 'nullable|string|max:255',
            
            'guardian_last_name' => 'nullable|string|max:255',
            'guardian_first_name' => 'nullable|string|max:255',
            'guardian_middle_name' => 'nullable|string|max:255',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relationship' => 'required|string|max:255',
            'guardian_contact' => 'nullable|string|size:11|regex:/^\d{11}$/',
            
            // Address (matching register-page structure)
            'street_address' => 'required|string|max:255',
            'street_name' => 'nullable|string|max:255',
            'barangay' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            
            // Permanent address
            'same_as_current_address' => 'nullable|boolean',
            'permanent_street_address' => 'nullable|string|max:255|required_if:same_as_current_address,0',
            'permanent_street_name' => 'nullable|string|max:255',
            'permanent_barangay' => 'nullable|string|max:100|required_if:same_as_current_address,0',
            'permanent_city' => 'nullable|string|max:100|required_if:same_as_current_address,0',
            'permanent_province' => 'nullable|string|max:100|required_if:same_as_current_address,0',
            'permanent_zip_code' => 'nullable|string|max:20|required_if:same_as_current_address,0',
            
            // Returning/Transferee fields
            'last_grade_level_completed' => 'nullable|string|max:50',
            'last_school_year_completed' => 'nullable|string|max:20',
            'previous_school' => 'nullable|string|max:255|required_if:type,transferee',
            'previous_school_id' => 'nullable|string|max:20',
            
            'remarks' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => ['required', 'string', SettingsEnforcer::getPasswordRules(), 'confirmed'],
            
            // Documents - dynamic based on pupil type (handled in after() below)
            'birth_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'report_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'good_moral' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'transfer_credential' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Custom document validation based on pupil type (matching register-page logic)
        $validator->after(function ($validator) use ($request, $studentType) {
            if ($studentType === 'new') {
                if (!$request->hasFile('birth_certificate')) {
                    $validator->errors()->add('birth_certificate', 'Birth Certificate is required for new pupils.');
                }
            } elseif ($studentType === 'transferee') {
                $requiredDocs = [
                    'birth_certificate' => 'Birth Certificate',
                    'report_card' => 'Report Card / Form 138',
                    'good_moral' => 'Certificate of Good Moral Character',
                    'transfer_credential' => 'Transfer Credentials / Honorable Dismissal',
                ];
                foreach ($requiredDocs as $field => $label) {
                    if (!$request->hasFile($field)) {
                        $validator->errors()->add($field, $label . ' is required for transferee pupils.');
                    }
                }
            }
            // Continuing pupils: all documents optional, no validation needed
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $studentRole = Role::where('name', 'Pupil')->firstOrFail();

            // Handle photo (base64 for admin - keeping your current pattern)
            $photoData = null;
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $mime = $file->getMimeType();
                $base64 = base64_encode(file_get_contents($file->getRealPath()));
                $photoData = 'data:' . $mime . ';base64,' . $base64;
            }

            // Create user
            $user = User::create([
                'role_id' => $studentRole->id,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'suffix' => $request->suffix,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'password_updated_at' => now(),
                'photo' => $photoData,
                'is_active' => 1, // Admin-created students are active immediately
            ]);

            // Build full names from split fields (matching register-page)
            $fatherName = $this->buildFullName(
                $request->father_last_name,
                $request->father_first_name,
                $request->father_middle_name
            );
            $motherName = $this->buildFullName(
                $request->mother_last_name,
                $request->mother_first_name,
                $request->mother_middle_name
            );
            $guardianName = $request->filled('guardian_name')
                ? $request->guardian_name
                : $this->buildFullName(
                    $request->guardian_last_name,
                    $request->guardian_first_name,
                    $request->guardian_middle_name
                );

            // Handle permanent address (matching register-page logic)
            $sameAsCurrent = $request->boolean('same_as_current_address', true);
            $permanentStreet = $sameAsCurrent ? $request->street_address : $request->permanent_street_address;
            $permanentStreetName = $sameAsCurrent ? $request->street_name : $request->permanent_street_name;
            $permanentBarangay = $sameAsCurrent ? $request->barangay : $request->permanent_barangay;
            $permanentCity = $sameAsCurrent ? $request->city : $request->permanent_city;
            $permanentProvince = $sameAsCurrent ? $request->province : $request->permanent_province;
            $permanentZip = $sameAsCurrent ? $request->zip_code : $request->permanent_zip_code;

            // Create student (active - admin creates directly enrolled)
            $student = $user->student()->create([
                'lrn' => $fullLrn,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'suffix' => $request->suffix,
                'has_lrn' => $request->boolean('has_lrn', false),
                'birthdate' => $request->birthday,
                'birth_place' => $request->birth_place,
                'psa_birth_cert_no' => $request->psa_birth_cert_no,
                'gender' => $request->gender,
                'nationality' => $request->nationality,
                'religion' => $request->religion,
                
                // New DepEd fields
                'is_ip' => $request->boolean('is_ip', false),
                'ip_specification' => $request->ip_specification,
                'is_4ps_beneficiary' => $request->boolean('is_4ps_beneficiary', false),
                'household_id_4ps' => $request->household_id_4ps,
                'is_returning_balik_aral' => $request->boolean('is_returning_balik_aral', false),
                
                // Parents (built from split fields)
                'father_name' => $fatherName,
                'father_occupation' => $request->father_occupation,
                'father_contact' => $request->father_contact,
                'mother_name' => $motherName,
                'mother_occupation' => $request->mother_occupation,
                'mother_contact' => $request->mother_contact,
                
                // Guardian
                'guardian_name' => $guardianName,
                'guardian_relationship' => $request->guardian_relationship,
                'guardian_contact' => $request->guardian_contact,
                
                // Current Address
                'street_address' => $request->street_address,
                'street_name' => $request->street_name,
                'barangay' => $request->barangay,
                'city' => $request->city,
                'province' => $request->province,
                'zip_code' => $request->zip_code,
                
                // Permanent Address
                'same_as_current_address' => $sameAsCurrent,
                'permanent_street_address' => $permanentStreet,
                'permanent_street_name' => $permanentStreetName,
                'permanent_barangay' => $permanentBarangay,
                'permanent_city' => $permanentCity,
                'permanent_province' => $permanentProvince,
                'permanent_zip_code' => $permanentZip,
                
                'status' => 'active',
                'grade_level_id' => $request->grade_level_id,
                'section_id' => $request->section_id,
                
                'ethnicity' => $request->ethnicity,
                'mother_tongue' => $request->mother_tongue,
                'remarks' => $request->remarks,
                
                // Returning/Transferee
                'last_grade_level_completed' => $request->last_grade_level_completed,
                'last_school_year_completed' => $request->last_school_year_completed,
                'previous_school' => $request->previous_school,
                'previous_school_id' => $request->previous_school_id,
                
                'registration_status' => 'enrolled', // Admin creates = enrolled, not pending
            ]);

            // Handle document uploads
            $documentPaths = [];
            if ($request->hasFile('birth_certificate')) {
                $documentPaths['birth_certificate_path'] = $request->file('birth_certificate')->store('student-documents/' . $student->id, 'public');
            }
            if ($request->hasFile('report_card')) {
                $documentPaths['report_card_path'] = $request->file('report_card')->store('student-documents/' . $student->id, 'public');
            }
            if ($request->hasFile('good_moral')) {
                $documentPaths['good_moral_path'] = $request->file('good_moral')->store('student-documents/' . $student->id, 'public');
            }
            if ($request->hasFile('transfer_credential')) {
                $documentPaths['transfer_credential_path'] = $request->file('transfer_credential')->store('student-documents/' . $student->id, 'public');
            }
            if (!empty($documentPaths)) {
                $student->update($documentPaths);
            }

            // Create enrollment (active school year)
            $activeSchoolYear = SchoolYear::where('is_active', true)->first();
            if ($activeSchoolYear) {
                Enrollment::create([
                    'student_id' => $student->id,
                    'section_id' => $request->section_id,
                    'school_year_id' => $activeSchoolYear->id,
                    'grade_level_id' => $request->grade_level_id,
                    'status' => 'enrolled',
                    'type' => $request->type ?? 'new',
                    'previous_school' => in_array($request->type, ['transferee', 'continuing']) ? $request->previous_school : null,
                    'previous_school_id' => $request->previous_school_id,
                    'enrollment_date' => now(),
                    
                    // Store current school info at time of enrollment (matching register-page)
                    'school_name' => Setting::get('school_name'),
                    'school_id' => Setting::get('deped_school_id'),
                    'school_district' => Setting::get('school_district'),
                    'school_division' => Setting::get('school_division'),
                    'school_region' => Setting::get('school_region'),
                ]);
            }

            $newValues = $student->toArray();
            if (isset($newValues['user']['photo']) && strlen($newValues['user']['photo']) > 100) {
                $newValues['user']['photo'] = '[photo uploaded]';
            }
            \App\Models\ActivityLog::log('created', 'Student', $student->id, 'Created new Student', null, $newValues);
            
            DB::commit();
            return redirect()->route('admin.students.index')->with('success', 'Student created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create student: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Student $student)
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        
        $student->load(['user', 'gradeLevel', 'enrollments' => function ($query) use ($activeSchoolYear) {
            $query->where('school_year_id', $activeSchoolYear?->id)
                  ->where('status', 'enrolled')
                  ->with('section.gradeLevel');
        }]);
        
        $schoolSettings = Setting::where('group', 'school')->get()->keyBy('key')->map->value;
        $schoolId = $schoolSettings['deped_school_id'] ?? '';
        $schoolName = $schoolSettings['school_name'] ?? '';
        $schoolDivision = $schoolSettings['school_division'] ?? '';
        $schoolRegion = $schoolSettings['school_region'] ?? '';
        $schoolHead = $schoolSettings['school_head'] ?? Setting::where('key', 'school_head')->value('value') ?? '';
        
        $activityLogs = \App\Models\ActivityLog::forEntity('Student', $student->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.students.show', compact('student', 'activeSchoolYear', 'schoolId', 'schoolName', 'schoolDivision', 'schoolRegion', 'schoolHead', 'activityLogs'));
    }

    public function edit(Student $student)
    {
        $student->load('user');
        $gradeLevels = \App\Models\GradeLevel::orderBy('name')->get();
        $sections = \App\Models\Section::orderBy('name')->get();
        
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        $activeEnrollment = $student->enrollments()
            ->where('school_year_id', $activeSchoolYear?->id)
            ->where('status', 'enrolled')
            ->first();
        
        return view('admin.students.edit', compact('student', 'gradeLevels', 'sections', 'activeEnrollment'));
    }

    public function update(Request $request, Student $student)
    {
        $studentType = $request->input('type', 'new');
        
        // Build full LRN: if "With LRN?" = Yes, use the full 12-digit LRN from input
        $hasLrn = $request->boolean('has_lrn', false);
        if ($hasLrn && $request->filled('lrn_suffix')) {
            $fullLrn = $request->lrn_suffix;
        } else {
            $fullLrn = null;
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'lrn_suffix' => [
                'nullable',
                function ($attribute, $value, $fail) use ($studentType, $fullLrn, $student) {
                    if ($studentType === 'transferee') {
                        if (!empty($value) && !preg_match('/^\d{12}$/', $value)) {
                            $fail('The LRN must be exactly 12 digits.');
                            return;
                        }
                    }
                    if ($fullLrn && Student::where('lrn', $fullLrn)->where('id', '!=', $student->id)->exists()) {
                        $fail('The LRN is already taken.');
                    }
                },
            ],
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'email' => 'required|email|max:255|unique:users,email,' . $student->user_id,
            'gender' => 'required|in:Male,Female,Other',
            'birthday' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'religion' => 'required|string|max:255',
            'ethnicity' => 'required|string|max:100',
            'mother_tongue' => 'required|string|max:100',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'section_id' => 'required|exists:sections,id',
            'type' => 'required|in:new,transferee,continuing',
            
            // New DepEd fields
            'psa_birth_cert_no' => 'nullable|string|max:50',
            'is_ip' => 'nullable|boolean',
            'ip_specification' => 'nullable|string|max:100|required_if:is_ip,1',
            'is_4ps_beneficiary' => 'nullable|boolean',
            'household_id_4ps' => 'nullable|string|max:50|required_if:is_4ps_beneficiary,1',
            'is_returning_balik_aral' => 'nullable|boolean',
            'has_lrn' => 'nullable|boolean',
            
            // Parent/Guardian - split name fields
            'father_last_name' => 'nullable|string|max:255',
            'father_first_name' => 'nullable|string|max:255',
            'father_middle_name' => 'nullable|string|max:255',
            'father_contact' => 'nullable|string|size:11|regex:/^\d{11}$/',
            'father_occupation' => 'nullable|string|max:255',
            
            'mother_last_name' => 'nullable|string|max:255',
            'mother_first_name' => 'nullable|string|max:255',
            'mother_middle_name' => 'nullable|string|max:255',
            'mother_contact' => 'nullable|string|size:11|regex:/^\d{11}$/',
            'mother_occupation' => 'nullable|string|max:255',
            
            'guardian_last_name' => 'nullable|string|max:255',
            'guardian_first_name' => 'nullable|string|max:255',
            'guardian_middle_name' => 'nullable|string|max:255',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relationship' => 'required|string|max:255',
            'guardian_contact' => 'nullable|string|size:11|regex:/^\d{11}$/',
            
            // Address
            'street_address' => 'required|string|max:255',
            'street_name' => 'nullable|string|max:255',
            'barangay' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            
            // Permanent address
            'same_as_current_address' => 'nullable|boolean',
            'permanent_street_address' => 'nullable|string|max:255|required_if:same_as_current_address,0',
            'permanent_street_name' => 'nullable|string|max:255',
            'permanent_barangay' => 'nullable|string|max:100|required_if:same_as_current_address,0',
            'permanent_city' => 'nullable|string|max:100|required_if:same_as_current_address,0',
            'permanent_province' => 'nullable|string|max:100|required_if:same_as_current_address,0',
            'permanent_zip_code' => 'nullable|string|max:20|required_if:same_as_current_address,0',
            
            // Returning/Transferee fields
            'last_grade_level_completed' => 'nullable|string|max:50',
            'last_school_year_completed' => 'nullable|string|max:20',
            'previous_school' => 'nullable|string|max:255|required_if:type,transferee',
            'previous_school_id' => 'nullable|string|max:20',
            
            'remarks' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'username' => 'required|string|max:50|unique:users,username,' . $student->user_id,
            'password' => ['nullable', 'string', SettingsEnforcer::getPasswordRules(), 'confirmed'],
            'birth_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'report_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'good_moral' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'transfer_credential' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Custom document validation based on pupil type (only if documents are being uploaded)
        $validator->after(function ($validator) use ($request, $studentType) {
            if ($studentType === 'new') {
                // On update, birth certificate is optional (already uploaded or not required)
            } elseif ($studentType === 'transferee') {
                // On update, documents are optional unless you want to enforce re-upload
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $oldStudentData = $student->toArray();
            $oldUserData = $student->user->toArray();
            
            // Update user
            $userData = [
                'username' => $request->username,
                'email' => $request->email,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'suffix' => $request->suffix,
                'photo' => $request->hasFile('photo')
                    ? (function ($file) {
                        $mime = $file->getMimeType();
                        $base64 = base64_encode(file_get_contents($file->getRealPath()));
                        return 'data:' . $mime . ';base64,' . $base64;
                    })($request->file('photo'))
                    : $student->user->photo,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
                $userData['password_updated_at'] = now();
            }
            $student->user->update($userData);

            // Build full names from split fields
            $fatherName = $this->buildFullName(
                $request->father_last_name,
                $request->father_first_name,
                $request->father_middle_name
            );
            $motherName = $this->buildFullName(
                $request->mother_last_name,
                $request->mother_first_name,
                $request->mother_middle_name
            );
            $guardianName = $request->filled('guardian_name')
                ? $request->guardian_name
                : $this->buildFullName(
                    $request->guardian_last_name,
                    $request->guardian_first_name,
                    $request->guardian_middle_name
                );

            // Handle permanent address
            $sameAsCurrent = $request->boolean('same_as_current_address', true);
            $permanentStreet = $sameAsCurrent ? $request->street_address : $request->permanent_street_address;
            $permanentStreetName = $sameAsCurrent ? $request->street_name : $request->permanent_street_name;
            $permanentBarangay = $sameAsCurrent ? $request->barangay : $request->permanent_barangay;
            $permanentCity = $sameAsCurrent ? $request->city : $request->permanent_city;
            $permanentProvince = $sameAsCurrent ? $request->province : $request->permanent_province;
            $permanentZip = $sameAsCurrent ? $request->zip_code : $request->permanent_zip_code;

            $studentData = [
                'lrn' => $fullLrn,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'suffix' => $request->suffix,
                'has_lrn' => $request->boolean('has_lrn', false),
                'birthdate' => $request->birthday,
                'birth_place' => $request->birth_place,
                'psa_birth_cert_no' => $request->psa_birth_cert_no,
                'gender' => $request->gender,
                'nationality' => $request->nationality,
                'religion' => $request->religion,
                
                // DepEd fields
                'is_ip' => $request->boolean('is_ip', false),
                'ip_specification' => $request->ip_specification,
                'is_4ps_beneficiary' => $request->boolean('is_4ps_beneficiary', false),
                'household_id_4ps' => $request->household_id_4ps,
                'is_returning_balik_aral' => $request->boolean('is_returning_balik_aral', false),
                
                // Parents
                'father_name' => $fatherName,
                'father_occupation' => $request->father_occupation,
                'father_contact' => $request->father_contact,
                'mother_name' => $motherName,
                'mother_occupation' => $request->mother_occupation,
                'mother_contact' => $request->mother_contact,
                
                // Guardian
                'guardian_name' => $guardianName,
                'guardian_relationship' => $request->guardian_relationship,
                'guardian_contact' => $request->guardian_contact,
                
                // Current Address
                'street_address' => $request->street_address,
                'street_name' => $request->street_name,
                'barangay' => $request->barangay,
                'city' => $request->city,
                'province' => $request->province,
                'zip_code' => $request->zip_code,
                
                // Permanent Address
                'same_as_current_address' => $sameAsCurrent,
                'permanent_street_address' => $permanentStreet,
                'permanent_street_name' => $permanentStreetName,
                'permanent_barangay' => $permanentBarangay,
                'permanent_city' => $permanentCity,
                'permanent_province' => $permanentProvince,
                'permanent_zip_code' => $permanentZip,
                
                'grade_level_id' => $request->grade_level_id,
                'section_id' => $request->section_id,
                'ethnicity' => $request->ethnicity,
                'mother_tongue' => $request->mother_tongue,
                'remarks' => $request->remarks,
                
                // Returning/Transferee
                'last_grade_level_completed' => $request->last_grade_level_completed,
                'last_school_year_completed' => $request->last_school_year_completed,
                'previous_school' => $request->previous_school,
                'previous_school_id' => $request->previous_school_id,
            ];

            if ($request->hasFile('birth_certificate')) {
                $studentData['birth_certificate_path'] = $request->file('birth_certificate')->store('student-documents/' . $student->id, 'public');
            }
            if ($request->hasFile('report_card')) {
                $studentData['report_card_path'] = $request->file('report_card')->store('student-documents/' . $student->id, 'public');
            }
            if ($request->hasFile('good_moral')) {
                $studentData['good_moral_path'] = $request->file('good_moral')->store('student-documents/' . $student->id, 'public');
            }
            if ($request->hasFile('transfer_credential')) {
                $studentData['transfer_credential_path'] = $request->file('transfer_credential')->store('student-documents/' . $student->id, 'public');
            }

            $student->update($studentData);
            
            // Audit log - compute changed fields
            $changedOld = [];
            $changedNew = [];
            $userFields = ['username','email','first_name','middle_name','last_name','suffix'];
            foreach ($userFields as $key) {
                $oldVal = $oldUserData[$key] ?? null;
                $newVal = $userData[$key] ?? null;
                if ($oldVal != $newVal) {
                    $changedOld['user_' . $key] = $oldVal;
                    $changedNew['user_' . $key] = $newVal;
                }
            }
            if (($oldUserData['photo'] ?? null) != ($userData['photo'] ?? null)) {
                $changedOld['user_photo'] = !empty($oldUserData['photo']) ? '[photo changed]' : null;
                $changedNew['user_photo'] = '[photo changed]';
            }
            if ($request->filled('password')) {
                $changedOld['user_password'] = '[changed]';
                $changedNew['user_password'] = '[changed]';
            }
            foreach ($studentData as $key => $newVal) {
                $oldVal = $oldStudentData[$key] ?? null;
                if ($oldVal != $newVal) {
                    $changedOld[$key] = $oldVal;
                    $changedNew[$key] = $newVal;
                }
            }
            if (!empty($changedOld)) {
                \App\Models\ActivityLog::log('updated', 'Student', $student->id, 'Updated Student', $changedOld, $changedNew);
            }

            // Update enrollment
            $activeSchoolYear = SchoolYear::where('is_active', true)->first();
            if ($activeSchoolYear) {
                Enrollment::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'school_year_id' => $activeSchoolYear->id,
                    ],
                    [
                        'section_id' => $request->section_id,
                        'grade_level_id' => $request->grade_level_id,
                        'status' => 'enrolled',
                        'type' => $request->type ?? 'new',
                        'previous_school' => in_array($request->type, ['transferee', 'continuing']) ? $request->previous_school : null,
                        'previous_school_id' => $request->previous_school_id,
                        'enrollment_date' => now(),
                        
                        // Store current school info at time of enrollment
                        'school_name' => Setting::get('school_name'),
                        'school_id' => Setting::get('deped_school_id'),
                        'school_district' => Setting::get('school_district'),
                        'school_division' => Setting::get('school_division'),
                        'school_region' => Setting::get('school_region'),
                    ]
                );
            }

            DB::commit();
            return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update student: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Student $student)
    {
        DB::beginTransaction();
        try {
            \App\Services\ActivityLogService::logDeleted($student, 'Student');
            $student->user->delete();
            $student->delete();
            DB::commit();
            return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete student: ' . $e->getMessage());
        }
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:students,id',
        ]);

        DB::beginTransaction();
        try {
            $students = Student::whereIn('id', $request->ids)->with('user')->get();
            foreach ($students as $student) {
                if ($student->user) {
                    $student->user->delete();
                }
                $student->delete();
            }
            DB::commit();
            return redirect()->route('admin.students.index')->with('success', count($request->ids) . ' pupil(s) deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete pupils: ' . $e->getMessage());
        }
    }

    public function exportCsv(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:students,id',
        ]);

        $activeSchoolYear = SchoolYear::where('is_active', true)->first();

        $students = Student::with(['user', 'enrollments.section.gradeLevel'])
            ->whereIn('id', $request->ids)
            ->get()
            ->sortBy(function ($student) {
                $gender = strtoupper($student->gender ?? '');
                $genderOrder = in_array($gender, ['MALE', 'M']) ? 0 : 1;
                return [$genderOrder, $student->user->last_name ?? '', $student->user->first_name ?? ''];
            });

        $headers = [
            'NO.', 'LRN', 'Name', 'School Year', 'Status', 'Grade & Section',
            'Sex (M/F)', 'Birth Date (mm/dd/yyyy)', 'Age', 'Mother Tongue', 'IP (Ethnic Group)',
            'Religion', 'Address',
            'Father\'s Name', 'Mother\'s Maiden Name', 'Guardian\'s Name',
            'Relationship to Guardian', 'Contact Number', 'Remarks'
        ];

        $csv = "\xEF\xBB\xBF";
        $csv .= implode(',', array_map(fn($h) => '"' . str_replace('"', '""', $h) . '"', $headers)) . "\n";

        $maleCounter = 0;
        $femaleCounter = 0;

        foreach ($students as $student) {
            $user = $student->user;
            $gender = strtoupper($student->gender ?? '');
            $isMale = in_array($gender, ['MALE', 'M']);
            $sexCode = $isMale ? 'M' : 'F';

            if ($isMale) {
                $maleCounter++;
                $no = $maleCounter;
            } else {
                $femaleCounter++;
                $no = $femaleCounter;
            }

            $birthDate = $student->birthdate ? Carbon::parse($student->birthdate)->format('m/d/Y') : '';
            $age = $student->age ?? '';

            $fullName = trim(($user->last_name ?? '') . ', ' . ($user->first_name ?? '') . ' ' . ($user->middle_name ?? ''));
            $addressParts = array_filter([
                $student->street_address ?? '',
                $student->barangay ?? '',
                $student->city ?? '',
                ($student->province ?? '') . ($student->zip_code ? ' ' . $student->zip_code : ''),
            ]);
            $address = implode(', ', $addressParts);

            $enrollment = $student->enrollments->first();
            $gradeLevel = $enrollment?->section?->gradeLevel?->name ?? '';
            $section = $enrollment?->section?->name ?? '';
            $gradeSection = trim($gradeLevel . ($gradeLevel && $section ? ' - ' : '') . $section);
            $schoolYearName = $activeSchoolYear?->name ?? '';
            $status = $enrollment?->status ?? 'pending';

            $row = [
                $no,
                $student->lrn ?? '',
                $fullName,
                $schoolYearName,
                $status,
                $gradeSection,
                $sexCode,
                $birthDate,
                $age,
                $student->mother_tongue ?? '',
                $student->ethnicity ?? '',
                $student->religion ?? '',
                $address,
                $student->father_name ?? '',
                $student->mother_name ?? '',
                $student->guardian_name ?? '',
                $student->guardian_relationship ?? '',
                $student->guardian_contact ?? '',
                $student->remarks ?? '',
            ];

            $csv .= implode(',', array_map(fn($cell) => '"' . str_replace('"', '""', $cell) . '"', $row)) . "\n";
        }

        $filename = 'SF1_Export_' . now()->format('Y-m-d_His') . '.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function idCard(Student $student)
    {
        return view('admin.students.id-card', compact('student'));
    }

    public function viewDocument(Student $student, $type)
    {
        $validTypes = ['birth_certificate', 'report_card', 'good_moral', 'transfer_credential', 'medical_record', 'id_picture', 'enrollment_form'];
        if (!in_array($type, $validTypes)) {
            abort(404, 'Invalid document type.');
        }
        
        $column = $type . '_path';
        $filePath = $student->$column;
        
        if (empty($filePath) || str_contains($filePath, '..')) {
            abort(404, 'Document not found.');
        }
        
        $possiblePaths = [
            storage_path('app/public/' . $filePath),
            storage_path('app/' . $filePath),
            public_path('storage/' . $filePath),
            storage_path('app/private/public/' . $filePath),
            storage_path('app/private/' . $filePath),
        ];
        
        $fullPath = null;
        foreach ($possiblePaths as $path) {
            $realPath = realpath($path);
            if ($realPath && file_exists($realPath)) {
                $fullPath = $realPath;
                break;
            }
        }
        
        if (!$fullPath) {
            abort(404, 'File not found on server.');
        }
        
        $mimeType = mime_content_type($fullPath);
        $fileName = basename($filePath);
        
        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"'
        ]);
    }
}
