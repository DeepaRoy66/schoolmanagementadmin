<?php

namespace App\Http\Controllers;

use App\Models\EmergencyContact;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmergencyContactController extends Controller
{
    public function index(): View
    {
        $contacts = EmergencyContact::orderBy('sort_order')->orderBy('id')->get();

        return view('school-admin.emergency-contacts.index', compact('contacts'));
    }

    public function create(): View
    {
        return view('school-admin.emergency-contacts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        EmergencyContact::create([
            'school_id' => auth()->user()->school_id,
            'name' => $validated['name'],
            'designation' => $validated['designation'] ?? null,
            'phone' => $validated['phone'],
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('school-admin.emergency-contacts.index')
            ->with('status', 'Emergency contact added.');
    }

    public function edit(EmergencyContact $emergencyContact): View
    {
        return view('school-admin.emergency-contacts.edit', ['contact' => $emergencyContact]);
    }

    public function update(Request $request, EmergencyContact $emergencyContact): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $emergencyContact->update($validated);

        return redirect()->route('school-admin.emergency-contacts.index')
            ->with('status', 'Emergency contact updated.');
    }

    public function destroy(EmergencyContact $emergencyContact): RedirectResponse
    {
        $emergencyContact->delete();

        return redirect()->route('school-admin.emergency-contacts.index')
            ->with('status', 'Emergency contact removed.');
    }
}