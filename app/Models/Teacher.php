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
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'dob',
        'gender',
        'marital_status',
        'pan_no',
        'address',
        'designation',
        'is_active',
        'class_teacher_of_class',
        'class_teacher_of_section',
    ];

    
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->last_name);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    public function subjectAllocations()
    {
        return $this->hasMany(TeacherSubjectAllocation::class);
    }

    
    public function classTeacherAssignment()
    {
        return $this->hasOne(ClassTeacherAssignment::class);
    }

    public function classTeacherOfClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_teacher_of_class');
    }

    public function classTeacherOfSection()
    {
        return $this->belongsTo(Section::class, 'class_teacher_of_section');
    }

    
    public function isClassTeacher(): bool
    {
        return $this->classTeacherAssignment !== null;
    }
}