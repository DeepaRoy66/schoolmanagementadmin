<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    protected $fillable = [
        'student_fee_id',
        'school_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference_no',
        'notes',
    ];

    public function studentFee()
    {
        return $this->belongsTo(StudentFee::class);
    }
}