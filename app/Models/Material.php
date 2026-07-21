<?php

namespace App\Models;

use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials';

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope);
    }

    protected $fillable = [
        'school_id',
        'teacher_id',
        'title',
        'description',
        'class',
        'subject',
        'file_path',
        'file_name',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}