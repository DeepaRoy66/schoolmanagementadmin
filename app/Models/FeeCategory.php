<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeCategory extends Model
{
    protected $fillable = [
        'school_id',
        'name',
        'is_recurring',
        'recurring_interval',
    ];

    protected $casts = [
        'is_recurring' => 'boolean',
    ];

    /**
     * The school this fee category belongs to.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Student fee records that use this category.
     */
    public function studentFees()
    {
        return $this->hasMany(StudentFee::class);
    }

    
}