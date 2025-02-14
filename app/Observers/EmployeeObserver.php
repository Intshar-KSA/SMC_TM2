<?php

namespace App\Observers;

use App\Models\Emp;

class EmployeeObserver
{
    /**
     * Handle the Emp "created" event.
     */
    public function created(Emp $emp): void
    {
        //
    }

    /**
     * Handle the Emp "updated" event.
     */
    public function updated(Emp $emp): void
    {
        //
    }

    /**
     * Handle the Emp "deleted" event.
     */
    public function deleted(Emp $emp): void
    {
        //
    }

    /**
     * Handle the Emp "restored" event.
     */
    public function restored(Emp $emp): void
    {
        //
    }

    /**
     * Handle the Emp "force deleted" event.
     */
    public function forceDeleted(Emp $emp): void
    {
        //
    }
     /**
     * Handle the Emp "creating" event.
     */
    public function creating(Emp $emp): void
    {if(auth()->user()->type == "admin"){
        $emp->user_id = auth()->id();
    }

    }
}
