<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Emp;
use App\Models\Scopes\TasksScope;
use App\Models\User;
use App\Observers\TaskObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ObservedBy([TaskObserver::class])]
#[ScopedBy([TasksScope::class])]
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'title', 'description', 'sender_id', 'receiver_id', 'time_in_minutes', 'start_date',
        'is_recurring',
        'recurrence_interval_days',
        'next_occurrence',
        'parent_id','exact_time'
    ];

    // Define relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user_project()
{
    return $this->belongsTo(Project::class,'project_id')->where('user_id', auth()->id());
}
public function user_project_app()
{
    return $this->belongsTo(Project::class, 'project_id');
}

public function emp_project()
{

    $user=User::find(auth()->user()->user_id);
    return $this->belongsTo(Project::class,'project_id')->where('user_id', $user->id);
}
public function emp_project2()
{
    return $this->belongsTo(Project::class, 'project_id');
}

public function task_emp()
{
    return $this->belongsTo(Emp::class,'sender_id')->where('user_id', auth()->id());
}

public function task_emp_app()
{
    return $this->belongsTo(Emp::class,'sender_id')->where('user_id', auth()->user()->user_id);
}
    public function sender()
    {
        return $this->belongsTo(Emp::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Emp::class, 'receiver_id');
    }

    public function status()
    {
        return $this->belongsTo(TaskStatus::class);
    }

    public function followUps()
    {
        return $this->hasMany(TaskFollowUp::class);
    }

    public function lastFollowUp()
    {
        return $this->hasOne(TaskFollowUp::class)->latestOfMany();
    }

    public function projectOwner()
    {
        return $this->hasOneThrough(User::class, Project::class, 'id', 'id', 'project_id', 'user_id');
    }
    public function taskFollowUps()
    {
        return $this->hasMany(TaskFollowUp::class);
    }
     // Define parent task relationship
     public function parentTask()
     {
         return $this->belongsTo(Task::class, 'parent_id');
     }

     // Define child tasks relationship
     public function childTasks()
     {
         return $this->hasMany(Task::class, 'parent_id');
     }

 // Function to handle recurring tasks
 public static function handleRecurringTasks()
 {
     $now = Carbon::now();
     $tasks = self::where('is_recurring', true)
         ->where('next_occurrence', '<=', $now)
         ->get();

     foreach ($tasks as $task) {
         // Create a new task
         $newTask = $task->replicate();
         $newTask->start_date = $task->next_occurrence;
         $newTask->next_occurrence = Carbon::parse($task->next_occurrence)->addDays($task->recurrence_interval_days);
         $newTask->save();

         // Update the next_occurrence of the original task
         $task->next_occurrence = $newTask->next_occurrence;
         $task->save();
     }
 }

}
