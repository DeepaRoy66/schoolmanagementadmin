<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CalendarEventController extends Controller
{
    /**
     * List events. Optional filters: month, year, type.
     * GET /api/calendar-events?month=8&year=2026&type=holiday
     */
    public function index(Request $request): JsonResponse
    {
        $query = CalendarEvent::query()->orderBy('start_date');

        if ($request->filled('year')) {
            $query->whereYear('start_date', $request->query('year'));
        }

        if ($request->filled('month')) {
            $query->whereMonth('start_date', $request->query('month'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->query('type'));
        }

        $events = $query->get();

        return response()->json($events);
    }

    /**
     * Create a new event (holiday/exam/other).
     * Only school_admin should be allowed to create — checked below.
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'school_admin') {
            return response()->json(['message' => 'You are not authorized to create calendar events.'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'type' => 'required|in:holiday,event,exam,meeting,other',
            'custom_type' => 'required_if:type,other|nullable|string|max:255',
            'is_recurring' => 'boolean',
        ]);

        $event = CalendarEvent::create([
            'school_id' => $user->school_id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'type' => $validated['type'],
            'custom_type' => $validated['type'] === 'other' ? ($validated['custom_type'] ?? null) : null,
            'is_recurring' => $validated['is_recurring'] ?? false,
            'created_by' => $user->id,
        ]);

        return response()->json([
            'message' => 'Event created successfully.',
            'event' => $event,
        ], 201);
    }

    /**
     * Update an existing event.
     */
    public function update(Request $request, CalendarEvent $calendarEvent): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'school_admin') {
            return response()->json(['message' => 'You are not authorized to edit calendar events.'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'type' => 'sometimes|required|in:holiday,event,exam,meeting,other',
            'custom_type' => 'required_if:type,other|nullable|string|max:255',
            'is_recurring' => 'boolean',
        ]);

        // Determine the effective type (new value if provided, else existing) to decide custom_type.
        $effectiveType = $validated['type'] ?? $calendarEvent->type;

        if ($effectiveType === 'other') {
            $validated['custom_type'] = $validated['custom_type'] ?? $calendarEvent->custom_type;
        } else {
            $validated['custom_type'] = null;
        }

        $calendarEvent->update($validated);

        return response()->json([
            'message' => 'Event updated successfully.',
            'event' => $calendarEvent,
        ]);
    }

    /**
     * Delete an event.
     */
    public function destroy(Request $request, CalendarEvent $calendarEvent): JsonResponse
    {
        $user = $request->user();

        if ($user->role !== 'school_admin') {
            return response()->json(['message' => 'You are not authorized to delete calendar events.'], 403);
        }

        $calendarEvent->delete();

        return response()->json(['message' => 'Event deleted successfully.']);
    }
}