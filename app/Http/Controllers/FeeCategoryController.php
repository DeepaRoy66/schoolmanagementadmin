<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FeeCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeeCategoryController extends Controller
{
    
    public function index()
    {
        $categories = FeeCategory::where('school_id', auth()->user()->school_id)
            ->orderBy('name')
            ->get();

        return view('school-admin.fee-categories.index', compact('categories'));
    }

    
    public function create()
    {
        return view('school-admin.fee-categories.create');
    }

   
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

   
    public function update(Request $request, FeeCategory $feeCategory)
    {
        
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