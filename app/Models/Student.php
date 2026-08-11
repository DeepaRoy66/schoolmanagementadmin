<?php

namespace App\Models;

use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope);
    }

    protected $fillable = [
        'school_id',
        'academic_year_id',
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'dob',
        'gender',
        'student_uid',
        'email',
        'phone',
        'address',
        'parent_name',
        'parent_phone',
        'telephone_no',
        'emergency_contact_name',
        'emergency_contact_relation',
        'emergency_contact_phone',
        'class_id',
        'section_id',
        'roll_number',
        'status',
        'is_active',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public static function generateStudentUid(): string
    {
        $last = static::withoutGlobalScopes()->orderByDesc('id')->value('student_uid');
        $next = $last ? ((int) $last) + 1 : 1;

        if ($next > 999) {
            throw new \Exception('Student ID limit (999) pugisakyo.');
        }

        return str_pad($next, 3, '0', STR_PAD_LEFT);
    }
}