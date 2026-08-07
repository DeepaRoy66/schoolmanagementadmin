<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SectionController extends Controller
{
    public function index(Request $request): View
    {
        $sections = Section::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

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

    public function edit(Section $section): View
    {
        return view('school-admin.sections.edit', compact('section'));
    }

    public function update(Request $request, Section $section): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $section->update([
            'name' => $validated['name'],
        ]);

        return redirect()->route('school-admin.sections.index')
            ->with('status', 'Section updated successfully.');
    }

    public function destroy(Section $section): RedirectResponse
    {
        $section->delete();

        return redirect()->route('school-admin.sections.index')
            ->with('status', 'Section deleted.');
    }
}