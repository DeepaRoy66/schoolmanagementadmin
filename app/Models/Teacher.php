<?php

namespace App\Models;

use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope);
    }

    protected $fillable = [
        'school_id',
        'user_id',
        'name',
        'email',
        'phone',
        'subject',
        'is_active',
        'class_teacher_of_class',
        'class_teacher_of_section',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Yo teacher kunai class ko Class Teacher ho ki (attendance mark garna pauney)
     */
    public function isClassTeacher(): bool
    {
        return !empty($this->class_teacher_of_class);
    }
}