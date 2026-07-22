<?php

namespace App\Models;

use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSubjectAllocation extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope);
    }

    protected $fillable = [
        'school_id',
        'teacher_id',
        'subject_id',
        'section_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function create()
{
    $subjects = Subject::with('schoolClass.sections')->orderBy('subject_name')->get();
    $teachers = Teacher::where('is_active', true)->orderBy('first_name')->get();

    return view('school-admin.subject-allocations.create', compact('subjects', 'teachers'));
}

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}