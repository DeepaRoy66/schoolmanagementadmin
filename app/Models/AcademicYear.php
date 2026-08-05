<?php
// app/Models/AcademicYear.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = ['school_id', 'year', 'is_running'];

    protected $casts = [
        'is_running' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}