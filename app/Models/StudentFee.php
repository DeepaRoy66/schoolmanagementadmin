<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'student_id',
        'fee_name_id',
        'billing_period_id',
        'fee_category_id',
        'amount',
        'paid_amount',
        'billing_date',
        'due_date',
        'status',
        'notes',
        'created_by',
        'invoice_id',
    ];

    protected $casts = [
        'billing_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeCategory()
    {
        return $this->belongsTo(FeeCategory::class);
    }

    public function feeName()
    {
        return $this->belongsTo(FeeName::class, 'fee_name_id');
    }

    public function billingPeriod()
    {
        return $this->belongsTo(BillingPeriod::class, 'billing_period_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function feePayments()
    {
        return $this->hasMany(FeePayment::class);
    }

    // Who assigned this fee (used to show "User Name" in the expand row)
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function invoice()
    {
        return $this->belongsTo(\App\Models\Invoice::class, 'invoice_id');
    }
}