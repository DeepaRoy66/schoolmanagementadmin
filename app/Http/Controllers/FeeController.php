<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FeeController extends Controller
{
    public function index(): View
    {
        $fees = Fee::with('student')->latest()->paginate(15);

        return view('school-admin.fees.index', compact('fees'));
    }

    public function create(): View
    {
        $students = Student::orderBy('name')->get();

        return view('school-admin.fees.create', compact('students'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
        ]);

        $validated['school_id'] = auth()->user()->school_id;
        $validated['status'] = 'unpaid';

        Fee::create($validated);

        return redirect()->route('school-admin.fees.index')
            ->with('status', 'Fee record created.');
    }

    /**
     * Payment update garne - kati paisa tirey bhanera
     */
    public function updatePayment(Request $request, Fee $fee): RedirectResponse
    {
        $validated = $request->validate([
            'paid_amount' => 'required|numeric|min:0|max:' . $fee->amount,
        ]);

        $status = 'unpaid';
        if ($validated['paid_amount'] >= $fee->amount) {
            $status = 'paid';
        } elseif ($validated['paid_amount'] > 0) {
            $status = 'partial';
        }

        $fee->update([
            'paid_amount' => $validated['paid_amount'],
            'status' => $status,
            'paid_date' => $status === 'paid' ? now() : null,
        ]);

        return redirect()->route('school-admin.fees.index')
            ->with('status', 'Payment updated.');
    }

    public function destroy(Fee $fee): RedirectResponse
    {
        $fee->delete();

        return redirect()->route('school-admin.fees.index')
            ->with('status', 'Fee record deleted.');
    }
}