<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TimetableController extends Controller
{
    public function index(): View
    {
        $timetables = Timetable::orderBy('class')
            ->orderByRaw("FIELD(day, 'Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday')")
            ->orderBy('period')
            ->paginate(15);

        return view('school-admin.timetables.index', compact('timetables'));
    }

    public function create(): View
    {
        $teachers = Teacher::orderBy('name')->get();

        return view('school-admin.timetables.create', compact('teachers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class' => 'required|string|max:255',
            'day' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'period' => 'required|string|max:50',
            'subject' => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:teachers,id',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
        ]);

        
        if (!empty($validated['teacher_id'])) {
            $ownTeacher = Teacher::where('id', $validated['teacher_id'])
                ->where('school_id', auth()->user()->school_id)
                ->exists();

            if (!$ownTeacher) {
                return redirect()->back()
                    ->withErrors(['teacher_id' => 'Invalid teacher selected.'])
                    ->withInput();
            }
        }

        $validated['school_id'] = auth()->user()->school_id;

        Timetable::create($validated);

        return redirect()->route('school-admin.timetables.index')
            ->with('status', 'Timetable entry added.');
    }

    public function destroy(Timetable $timetable): RedirectResponse
    {
        $timetable->delete();

        return redirect()->route('school-admin.timetables.index')
            ->with('status', 'Timetable entry deleted.');
    }
}