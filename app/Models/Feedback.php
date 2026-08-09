<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'school_id',
        'submitted_by',
        'submitted_by_role',
        'subject',
        'message',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}