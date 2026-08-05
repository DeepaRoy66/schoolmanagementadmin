<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index(Request $request)
    {
        $query = AcademicYear::where('school_id', auth()->user()->school_id);

        if ($request->filled('year')) {
            $query->where('year', 'like', '%' . $request->year . '%');
        }

        $academicYears = $query->orderByDesc('year')->paginate(10);

        return view('school-admin.academic-years.index', compact('academicYears'));
    }

    public function create()
    {
        $academicYear = new AcademicYear();
        return view('school-admin.academic-years.create', compact('academicYear'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|string|max:20',
            'is_running' => 'nullable|boolean',
        ]);

        $validated['school_id'] = auth()->user()->school_id;
        $validated['is_running'] = $request->boolean('is_running');

        AcademicYear::create($validated);

        return redirect()
            ->route('school-admin.academic-years.index')
            ->with('success', 'Academic Year added successfully.');
    }

    public function edit(AcademicYear $academicYear)
    {
        return view('school-admin.academic-years.create', compact('academicYear'));
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'year' => 'required|string|max:20',
            'is_running' => 'nullable|boolean',
        ]);

        $validated['is_running'] = $request->boolean('is_running');

        $academicYear->update($validated);

        return redirect()
            ->route('school-admin.academic-years.index')
            ->with('success', 'Academic Year updated successfully.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();

        return redirect()
            ->route('school-admin.academic-years.index')
            ->with('success', 'Academic Year deleted successfully.');
    }
}