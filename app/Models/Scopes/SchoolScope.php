<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SchoolScope implements Scope
{
    /**
     * auth()->user() only resolves against the DEFAULT guard (usually 'web',
     * session-based). API requests go through the 'sanctum' guard instead
     * (token-based, no session), so auth()->check() can return false there
     * even though the request is properly authenticated — silently
     * skipping the school_id filter and leaking cross-school data.
     *
     * Fix: fall back to the 'sanctum' guard explicitly if the default
     * guard has no user.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user() ?? auth('sanctum')->user();

        if ($user && $user->role !== 'super_admin') {
            $builder->where($model->getTable() . '.school_id', $user->school_id);
        }
    }
}