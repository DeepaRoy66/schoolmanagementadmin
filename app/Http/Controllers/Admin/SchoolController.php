<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchoolController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $schools = School::query()
            ->when($status && in_array($status, ['active', 'trial', 'expired']), function ($query) use ($status) {
                $query->where('license_status', $status);
            })
            ->latest()
            ->paginate(10)
            ->appends($request->only('status'));

        $counts = [
            'all'     => School::count(),
            'active'  => School::where('license_status', 'active')->count(),
            'trial'   => School::where('license_status', 'trial')->count(),
            'expired' => School::where('license_status', 'expired')->count(),
        ];

        return view('admin.schools.index', compact('schools', 'counts'));
    }

    public function create(): View
    {
        return view('admin.schools.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'school_code' => 'nullable|string|max:50|unique:schools,school_code',
            'address' => 'nullable|string|max:255',
            'license_status' => 'required|in:active,expired,trial',
            'license_start' => 'nullable|date',
            'license_expiry' => 'nullable|date',
            'trial_ends_at' => 'nullable|date',
        ]);

        // If the admin left the code blank, auto-generate one (e.g. "SCH-0001").
        if (empty($validated['school_code'])) {
            $validated['school_code'] = School::generateSchoolCode();
        }

        School::create($validated);

        return redirect()->route('admin.schools.index')
            ->with('status', 'School successfully created.');
    }

    public function show(School $school): View
    {
        return view('admin.schools.show', compact('school'));
    }

    public function edit(School $school): View
    {
        return view('admin.schools.edit', compact('school'));
    }

    public function update(Request $request, School $school): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'school_code' => 'nullable|string|max:50|unique:schools,school_code,' . $school->id,
            'address' => 'nullable|string|max:255',
            'license_status' => 'required|in:active,expired,trial',
            'license_start' => 'nullable|date',
            'license_expiry' => 'nullable|date',
            'trial_ends_at' => 'nullable|date',
        ]);

        $school->update($validated);

        return redirect()->route('admin.schools.index')
            ->with('status', 'School successfully updated.');
    }

    public function destroy(School $school): RedirectResponse
    {
        $school->delete();

        return redirect()->route('admin.schools.index')
            ->with('status', 'School deleted.');
    }

    /**
     * License renew garne - naya expiry date set garne, status active banaune
     */
    public function renew(Request $request, School $school): RedirectResponse
    {
        $validated = $request->validate([
            'license_expiry' => 'required|date|after:today',
        ]);

        $school->update([
            'license_expiry' => $validated['license_expiry'],
            'license_status' => 'active',
        ]);

        return redirect()->route('admin.schools.index')
            ->with('status', "License for {$school->name} renewed successfully.");
    }
}