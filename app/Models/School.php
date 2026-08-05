<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'school_code',
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

    
    public static function generateSchoolCode(): string
    {
        $last = static::orderByDesc('id')->first();
        $nextNumber = $last ? $last->id + 1 : 1;

        return 'SCH-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }


    public function users()
    {
        return $this->hasMany(User::class);
    }

    
    public function admins()
    {
        return $this->hasMany(User::class)->where('role', 'school_admin');
    }
}