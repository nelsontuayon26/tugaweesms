<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

class ClassmatesController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        // Get classmates in the same section
        $classmates = $student->section ? $student->section->students()->where('id', '!=', $student->id)->get() : collect();

        return view('student.classmates.index', compact('classmates'));
    }
}