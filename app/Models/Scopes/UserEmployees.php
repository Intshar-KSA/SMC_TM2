<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class UserEmployees implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();
        $guard = Auth::getDefaultDriver();

        if ($guard === 'web') {
            if( $user?->isSuperAdmin()??false) {
                $builder;
            }
            if($user?->isAdmin()??false) {
                $builder->where('user_id', $user->id);
            }
        }
        if ($guard === 'emp') {
            $builder->where('user_id', $user->user_id);
        }

    }
}
