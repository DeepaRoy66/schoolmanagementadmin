<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'license_status',
        'license_start',
        'license_expiry',
        'trial_ends_at',
        'is_active',
    ];

    protected $casts = [
        'license_start' => 'date',
        'license_expiry' => 'date',
        'trial_ends_at' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Users (school admins, teachers, students) belonging to this school.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Only the school-admin users for this school.
     */
    public function admins()
    {
        return $this->hasMany(User::class)->where('role', 'school_admin');
    }
}