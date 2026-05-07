<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        $achievementCount = Achievement::where('student_id', $student->id)->count();

        $achievements = Achievement::where('student_id', $student->id)->get();

        return view('student.achievements.index', compact('achievementCount', 'achievements'));
    }
}