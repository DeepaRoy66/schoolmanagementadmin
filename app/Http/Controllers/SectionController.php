<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SectionController extends Controller
{
    public function index(): View
    {
        $sections = Section::orderBy('name')->get();

        return view('school-admin.sections.index', compact('sections'));
    }

    public function create(): View
    {
        return view('school-admin.sections.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Section::create([
            'school_id' => auth()->user()->school_id,
            'name' => $validated['name'],
        ]);

        return redirect()->route('school-admin.sections.index')
            ->with('status', 'Section added successfully.');
    }

    public function destroy(Section $section): RedirectResponse
    {
        $section->delete();

        return redirect()->route('school-admin.sections.index')
            ->with('status', 'Section deleted.');
    }
}