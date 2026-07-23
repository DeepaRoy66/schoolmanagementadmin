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
        $students = Student::orderBy('first_name')->orderBy('last_name')->get();

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

        $student = Student::where('id', $validated['student_id'])
            ->where('school_id', auth()->user()->school_id)
            ->first();

        if (!$student) {
            return redirect()->back()
                ->withErrors(['student_id' => 'Invalid student selected.'])
                ->withInput();
        }

        $validated['school_id'] = auth()->user()->school_id;
        $validated['status'] = 'unpaid';

        Fee::create($validated);

        return redirect()->route('school-admin.fees.index')
            ->with('status', 'Fee record created.');
    }

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

    public function reports(): View
    {
        $schoolId = auth()->user()->school_id;

        $totalFees = Fee::where('school_id', $schoolId)->sum('amount');
        $totalCollected = Fee::where('school_id', $schoolId)->sum('paid_amount');
        $totalPending = $totalFees - $totalCollected;

        $statusCounts = Fee::where('school_id', $schoolId)
            ->selectRaw('status, count(*) as total, sum(amount) as total_amount, sum(paid_amount) as total_paid')
            ->groupBy('status')
            ->get();

        $recentFees = Fee::with('student')
            ->where('school_id', $schoolId)
            ->latest()
            ->take(10)
            ->get();

        return view('school-admin.fees.reports', compact(
            'totalFees',
            'totalCollected',
            'totalPending',
            'statusCounts',
            'recentFees'
        ));
    }
}