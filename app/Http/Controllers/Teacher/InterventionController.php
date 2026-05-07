<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Intervention;

class InterventionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'subject_id' => 'required',
            'schedule' => 'required',
            'description' => 'required',
        ]);

        Intervention::create([
            'student_id' => $request->student_id,
            'subject_id' => $request->subject_id,
            'intervention_type' => $request->intervention_type,
            'schedule' => $request->schedule,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Intervention saved successfully'
        ]);
    }
}