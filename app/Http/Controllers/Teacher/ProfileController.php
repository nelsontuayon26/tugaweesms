<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Teacher;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $teacher = Teacher::firstOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $user->first_name ?? 'Teacher',
                'last_name'  => $user->last_name ?? '',
                'email'      => $user->email,
            ]
        );

        return view('teacher.profile.index', compact('user', 'teacher'));
    }

    public function edit()
    {
        $user = auth()->user();

        $teacher = Teacher::firstOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $user->first_name ?? 'Teacher',
                'last_name'  => $user->last_name ?? '',
                'email'      => $user->email,
            ]
        );

        return view('teacher.profile.edit', compact('user', 'teacher'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $teacher = Teacher::where('user_id', $user->id)->first();

        // Validate photo if uploaded
        if ($request->hasFile('photo')) {
            $request->validate([
                'photo' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $mime = $file->getMimeType();
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $user->photo = 'data:' . $mime . ';base64,' . $base64;
            $user->save();
        } elseif ($request->input('remove_photo') === '1') {
            $user->photo = null;
            $user->save();
        }

        // ✅ UPDATE USERS TABLE
        $user->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'email' => $request->email,
        ]);

        // ✅ UPDATE TEACHERS TABLE — all fields from the form with correct column names
        $teacher->update($request->only([
            'first_name', 'middle_name', 'last_name', 'suffix',
            'deped_id', 'gender', 'date_of_birth', 'place_of_birth',
            'civil_status', 'nationality', 'religion', 'blood_type',
            'mobile_number', 'telephone_number',
            'street_address', 'barangay', 'city_municipality', 'province', 'zip_code', 'region',
            'emergency_contact_name', 'emergency_contact_relationship', 'emergency_contact_number', 'emergency_contact_address',
            'employment_status', 'date_hired', 'date_regularized', 'current_status',
            'teaching_level', 'position', 'designation', 'department',
            'is_class_adviser', 'advisory_class',
            'salary_grade', 'step_increment', 'basic_salary', 'bank_name', 'bank_account_number',
            'highest_education', 'degree_program', 'major', 'minor',
            'school_graduated', 'year_graduated', 'honors_received',
            'prc_license_number', 'prc_license_validity', 'let_passer', 'board_rating',
            'tesda_nc', 'tesda_sector', 'years_of_experience', 'previous_school', 'previous_position',
            'gsis_id', 'pagibig_id', 'philhealth_id', 'sss_id', 'tin_id', 'pagibig_rtn',
            'spouse_name', 'spouse_occupation', 'spouse_contact', 'number_of_children',
            'father_name', 'father_occupation', 'mother_name', 'mother_occupation',
            'remarks'
        ]));

        return redirect()->route('teacher.profile')->with('success', 'Profile updated!');
    }

     public function settings()
    {
        $user = auth()->user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        return view('teacher.settings', compact('user', 'teacher'));
    }
      // Handle form submission to update settings
    public function updateSettings(Request $request)
    {
        $user = auth()->user();
        $teacher = $user->teacher;

        // Validate the request
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            // add other fields from your form
        ]);

        // Update user fields
        $user->update($data);

        // Update teacher fields (add fields from your settings form)
        $teacherData = $request->only([
            'deped_id', 'date_of_birth', 'place_of_birth', 'gender',
            'civil_status', 'nationality', 'religion', 'blood_type',
            'employment_status', 'date_hired', 'position', 'department',
            'highest_education', 'degree_program', 'major', 'minor',
            'school_graduated', 'year_graduated', 'prc_license_number',
            'prc_license_validity', 'years_of_experience'
        ]);
        $teacher->update($teacherData);

        return redirect()->route('teacher.settings')->with('success', 'Settings updated successfully!');
    }
}