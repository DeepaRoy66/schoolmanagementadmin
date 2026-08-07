<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'class_id',
        'student_id',
        'reported_by',
        'message',
        'photo_path',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id'); // tapaiko Class model ko naam anusar adjust garnu
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}