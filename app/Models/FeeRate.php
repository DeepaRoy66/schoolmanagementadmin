<?php

namespace App\Models;

use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeRate extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope);
    }

    protected $fillable = [
        'school_id',
        'fee_name_id',
        'class_id',
        'billing_period_id',
        'amount',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function feeName()
    {
        return $this->belongsTo(FeeName::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function billingPeriod()
    {
        return $this->belongsTo(BillingPeriod::class);
    }
}