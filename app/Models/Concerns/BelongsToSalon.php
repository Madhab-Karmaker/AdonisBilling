<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToSalon
{
    protected static function bootBelongsToSalon(): void
    {
        static::addGlobalScope('salon', function (Builder $builder) {
            $user = Auth::user();
            if ($user && isset($user->salon_id)) {
                $builder->where($builder->getModel()->getTable() . '.salon_id', $user->salon_id);
            }
        });

        static::creating(function ($model) {
            $user = Auth::user();
            if ($user && isset($user->salon_id) && empty($model->salon_id)) {
                $model->salon_id = $user->salon_id;
            }
        });
    }
}


