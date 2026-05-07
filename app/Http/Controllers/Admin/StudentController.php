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
        return view('admin.students.create', compact('gradeLevels', 'sections'));
    }

    public function store(Request $request)
    {
        // For transferees, use the entered value as the full LRN; for new/continuing, prefix with 120231
        $isTransferee = $request->type === 'transferee';
        $fullLrn = $request->filled('lrn_suffix')
            ? ($isTransferee ? $request->lrn_suffix : '120231' . $request->lrn_suffix)
            : null;

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'lrn_suffix' => [
                'nullable',
                $isTransferee ? 'digits:12' : 'digits:6',
                function ($attribute, $value, $fail) use ($fullLrn) {
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
            'previous_school' => 'nullable|string|max:255|required_if:type,transferee',
            'father_name' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'guardian_name' => 'required|string|max:255',
            'guardian_relationship' => 'required|string|max:255',
            'guardian_contact' => 'nullable|string|max:50',
            'street_address' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'remarks' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => ['required', 'string', SettingsEnforcer::getPasswordRules(), 'confirmed'],
            'birth_certificate' => $request->type === 'new' ? 'required|file|mimes:pdf,jpg,jpeg,png|max:5120' : 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'report_card' => in_array($request->type, ['new', 'transferee']) ? 'required|file|mimes:pdf,jpg,jpeg,png|max:5120' : 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'good_moral' => $request->type === 'transferee' ? 'required|file|mimes:pdf,jpg,jpeg,png|max:5120' : 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'transfer_credential' => $request->type === 'transferee' ? 'required|file|mimes:pdf,jpg,jpeg,png|max:5120' : 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $studentRole = Role::where('name', 'Pupil')->firstOrFail();

            $photoData = null;
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $mime = $file->getMimeType();
                $base64 = base64_encode(file_get_contents($file->getRealPath()));
                $photoData = 'data:' . $mime . ';base64,' . $base64;
            }

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
            ]);

            $student = $user->student()->create([
                'lrn' => $fullLrn,
                'birthdate' => $request->birthday,
                'birth_place' => $request->birth_place,
                'gender' => $request->gender,
                'nationality' => $request->nationality,
                'religion' => $request->religion,
                'father_name' => $request->father_name,
                'father_occupation' => $request->father_occupation,
                'mother_name' => $request->mother_name,
                'mother_occupation' => $request->mother_occupation,
                'guardian_name' => $request->guardian_name,
                'guardian_relationship' => $request->guardian_relationship,
                'guardian_contact' => $request->guardian_contact,
                'street_address' => $request->street_address,
                'barangay' => $request->barangay,
                'city' => $request->city,
                'province' => $request->province,
                'zip_code' => $request->zip_code,
                'grade_level_id' => $request->grade_level_id,
                'section_id' => $request->section_id,
                'ethnicity' => $request->ethnicity,
                'mother_tongue' => $request->mother_tongue,
                'remarks' => $request->remarks,
                'status' => 'active',
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

            $activeSchoolYear = SchoolYear::where('is_active', true)->first();
            if ($activeSchoolYear) {
                Enrollment::create([
                    'student_id' => $student->id,
                    'section_id' => $request->section_id,
                    'school_year_id' => $activeSchoolYear->id,
                    'grade_level_id' => $request->grade_level_id,
                    'status' => 'enrolled',
                    'type' => $request->type ?? 'new',
                    'previous_school' => $request->previous_school,
                    'enrollment_date' => now(),
                ]);
            }

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
        
        return view('admin.students.show', compact('student'));
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
        $fullLrn = $request->filled('lrn_suffix') ? '120231' . $request->lrn_suffix : null;

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'lrn_suffix' => [
                'nullable',
                'digits:6',
                function ($attribute, $value, $fail) use ($fullLrn, $student) {
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
            'previous_school' => 'nullable|string|max:255|required_if:type,transferee',
            'father_name' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'guardian_name' => 'required|string|max:255',
            'guardian_relationship' => 'required|string|max:255',
            'guardian_contact' => 'nullable|string|max:50',
            'street_address' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'remarks' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'username' => 'required|string|max:50|unique:users,username,' . $student->user_id,
            'password' => ['nullable', 'string', SettingsEnforcer::getPasswordRules(), 'confirmed'],
            'birth_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'report_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'good_moral' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'transfer_credential' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
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

            $studentData = [
                'lrn' => $fullLrn,
                'birthdate' => $request->birthday,
                'birth_place' => $request->birth_place,
                'gender' => $request->gender,
                'nationality' => $request->nationality,
                'religion' => $request->religion,
                'father_name' => $request->father_name,
                'father_occupation' => $request->father_occupation,
                'mother_name' => $request->mother_name,
                'mother_occupation' => $request->mother_occupation,
                'guardian_name' => $request->guardian_name,
                'guardian_relationship' => $request->guardian_relationship,
                'guardian_contact' => $request->guardian_contact,
                'street_address' => $request->street_address,
                'barangay' => $request->barangay,
                'city' => $request->city,
                'province' => $request->province,
                'zip_code' => $request->zip_code,
                'grade_level_id' => $request->grade_level_id,
                'section_id' => $request->section_id,
                'ethnicity' => $request->ethnicity,
                'mother_tongue' => $request->mother_tongue,
                'remarks' => $request->remarks,
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
                        'previous_school' => $request->previous_school,
                        'enrollment_date' => now(),
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
            $student->user->delete();
            $student->delete();
            DB::commit();
            return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete student: ' . $e->getMessage());
        }
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
