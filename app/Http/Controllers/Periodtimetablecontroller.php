<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;

class PeriodTimetableController extends Controller
{
    public function index(Request $request)
    {
        $periods = Period::query()
            ->where('school_id', auth()->user()->school_id)
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->orderBy('sort_order')
            ->orderBy('start_time')
            ->paginate(20)
            ->withQueryString();

        return view('school-admin.period-timetable.index', [
            'periods' => $periods,
            'activeTab' => 'period-info',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'code'       => 'required|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'is_break'   => 'nullable|boolean',
            'is_active'  => 'nullable|boolean',
        ]);

        Period::create([
            'school_id'  => auth()->user()->school_id,
            'name'       => $validated['name'],
            'code'       => $validated['code'],
            'start_time' => $validated['start_time'],
            'end_time'   => $validated['end_time'],
            'is_break'   => $request->boolean('is_break'),
            'is_active'  => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('school-admin.period-timetable.index')
            ->with('success', 'Period created successfully.');
    }

    public function edit(Period $period)
    {
        return view('school-admin.period-timetable.edit', [
            'period' => $period,
            'activeTab' => 'period-info',
        ]);
    }

    public function update(Request $request, Period $period)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'code'       => 'required|string|max:50',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'is_break'   => 'nullable|boolean',
            'is_active'  => 'nullable|boolean',
        ]);

        $period->update([
            'name'       => $validated['name'],
            'code'       => $validated['code'],
            'start_time' => $validated['start_time'],
            'end_time'   => $validated['end_time'],
            'is_break'   => $request->boolean('is_break'),
            'is_active'  => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('school-admin.period-timetable.index')
            ->with('success', 'Period updated successfully.');
    }

    public function destroy(Period $period)
    {
        $period->delete();

        return redirect()
            ->route('school-admin.period-timetable.index')
            ->with('success', 'Period deleted successfully.');
    }
}