<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\StudentFee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillInvoicesFromStudentFees extends Command
{
    protected $signature = 'fees:backfill-invoices';
    protected $description = 'Create Invoice records for legacy student_fees rows (invoice_id IS NULL) by grouping student+billing_period+billing_date, then link them. Safe to re-run.';

    public function handle(): int
    {
        $orphanFees = StudentFee::whereNull('invoice_id')->get();

        if ($orphanFees->isEmpty()) {
            $this->info('Nothing to backfill — every student_fees row already has an invoice_id.');
            return self::SUCCESS;
        }

        $groups = $orphanFees->groupBy(
            fn ($fee) => $fee->student_id . '|' . $fee->billing_period_id . '|' . $fee->billing_date
        );

        $this->info("Found {$groups->count()} legacy invoice group(s) to backfill...");

        $created = 0;

        DB::transaction(function () use ($groups, &$created) {
            foreach ($groups as $group) {
                $first = $group->first();

               
                $billingDate = $first->billing_date ?? optional($first->created_at)->toDateString() ?? now()->toDateString();
                $dueDate = $first->due_date ?? $billingDate;

                $invoice = Invoice::create([
                    'school_id' => $first->school_id,
                    'student_id' => $first->student_id,
                    'billing_period_id' => $first->billing_period_id,
                    'invoice_no' => 'TEMP',
                    'total_amount' => $group->sum('amount'),
                    'billing_date' => $billingDate,
                    'due_date' => $dueDate,
                    'status' => $group->every(fn ($f) => $f->status !== 'void') ? 'unpaid' : 'void',
                    'notes' => $first->notes,
                    'created_by' => $first->created_by,
                ]);

                $invoice->invoice_no = 'INV-' . \Carbon\Carbon::parse($billingDate)->format('Y') . '-' . str_pad($invoice->id, 6, '0', STR_PAD_LEFT);
                $invoice->save();

                StudentFee::whereIn('id', $group->pluck('id'))->update(['invoice_id' => $invoice->id]);

                $created++;
            }
        });

        $this->info("Done. Created {$created} invoice(s) and linked all legacy student_fees rows.");

        return self::SUCCESS;
    }
}