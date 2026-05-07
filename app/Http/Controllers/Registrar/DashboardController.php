<?php

namespace App\Http\Controllers\Registrar;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = \App\Models\Student::count();
        $totalTeachers = \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'teacher'))->count();
        $totalSections = \App\Models\Section::count();

        return view('registrar.dashboard', compact('totalStudents', 'totalTeachers', 'totalSections'));
    }
}
