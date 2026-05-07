<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index()
    {
        $events = Event::orderBy('date', 'asc')->paginate(20);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        // Find school year based on event date
        $eventDate = $request->date;
        $schoolYear = SchoolYear::where('start_date', '<=', $eventDate)
            ->where('end_date', '>=', $eventDate)
            ->first();

        $validated['school_year_id'] = $schoolYear?->id;
        $validated['created_by'] = Auth::id();

        $event = Event::create($validated);

        // Notify all teachers and students about the new event
        try {
            $teacherUserIds = \App\Models\User::whereHas('role', function($q) {
                $q->whereRaw('LOWER(name) = ?', ['teacher']);
            })->pluck('id')->toArray();

            $studentUserIds = \App\Models\User::whereHas('role', function($q) {
                $q->whereRaw('LOWER(name) = ?', ['student']);
            })->pluck('id')->toArray();

            $allUserIds = array_merge($teacherUserIds, $studentUserIds);
            // Exclude the creator from receiving their own notification
            $allUserIds = array_diff($allUserIds, [Auth::id()]);

            if (!empty($allUserIds)) {
                \App\Services\NotificationService::notifyMany(
                    $allUserIds,
                    'event',
                    "New School Event: {$event->title}",
                    "A new school event has been scheduled on {$event->date->format('F j, Y')}. " . ($event->description ? strip_tags($event->description) : ''),
                    [
                        'url' => route('student.events.show', $event),
                        'event_id' => $event->id,
                        'event_date' => $event->date->toDateString(),
                    ]
                );
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send event notifications: ' . $e->getMessage());
        }

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);

        // Find school year based on event date
        $eventDate = $request->date;
        $schoolYear = SchoolYear::where('start_date', '<=', $eventDate)
            ->where('end_date', '>=', $eventDate)
            ->first();

        $validated['school_year_id'] = $schoolYear?->id;

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified event.
     */
    public function destroy($id, Request $request)
    {
        $event = Event::find($id);
        
        if (!$event) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Event not found.'], 404);
            }
            return redirect()->route('admin.events.index')
                ->with('error', 'Event not found or has been deleted.');
        }
        
        $event->delete();

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Event deleted successfully.']);
        }

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }
}
