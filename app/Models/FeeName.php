<?php

namespace App\Models;

use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeName extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope);
    }

    protected $fillable = [
        'school_id',
        'fee_group_id',
        'name',
        'code',
        'fee_type',
        'is_taxable',
        'discount_applicable',
        'is_active',
    ];

    protected $casts = [
        'is_taxable' => 'boolean',
        'discount_applicable' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function feeGroup()
    {
        return $this->belongsTo(FeeGroup::class);
    }
}