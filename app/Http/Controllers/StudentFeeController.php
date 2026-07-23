<?php

namespace App\Http\Controllers;

use App\Models\StudentFee;
use App\Models\Student;
use App\Models\FeeCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentFeeController extends Controller
{
    /**
     * Display a listing of student fees for the logged-in admin's school.
     */
    public function index(Request $request)
    {
        $query = StudentFee::with(['student', 'feeCategory'])
            ->where('school_id', auth()->user()->school_id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $studentFees = $query->orderByDesc('due_date')->paginate(20);

        $students = Student::where('school_id', auth()->user()->school_id)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('school-admin.student-fees.index', compact('studentFees', 'students'));
    }

    /**
     * Show the form for creating a new student fee.
     */
    public function create()
    {
        $students = Student::where('school_id', auth()->user()->school_id)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $feeCategories = FeeCategory::where('school_id', auth()->user()->school_id)
            ->orderBy('name')
            ->get();

        return view('school-admin.student-fees.create', compact('students', 'feeCategories'));
    }

    /**
     * Store a newly created student fee.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                Rule::exists('students', 'id')->where('school_id', auth()->user()->school_id),
            ],
            'fee_category_id' => [
                'required',
                Rule::exists('fee_categories', 'id')->where('school_id', auth()->user()->school_id),
            ],
            'amount' => ['required', 'numeric', 'min:0'],
            'due_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        StudentFee::create([
            'school_id' => auth()->user()->school_id,
            'student_id' => $validated['student_id'],
            'fee_category_id' => $validated['fee_category_id'],
            'amount' => $validated['amount'],
            'paid_amount' => 0,
            'due_date' => $validated['due_date'],
            'status' => 'unpaid',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('school-admin.student-fees.index')
            ->with('success', 'Student fee assigned successfully.');
    }

    /**
     * Show the form for editing the specified student fee.
     */
    public function edit(StudentFee $studentFee)
    {
        abort_unless($studentFee->school_id === auth()->user()->school_id, 403);

        $students = Student::where('school_id', auth()->user()->school_id)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $feeCategories = FeeCategory::where('school_id', auth()->user()->school_id)
            ->orderBy('name')
            ->get();

        return view('school-admin.student-fees.edit', compact('studentFee', 'students', 'feeCategories'));
    }

    /**
     * Update the specified student fee.
     */
    public function update(Request $request, StudentFee $studentFee)
    {
        abort_unless($studentFee->school_id === auth()->user()->school_id, 403);

        $validated = $request->validate([
            'student_id' => [
                'required',
                Rule::exists('students', 'id')->where('school_id', auth()->user()->school_id),
            ],
            'fee_category_id' => [
                'required',
                Rule::exists('fee_categories', 'id')->where('school_id', auth()->user()->school_id),
            ],
            'amount' => ['required', 'numeric', 'min:0'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'due_date' => ['required', 'date'],
            'status' => ['required', Rule::in(['unpaid', 'partial', 'paid', 'overdue'])],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $studentFee->update([
            'student_id' => $validated['student_id'],
            'fee_category_id' => $validated['fee_category_id'],
            'amount' => $validated['amount'],
            'paid_amount' => $validated['paid_amount'],
            'due_date' => $validated['due_date'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('school-admin.student-fees.index')
            ->with('success', 'Student fee updated successfully.');
    }

    public function reports()
    {
        $schoolId = auth()->user()->school_id;

        $totalFees = StudentFee::where('school_id', $schoolId)->sum('amount');
        $totalCollected = StudentFee::where('school_id', $schoolId)->sum('paid_amount');
        $totalPending = $totalFees - $totalCollected;

        $statusCounts = StudentFee::where('school_id', $schoolId)
            ->selectRaw('status, count(*) as total, sum(amount) as total_amount, sum(paid_amount) as total_paid')
            ->groupBy('status')
            ->get();

        $categoryBreakdown = StudentFee::with('feeCategory')
            ->where('school_id', $schoolId)
            ->get()
            ->groupBy(fn ($fee) => $fee->feeCategory->name ?? 'Uncategorized')
            ->map(function ($items) {
                return [
                    'total_amount' => $items->sum('amount'),
                    'total_paid' => $items->sum('paid_amount'),
                ];
            });

        $recentFees = StudentFee::with(['student', 'feeCategory'])
            ->where('school_id', $schoolId)
            ->latest()
            ->take(10)
            ->get();

        return view('school-admin.student-fees.reports', compact(
            'totalFees',
            'totalCollected',
            'totalPending',
            'statusCounts',
            'categoryBreakdown',
            'recentFees'
        ));
    }

    /**
     * Remove the specified student fee — blocked if any payment
     * has already been recorded against it.
     */
    public function destroy(StudentFee $studentFee)
    {
        abort_unless($studentFee->school_id === auth()->user()->school_id, 403);

        if ($studentFee->feePayments()->exists()) {
            return redirect()
                ->route('school-admin.student-fees.index')
                ->with('error', 'Cannot delete: payments have already been recorded against this fee.');
        }

        $studentFee->delete();

        return redirect()
            ->route('school-admin.student-fees.index')
            ->with('success', 'Student fee removed successfully.');
    }
}