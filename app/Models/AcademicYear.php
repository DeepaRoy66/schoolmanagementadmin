<?php
// app/Models/AcademicYear.php
namespace App\Models;

use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope);
    }

    protected $fillable = ['school_id', 'year', 'is_running'];

    protected $casts = [
        'is_running' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}