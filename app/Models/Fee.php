<?php

namespace App\Models;

use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $table = 'fees';

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope);
    }

    protected $fillable = [
        'school_id',
        'student_id',
        'title',
        'amount',
        'paid_amount',
        'status',
        'due_date',
        'paid_date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}