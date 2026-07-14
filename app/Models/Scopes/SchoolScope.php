<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SchoolScope implements Scope
{
    /**
     * Yo scope le automatically school_id le filter garcha,
     * tara super_admin lai matra sabai data herna dincha.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (auth()->check() && auth()->user()->role !== 'super_admin') {
            $builder->where($model->getTable() . '.school_id', auth()->user()->school_id);
        }
    }
}