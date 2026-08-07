<?php

namespace App\Http\Controllers;

use App\Models\BillingPeriod;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BillingPeriodController extends Controller
{
    public function index(Request $request): View
    {
        $periods = BillingPeriod::when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('code', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('hierarchy')
            ->get();

        return view('school-admin.billing-periods.index', compact('periods'));
    }

    public function create(): View
    {
        return view('school-admin.billing-periods.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'hierarchy' => 'required|integer|min:0|unique:billing_periods,hierarchy',
            'quantity' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['school_id'] = auth()->user()->school_id;
        $validated['quantity'] = $validated['quantity'] ?? 1.00;
        $validated['is_active'] = $request->has('is_active');

        BillingPeriod::create($validated);

        return redirect()->route('school-admin.billing-periods.index')
            ->with('status', 'Billing period added successfully.');
    }

    public function edit(BillingPeriod $billingPeriod): View
    {
        return view('school-admin.billing-periods.edit', ['period' => $billingPeriod]);
    }

    public function update(Request $request, BillingPeriod $billingPeriod): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'hierarchy' => 'required|integer|min:0|unique:billing_periods,hierarchy,' . $billingPeriod->id,
            'quantity' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['quantity'] = $validated['quantity'] ?? 1.00;
        $validated['is_active'] = $request->has('is_active');

        $billingPeriod->update($validated);

        return redirect()->route('school-admin.billing-periods.index')
            ->with('status', 'Billing period updated successfully.');
    }

    public function destroy(BillingPeriod $billingPeriod): RedirectResponse
    {
        $billingPeriod->delete();

        return redirect()->route('school-admin.billing-periods.index')
            ->with('status', 'Billing period deleted.');
    }
}