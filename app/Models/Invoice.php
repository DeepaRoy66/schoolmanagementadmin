<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'student_id',
        'billing_period_id',
        'invoice_no',
        'total_amount',
        'billing_date',
        'due_date',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'billing_date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function billingPeriod()
    {
        return $this->belongsTo(BillingPeriod::class, 'billing_period_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // line items
    public function studentFees()
    {
        return $this->hasMany(StudentFee::class, 'invoice_id');
    }
}