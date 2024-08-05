<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'whatsapp_group_id', 'start_date', 'end_date',
        'facebook_user',
        'insta_user',
        'tiktok_user',
        'instagram_user',
        'snap_user',
        'x_user',
        'facebook_pass',
        'insta_pass',
        'tiktok_pass',
        'instagram_pass',
        'snap_pass',
        'x_pass',

        'store_url',
        'store_user',
        'store_password'
    ];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function emps()
    {
        return $this->belongsToMany(Emp::class);
    }
    public function user_project_app()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
