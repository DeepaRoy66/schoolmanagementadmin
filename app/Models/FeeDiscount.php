<?php

namespace App\Models;

use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeDiscount extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope);
    }

    protected $fillable = [
        'school_id',
        'student_id',
        'fee_name_id',
        'billing_period_id',
        'discount_percent',
        'discount_amount',
        'remarks',
    ];

    protected $casts = [
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeName()
    {
        return $this->belongsTo(FeeName::class);
    }

    public function billingPeriod()
    {
        return $this->belongsTo(BillingPeriod::class);
    }
}