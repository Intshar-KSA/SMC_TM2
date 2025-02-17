<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TasksScope implements Scope
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
                $builder->whereHas('user_project_app', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->id);
                });
            }
        }
        if ($guard === 'emp') {
            if ($user && !$user->can_show) {


                $builder->where(function($q) use ($user) {
                    $q->where('receiver_id', $user->id);
                });
            }
            else {
                // Otherwise, apply user_project_app filter
                $builder->whereHas('user_project_app', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->id);
                });
            }
        }
    }
}
