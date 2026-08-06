<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AcademicYearRun;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class AcademicYearRunController extends Controller
{
    public function index(Request $request)
    {
        $query = AcademicYearRun::with(['academicYear', 'schoolClass']);

        if ($request->filled('year')) {
            $query->whereHas('academicYear', function ($q) use ($request) {
                $q->where('year', 'like', '%' . $request->year . '%');
            });
        }

        $academicYearRuns = $query->orderByDesc('id')->paginate(10);

        return view('school-admin.academic-year-runs.index', compact('academicYearRuns'));
    }

    public function create()
    {
        $academicYears = AcademicYear::orderByDesc('year')->get();
        $classes = SchoolClass::orderBy('name')->get();

        return view('school-admin.academic-year-runs.create', compact('academicYears', 'classes'));
    }
public function store(Request $request)
{
    $validated = $request->validate([
        'academic_year_id' => 'required|exists:academic_years,id',
        'class_ids' => 'required|array|min:1',
        'class_ids.*' => 'exists:classes,id',
        'start_date' => 'nullable|array',
        'end_date' => 'nullable|array',
    ]);

    $alreadyExists = [];

    foreach ($validated['class_ids'] as $classId) {
        $exists = AcademicYearRun::where('academic_year_id', $validated['academic_year_id'])
            ->where('class_id', $classId)
            ->exists();

        if ($exists) {
            $className = \App\Models\SchoolClass::find($classId)?->name ?? $classId;
            $alreadyExists[] = $className;
            continue;
        }

        AcademicYearRun::create([
            'school_id' => auth()->user()->school_id,
            'academic_year_id' => $validated['academic_year_id'],
            'class_id' => $classId,
            'start_date' => $request->input("start_date.$classId"),
            'end_date' => $request->input("end_date.$classId"),
        ]);
    }

    if (count($alreadyExists) > 0) {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Already exists for: ' . implode(', ', $alreadyExists) . '. These were skipped.');
    }

    return redirect()
        ->route('school-admin.academic-year-runs.index')
        ->with('success', 'Academic Year Run added successfully.');
}

    public function edit(AcademicYearRun $academicYearRun)
    {
        $academicYears = AcademicYear::orderByDesc('year')->get();
        $classes = SchoolClass::orderBy('name')->get();

        return view('school-admin.academic-year-runs.edit', compact('academicYearRun', 'academicYears', 'classes'));
    }

   public function update(Request $request, AcademicYearRun $academicYearRun)
{
    $validated = $request->validate([
        'academic_year_id' => 'required|exists:academic_years,id',
        'class_id' => 'required|exists:classes,id',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
    ]);

    $academicYearRun->update($validated);

    return redirect()
        ->route('school-admin.academic-year-runs.index')
        ->with('success', 'Academic Year Run updated successfully.');
}

    public function destroy(AcademicYearRun $academicYearRun)
    {
        $academicYearRun->delete();

        return redirect()
            ->route('school-admin.academic-year-runs.index')
            ->with('success', 'Academic Year Run deleted successfully.');
    }
}