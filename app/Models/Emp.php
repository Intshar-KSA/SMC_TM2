<?php

namespace App\Models;

use App\Observers\EmployeeObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[ObservedBy([EmployeeObserver::class])]
class Emp extends  Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'phone', 'number_of_hours_per_day', 'day_off','email', 'password','is_admin','post_url','sheet_api_url',
        'can_show','is_active','request_status'
    ];

    protected $casts = [
        'day_off' => 'array',
    ];




    protected $hidden = [
        'password', 'remember_token',
    ];
    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function sentTasks()
    {
        return $this->hasMany(Task::class, 'sender_id');
    }

    public function receivedTasks()
    {
        return $this->hasMany(Task::class, 'receiver_id');
    }
}
