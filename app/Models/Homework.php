<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Homework extends Model
{
    protected $table = 'homeworks';

    protected $fillable = [
        'school_id',
        'teacher_id',
        'title',
        'description',
        'class_id',
        'subject',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(HomeworkSubmission::class);
    
    }

    public function schoolClass()
{
    return $this->belongsTo(SchoolClass::class, 'class_id');
}
}