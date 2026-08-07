<?php

namespace App\Http\Controllers;

use App\Models\FeeGroup;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FeeGroupController extends Controller
{
    public function index(Request $request): View
    {
        $feeGroups = FeeGroup::when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('code', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->get();

        return view('school-admin.fee-groups.index', compact('feeGroups'));
    }

    public function create(): View
    {
        return view('school-admin.fee-groups.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'remarks' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['school_id'] = auth()->user()->school_id;
        $validated['is_active'] = $request->has('is_active');

        FeeGroup::create($validated);

        return redirect()->route('school-admin.fee-groups.index')
            ->with('status', 'Fee group added successfully.');
    }

    public function edit(FeeGroup $feeGroup): View
    {
        return view('school-admin.fee-groups.edit', compact('feeGroup'));
    }

    public function update(Request $request, FeeGroup $feeGroup): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'remarks' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $feeGroup->update($validated);

        return redirect()->route('school-admin.fee-groups.index')
            ->with('status', 'Fee group updated successfully.');
    }

    public function destroy(FeeGroup $feeGroup): RedirectResponse
    {
        $feeGroup->delete();

        return redirect()->route('school-admin.fee-groups.index')
            ->with('status', 'Fee group deleted.');
    }
}