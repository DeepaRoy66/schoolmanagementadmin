<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClassController extends Controller
{
    public function index(): View
    {
        $classes = SchoolClass::with('sections')->orderBy('name')->get();

        return view('school-admin.classes.index', compact('classes'));
    }

    public function create(): View
    {
        $sections = Section::orderBy('name')->get();

        return view('school-admin.classes.create', compact('sections'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'section_ids' => 'nullable|array',
            'section_ids.*' => 'exists:sections,id',
        ]);

        $class = SchoolClass::create([
            'school_id' => auth()->user()->school_id,
            'name' => $validated['name'],
        ]);

        if (!empty($validated['section_ids'])) {
            $class->sections()->sync($validated['section_ids']);
        }

        return redirect()->route('school-admin.classes.index')
            ->with('status', 'Class added successfully.');
    }

    public function destroy(SchoolClass $class): RedirectResponse
    {
        $class->delete();

        return redirect()->route('school-admin.classes.index')
            ->with('status', 'Class deleted.');
    }
}