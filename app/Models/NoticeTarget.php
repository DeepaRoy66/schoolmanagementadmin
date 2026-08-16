<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'notice_id',
        'class_id',
        'section_id',
    ];

    public function notice()
    {
        return $this->belongsTo(Notice::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}