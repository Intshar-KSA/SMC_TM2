<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable , HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'w_api_token', 'w_api_profile_id', 'end_date_subscription', 'type',
       'enable_whatsapp_notifications', 'enable_group_notifications', 'enable_employee_notifications','work_group','company_policy'
    ];

    // Define relationships
    public function emps()
    {
        return $this->hasMany(Emp::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function sentTasks()
    {
        return $this->hasMany(Task::class, 'sender_id');
    }

    public function receivedTasks()
    {
        return $this->hasMany(Task::class, 'receiver_id');
    }

    public function taskStatuses()
    {
        return $this->hasMany(TaskStatus::class);
    }

    public function taskFollowUps()
    {
        return $this->hasMany(TaskFollowUp::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin()
    {
        return $this->type === 'super admin';
    }

    public function isAdmin()
    {
        return $this->type === 'admin';
    }
}
