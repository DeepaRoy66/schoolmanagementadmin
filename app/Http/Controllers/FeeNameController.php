<?php

namespace App\Http\Controllers;

use App\Models\FeeGroup;
use App\Models\FeeName;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FeeNameController extends Controller
{
    public function index(Request $request): View
    {
        $query = FeeName::with('feeGroup')->latest();

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $feeNames = $query->paginate(15)->withQueryString();

        return view('school-admin.fee-names.index', compact('feeNames'));
    }

    public function create(): View
    {
        $feeGroups = FeeGroup::where('is_active', true)->orderBy('name')->get();

        return view('school-admin.fee-names.create', compact('feeGroups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fee_group_id' => 'required|exists:fee_groups,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'fee_type' => 'required|in:compulsory_regular,extra_miscellaneous,optional',
            'is_taxable' => 'nullable|boolean',
            'discount_applicable' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $schoolId = auth()->user()->school_id;

        $ownGroup = FeeGroup::where('id', $validated['fee_group_id'])->where('school_id', $schoolId)->exists();
        if (!$ownGroup) {
            return redirect()->back()->withErrors(['fee_group_id' => 'Invalid fee group selected.']);
        }

        $validated['school_id'] = $schoolId;
        $validated['is_taxable'] = $request->has('is_taxable');
        $validated['discount_applicable'] = $request->has('discount_applicable');
        $validated['is_active'] = $request->has('is_active');

        FeeName::create($validated);

        return redirect()->route('school-admin.fee-names.index')
            ->with('status', 'Fee name added successfully.');
    }

    public function edit(FeeName $feeName): View
    {
        $feeGroups = FeeGroup::where('is_active', true)->orderBy('name')->get();

        return view('school-admin.fee-names.edit', compact('feeName', 'feeGroups'));
    }

    public function update(Request $request, FeeName $feeName): RedirectResponse
    {
        $validated = $request->validate([
            'fee_group_id' => 'required|exists:fee_groups,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'fee_type' => 'required|in:compulsory_regular,extra_miscellaneous,optional',
            'is_taxable' => 'nullable|boolean',
            'discount_applicable' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $ownGroup = FeeGroup::where('id', $validated['fee_group_id'])
            ->where('school_id', auth()->user()->school_id)->exists();
        if (!$ownGroup) {
            return redirect()->back()->withErrors(['fee_group_id' => 'Invalid fee group selected.']);
        }

        $validated['is_taxable'] = $request->has('is_taxable');
        $validated['discount_applicable'] = $request->has('discount_applicable');
        $validated['is_active'] = $request->has('is_active');

        $feeName->update($validated);

        return redirect()->route('school-admin.fee-names.index')
            ->with('status', 'Fee name updated successfully.');
    }

    public function destroy(FeeName $feeName): RedirectResponse
    {
        $feeName->delete();

        return redirect()->route('school-admin.fee-names.index')
            ->with('status', 'Fee name deleted.');
    }
}