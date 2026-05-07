<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'System Admin')->first();
        $registrarRole = Role::where('name', 'Registrar')->first();
        $teacherRole = Role::where('name', 'Teacher')->first();
        $studentRole = Role::where('name', 'Pupil')->first();

        // Admin
        User::firstOrCreate([
            'email' => 'admin@tugawees.edu'
        ], [
            'first_name' => 'System',
            'last_name' => 'Admin',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id
        ]);

        // Registrar
        User::firstOrCreate([
            'email' => 'registrar@tugawees.edu'
        ], [
            'first_name' => 'Registrar',
            'last_name' => 'User',
            'username' => 'registrar',
            'password' => Hash::make('password'),
            'role_id' => $registrarRole->id
        ]);

        // Teacher
        $teacher = User::firstOrCreate([
            'email' => 'teacher@tugawees.edu'
        ], [
            'first_name' => 'Teacher',
            'last_name' => 'One',
            'username' => 'teacher',
            'password' => Hash::make('password'),
            'role_id' => $teacherRole->id
        ]);

        // Student
        $studentUser = User::firstOrCreate([
            'email' => 'pupil@tugawees.edu'
        ], [
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'username' => 'pupil',
            'password' => Hash::make('password'),
            'role_id' => $studentRole->id
        ]);

        Student::firstOrCreate([
            'user_id' => $studentUser->id
        ], [
            'lrn' => '123456789012',
            'birthdate' => '2015-01-15',
            'gender' => 'Male',
            'nationality' => 'Filipino',
            'religion' => 'Roman Catholic',
            'status' => 'active'
        ]);
    }
}
