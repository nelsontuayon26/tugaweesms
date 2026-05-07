<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HelpController extends Controller
{
  public function index()
{
    $student = Auth::user()->student;

    return view('student.help.index', compact('student'));
}
}