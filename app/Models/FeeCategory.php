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

   
    public function school()
    {
        return $this->belongsTo(School::class);
    }


    public function studentFees()
    {
        return $this->hasMany(StudentFee::class);
    }

    
}