<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarEventController extends Controller
{
    public function index(Request $request): View
    {
        $events = CalendarEvent::when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->orderBy('start_date')
            ->paginate(20)
            ->withQueryString();

        return view('school-admin.calendar-events.index', compact('events'));
    }

    public function create(): View
    {
        return view('school-admin.calendar-events.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'start_date'    => ['required', 'date'],
            'end_date'      => ['nullable', 'date', 'after_or_equal:start_date'],
            'type'          => ['required', 'in:holiday,exam,event,meeting,other'],
            'custom_type'   => ['required_if:type,other', 'nullable', 'string', 'max:255'],
            'is_recurring'  => ['nullable', 'boolean'],
        ]);

        CalendarEvent::create([
            'school_id'     => auth()->user()->school_id,
            'title'         => $validated['title'],
            'description'   => $validated['description'] ?? null,
            'start_date'    => $validated['start_date'],
            'end_date'      => $validated['end_date'] ?? $validated['start_date'],
            'type'          => $validated['type'],
            'custom_type'   => $validated['type'] === 'other' ? $validated['custom_type'] : null,
            'is_recurring'  => $request->boolean('is_recurring'),
            'created_by'    => auth()->id(),
        ]);

        return redirect()
            ->route('school-admin.calendar-events.index')
            ->with('success', 'Event added successfully.');
    }

    public function edit(CalendarEvent $calendarEvent): View
    {
        return view('school-admin.calendar-events.edit', compact('calendarEvent'));
    }

    public function update(Request $request, CalendarEvent $calendarEvent): RedirectResponse
    {
        $validated = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'start_date'    => ['required', 'date'],
            'end_date'      => ['nullable', 'date', 'after_or_equal:start_date'],
            'type'          => ['required', 'in:holiday,exam,event,meeting,other'],
            'custom_type'   => ['required_if:type,other', 'nullable', 'string', 'max:255'],
            'is_recurring'  => ['nullable', 'boolean'],
        ]);

        $calendarEvent->update([
            'title'         => $validated['title'],
            'description'   => $validated['description'] ?? null,
            'start_date'    => $validated['start_date'],
            'end_date'      => $validated['end_date'] ?? $validated['start_date'],
            'type'          => $validated['type'],
            'custom_type'   => $validated['type'] === 'other' ? $validated['custom_type'] : null,
            'is_recurring'  => $request->boolean('is_recurring'),
        ]);

        return redirect()
            ->route('school-admin.calendar-events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(CalendarEvent $calendarEvent): RedirectResponse
    {
        $calendarEvent->delete();

        return redirect()
            ->route('school-admin.calendar-events.index')
            ->with('success', 'Event deleted successfully.');
    }
}