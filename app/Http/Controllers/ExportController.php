<?php

namespace App\Http\Controllers;

use App\Models\User;

class ExportController extends Controller
{
    public function teacher($id)
    {
        $teacher = User::whereHas('role', function ($q) {
            $q->where('name', 'teacher');
        })
        ->with(['sections.students'])
        ->findOrFail($id);

        $csv = "Teacher,Section,Student\n";

        foreach ($teacher->sections as $section) {
            if ($section->students->isEmpty()) {
                $csv .= "{$teacher->name},{$section->name},No Students\n";
            } else {
                foreach ($section->students as $student) {
                    $csv .= "{$teacher->name},{$section->name},{$student->first_name} {$student->last_name}\n";
                }
            }
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename=teacher_'.$id.'_sections.csv');
    }
}
