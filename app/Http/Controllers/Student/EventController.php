<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of events for students.
     */
    public function index()
    {
        // Show all school events regardless of school year
        $events = Event::orderBy('date', 'asc')->get();
            
        return view('student.events.index', compact('events'));
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        return view('student.events.show', compact('event'));
    }
}
