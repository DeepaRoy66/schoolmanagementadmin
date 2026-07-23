<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FeeCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeeCategoryController extends Controller
{
    /**
     * Display a listing of fee categories for the logged-in admin's school.
     */
    public function index()
    {
        $categories = FeeCategory::where('school_id', auth()->user()->school_id)
            ->orderBy('name')
            ->get();

        return view('school-admin.fee-categories.index', compact('categories'));
    }

    /**
     * Store a newly created fee category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fee_categories')->where(
                    fn ($query) => $query->where('school_id', auth()->user()->school_id)
                ),
            ],
            'is_recurring' => ['required', 'boolean'],
            'recurring_interval' => ['nullable', 'required_if:is_recurring,1', Rule::in(['monthly', 'yearly'])],
        ]);

        FeeCategory::create([
            'school_id' => auth()->user()->school_id,
            'name' => $validated['name'],
            'is_recurring' => $validated['is_recurring'],
            'recurring_interval' => $validated['recurring_interval'] ?? null,
        ]);

        return redirect()
            ->route('school-admin.fee-categories.index')
            ->with('success', 'Fee category created successfully.');
    }

    /**
     * Update the specified fee category.
     */
    public function update(Request $request, FeeCategory $feeCategory)
    {
        // Ensure the category belongs to the logged-in admin's school
        abort_unless($feeCategory->school_id === auth()->user()->school_id, 403);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fee_categories')
                    ->where(fn ($query) => $query->where('school_id', auth()->user()->school_id))
                    ->ignore($feeCategory->id),
            ],
            'is_recurring' => ['required', 'boolean'],
            'recurring_interval' => ['nullable', 'required_if:is_recurring,1', Rule::in(['monthly', 'yearly'])],
        ]);

        $feeCategory->update([
            'name' => $validated['name'],
            'is_recurring' => $validated['is_recurring'],
            'recurring_interval' => $validated['recurring_interval'] ?? null,
        ]);

        return redirect()
            ->route('school-admin.fee-categories.index')
            ->with('success', 'Fee category updated successfully.');
    }

    /**
     * Remove the specified fee category — blocked if it is already
     * assigned to any student to protect existing records.
     */
    public function destroy(FeeCategory $feeCategory)
    {
        abort_unless($feeCategory->school_id === auth()->user()->school_id, 403);

        if ($feeCategory->studentFees()->exists()) {
            return redirect()
                ->route('school-admin.fee-categories.index')
                ->with('error', 'Cannot delete: this category is already assigned to one or more students.');
        }

        $feeCategory->delete();

        return redirect()
            ->route('school-admin.fee-categories.index')
            ->with('success', 'Fee category deleted successfully.');
    }
}