<?php

namespace App\Models;

use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYearRun extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope);
    }

  protected $fillable = [
    'school_id',
    'academic_year_id',
    'class_id',
    'start_date',
    'end_date',
];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}