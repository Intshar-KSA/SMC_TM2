<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class UserProjects implements Scope
{
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
