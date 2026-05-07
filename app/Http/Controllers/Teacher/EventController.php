<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of events for teachers.
     */
    public function index()
    {
        // Show all school events regardless of school year
        $events = Event::orderBy('date', 'asc')->get();
            
        return view('teacher.events.index', compact('events'));
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        return view('teacher.events.show', compact('event'));
    }
}
