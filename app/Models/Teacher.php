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

    // 1) ADD TEACHER -> profile matra (naam, contact, designation).
    //    'subject' hataisakiyo - kun subject padhaucha vanne kura ab
    //    teacher_subject_allocations table le control garcha.
    //    'name' hataera first_name/middle_name/last_name - students table
    //    jasto nai convention.
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

    /**
     * Display ko lagi full name - views ma $teacher->full_name use garnus
     */
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

    // 2) SUBJECT ALLOCATION -> ek teacher ko sabai class/section/subject allocation haru
    public function subjectAllocations()
    {
        return $this->hasMany(TeacherSubjectAllocation::class);
    }

    // 3) ASSIGN CLASS TEACHER -> attendance access paune class ra section
    //    Source of truth ab class_teacher_assignments table ho, class_teacher_of_class /
    //    class_teacher_of_section columns lai ab kunai controller le write gardaina -
    //    tyo dui column database ma xa tara unused/legacy, purано display code ko lagi
    //    matra reference garna baaki cha (yesle overwrite hudaina).
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

    /**
     * Yo teacher kunai class ko Class Teacher ho ki (attendance mark garna pauney).
     * class_teacher_assignments table bata check garcha (source of truth).
     */
    public function isClassTeacher(): bool
    {
        return $this->classTeacherAssignment !== null;
    }
}