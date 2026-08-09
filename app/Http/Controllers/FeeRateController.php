<?php

namespace App\Http\Controllers;

use App\Models\BillingPeriod;
use App\Models\FeeName;
use App\Models\FeeRate;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FeeRateController extends Controller
{
    public function index(Request $request): View
    {
        $schoolId = auth()->user()->school_id;

        $feeRates = FeeRate::with(['feeName', 'schoolClass', 'billingPeriod'])
            ->where('school_id', $schoolId)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->whereHas('feeName', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })->orWhereHas('schoolClass', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('school-admin.fee-rates.index', compact('feeRates'));
    }

    public function create(): View
    {
        $feeNames = FeeName::where('is_active', true)->orderBy('name')->get();
        $classes = SchoolClass::with('sections')->orderBy('name')->get();
        $billingPeriods = BillingPeriod::where('is_active', true)->orderBy('hierarchy')->get();

        return view('school-admin.fee-rates.create', compact('feeNames', 'classes', 'billingPeriods'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fee_name_id' => 'required|exists:fee_names,id',
            'class_id' => 'required|exists:classes,id',
            'billing_period_id' => 'nullable|exists:billing_periods,id',
            'amount' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $schoolId = auth()->user()->school_id;

        $ownFeeName = FeeName::where('id', $validated['fee_name_id'])->where('school_id', $schoolId)->exists();
        $ownClass = SchoolClass::where('id', $validated['class_id'])->where('school_id', $schoolId)->exists();
        $ownPeriod = empty($validated['billing_period_id']) ||
            BillingPeriod::where('id', $validated['billing_period_id'])->where('school_id', $schoolId)->exists();

        if (!$ownFeeName || !$ownClass || !$ownPeriod) {
            return redirect()->back()->withErrors(['fee_name_id' => 'Invalid selection.'])->withInput();
        }

        $exists = FeeRate::where('fee_name_id', $validated['fee_name_id'])
            ->where('class_id', $validated['class_id'])
            ->where('billing_period_id', $validated['billing_period_id'] ?? null)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['amount' => 'A rate for this Fee Name + Class + Billing Period combination already exists.'])
                ->withInput();
        }

        $validated['school_id'] = $schoolId;
        $validated['is_active'] = $request->has('is_active');

        FeeRate::create($validated);

        return redirect()->route('school-admin.fee-rates.index')
            ->with('status', 'Fee rate added successfully.');
    }

    public function edit(FeeRate $feeRate): View
    {
        $feeNames = FeeName::where('is_active', true)->orderBy('name')->get();
        $classes = SchoolClass::with('sections')->orderBy('name')->get();
        $billingPeriods = BillingPeriod::where('is_active', true)->orderBy('hierarchy')->get();

        return view('school-admin.fee-rates.edit', compact('feeRate', 'feeNames', 'classes', 'billingPeriods'));
    }

    public function update(Request $request, FeeRate $feeRate): RedirectResponse
    {
        $validated = $request->validate([
            'fee_name_id' => 'required|exists:fee_names,id',
            'class_id' => 'required|exists:classes,id',
            'billing_period_id' => 'nullable|exists:billing_periods,id',
            'amount' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $schoolId = auth()->user()->school_id;

        $ownFeeName = FeeName::where('id', $validated['fee_name_id'])->where('school_id', $schoolId)->exists();
        $ownClass = SchoolClass::where('id', $validated['class_id'])->where('school_id', $schoolId)->exists();
        $ownPeriod = empty($validated['billing_period_id']) ||
            BillingPeriod::where('id', $validated['billing_period_id'])->where('school_id', $schoolId)->exists();

        if (!$ownFeeName || !$ownClass || !$ownPeriod) {
            return redirect()->back()->withErrors(['fee_name_id' => 'Invalid selection.'])->withInput();
        }

        $exists = FeeRate::where('fee_name_id', $validated['fee_name_id'])
            ->where('class_id', $validated['class_id'])
            ->where('billing_period_id', $validated['billing_period_id'] ?? null)
            ->where('id', '!=', $feeRate->id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['amount' => 'A rate for this Fee Name + Class + Billing Period combination already exists.'])
                ->withInput();
        }

        $validated['is_active'] = $request->has('is_active');

        $feeRate->update($validated);

        return redirect()->route('school-admin.fee-rates.index')
            ->with('status', 'Fee rate updated successfully.');
    }

    public function destroy(FeeRate $feeRate): RedirectResponse
    {
        abort_if($feeRate->school_id !== auth()->user()->school_id, 403);

        $feeRate->delete();

        return redirect()->route('school-admin.fee-rates.index')
            ->with('status', 'Fee rate deleted.');
    }
}