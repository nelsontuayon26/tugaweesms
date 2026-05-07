<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Role;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;

class BulkImportController extends Controller
{
    public function showStudentImportForm()
    {
        $gradeLevels = GradeLevel::orderBy('order')->get();
        $schoolYears = SchoolYear::orderBy('name', 'desc')->get();
        
        return view('admin.import.students', compact('gradeLevels', 'schoolYears'));
    }

    public function importStudents(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'school_year_id' => 'required|exists:school_years,id',
        ]);

        $file = $request->file('csv_file');
        $gradeLevelId = $request->grade_level_id;
        $schoolYearId = $request->school_year_id;

        try {
            $csv = Reader::createFromPath($file->getPathname(), 'r');
            $csv->setHeaderOffset(0);
            $records = $csv->getRecords();

            $results = [
                'success' => 0,
                'failed' => 0,
                'errors' => [],
            ];

            $studentRole = Role::where('name', 'Pupil')->first();
            $activeSchoolYear = SchoolYear::find($schoolYearId);

            DB::beginTransaction();

            foreach ($records as $index => $record) {
                $rowNumber = $index + 2; // +2 because CSV starts at 1 and we have header row

                // Validate required fields
                $validator = Validator::make($record, [
                    'first_name' => 'required|string|max:100',
                    'last_name' => 'required|string|max:100',
                    'lrn' => 'required|string|size:12|unique:students,lrn',
                    'gender' => 'required|in:Male,Female',
                    'birthdate' => 'required|date',
                ]);

                if ($validator->fails()) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNumber}: " . implode(', ', $validator->errors()->all());
                    continue;
                }

                try {
                    // Generate unique username and email
                    $baseUsername = strtolower(substr($record['first_name'], 0, 1) . $record['last_name']);
                    $baseUsername = preg_replace('/[^a-z0-9]/', '', $baseUsername);
                    $username = $this->generateUniqueUsername($baseUsername);
                    $email = $username . '@tugaweelem.edu';

                    // Create user
                    $user = User::create([
                        'role_id' => $studentRole->id,
                        'first_name' => $record['first_name'],
                        'middle_name' => $record['middle_name'] ?? null,
                        'last_name' => $record['last_name'],
                        'username' => $username,
                        'email' => $email,
                        'password' => Hash::make($record['lrn']), // Default password is LRN
                        'is_active' => true,
                    ]);

                    // Create student
                    $student = Student::create([
                        'user_id' => $user->id,
                        'lrn' => $record['lrn'],
                        'first_name' => $record['first_name'],
                        'middle_name' => $record['middle_name'] ?? null,
                        'last_name' => $record['last_name'],
                        'gender' => $record['gender'],
                        'birthdate' => $record['birthdate'],
                        'grade_level_id' => $gradeLevelId,
                        'school_year_id' => $schoolYearId,
                        'status' => 'active',
                    ]);

                    // Create enrollment
                    \App\Models\Enrollment::create([
                        'student_id' => $student->id,
                        'school_year_id' => $schoolYearId,
                        'grade_level_id' => $gradeLevelId,
                        'enrollment_date' => now(),
                        'status' => 'enrolled',
                        'type' => 'New',
                    ]);

                    $results['success']++;

                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNumber}: " . $e->getMessage();
                }
            }

            DB::commit();

            // Log the bulk import
            ActivityLogService::logBulkAction('imported', 'Pupil', $results['success']);

            $message = "Import completed: {$results['success']} successful, {$results['failed']} failed.";
            
            if ($results['failed'] > 0) {
                return back()->with('warning', $message)->with('import_errors', $results['errors']);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function showTeacherImportForm()
    {
        return view('admin.import.teachers');
    }

    public function importTeachers(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');

        try {
            $csv = Reader::createFromPath($file->getPathname(), 'r');
            $csv->setHeaderOffset(0);
            $records = $csv->getRecords();

            $results = [
                'success' => 0,
                'failed' => 0,
                'errors' => [],
            ];

            $teacherRole = Role::where('name', 'Teacher')->first();

            DB::beginTransaction();

            foreach ($records as $index => $record) {
                $rowNumber = $index + 2;

                $validator = Validator::make($record, [
                    'first_name' => 'required|string|max:100',
                    'last_name' => 'required|string|max:100',
                    'email' => 'required|email|unique:users,email',
                    'employee_id' => 'required|string|unique:teachers,employee_id',
                ]);

                if ($validator->fails()) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNumber}: " . implode(', ', $validator->errors()->all());
                    continue;
                }

                try {
                    $baseUsername = strtolower(substr($record['first_name'], 0, 1) . $record['last_name']);
                    $baseUsername = preg_replace('/[^a-z0-9]/', '', $baseUsername);
                    $username = $this->generateUniqueUsername($baseUsername);

                    $user = User::create([
                        'role_id' => $teacherRole->id,
                        'first_name' => $record['first_name'],
                        'middle_name' => $record['middle_name'] ?? null,
                        'last_name' => $record['last_name'],
                        'username' => $username,
                        'email' => $record['email'],
                        'password' => Hash::make('password123'), // Default password
                        'is_active' => true,
                    ]);

                    Teacher::create([
                        'user_id' => $user->id,
                        'employee_id' => $record['employee_id'],
                        'specialization' => $record['specialization'] ?? null,
                        'status' => 'active',
                    ]);

                    $results['success']++;

                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNumber}: " . $e->getMessage();
                }
            }

            DB::commit();

            ActivityLogService::logBulkAction('imported', 'Teacher', $results['success']);

            $message = "Import completed: {$results['success']} successful, {$results['failed']} failed.";
            
            if ($results['failed'] > 0) {
                return back()->with('warning', $message)->with('import_errors', $results['errors']);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function downloadStudentTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="student-import-template.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['first_name', 'middle_name', 'last_name', 'lrn', 'gender', 'birthdate']);
            fputcsv($file, ['John', 'Doe', 'Smith', '123456789012', 'Male', '2010-05-15']);
            fputcsv($file, ['Jane', 'Marie', 'Doe', '123456789013', 'Female', '2010-08-22']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadTeacherTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="teacher-import-template.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['first_name', 'middle_name', 'last_name', 'email', 'employee_id', 'specialization']);
            fputcsv($file, ['Maria', 'Santos', 'Garcia', 'maria.garcia@tugaweelem.edu', 'T001', 'Mathematics']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function generateUniqueUsername(string $base): string
    {
        $username = $base;
        $counter = 1;
        
        while (User::where('username', $username)->exists()) {
            $username = $base . $counter;
            $counter++;
        }
        
        return $username;
    }
}
