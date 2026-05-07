<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;

class ReportCardController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        $grades = Grade::where('student_id', $student->id)
            ->with('subject')
            ->get()
            ->groupBy('quarter');

        $report = [];

        foreach ($grades as $quarter => $records) {
            $average = round($records->avg('grade'), 2);
            $remarks = $average >= 75 ? 'PASSED' : 'FAILED';

            $report[$quarter] = [
                'grades' => $records,
                'average' => $average,
                'remarks' => $remarks
            ];
        }

        return view('student.report-card', compact('student', 'report'));
    }
}
