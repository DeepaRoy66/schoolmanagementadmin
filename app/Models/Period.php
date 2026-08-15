<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'code',
        'start_time',
        'end_time',
        'is_break',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_break'   => 'boolean',
        'is_active'  => 'boolean',
        'start_time' => 'datetime:H:i',
        'end_time'   => 'datetime:H:i',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}