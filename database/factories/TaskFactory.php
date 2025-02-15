<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\Emp;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        // Get a random user
        $user = User::query()
            ->where('type', 'admin')
            ->whereHas('projects', function ($query) {
                $query->getModel()->newQuery()->withoutGlobalScopes();
            })
            ->whereHas('emps', function ($query) {
                $query->getModel()->newQuery()->withoutGlobalScopes();
            })
            ->inRandomOrder()
            ->first();

        if (!$user) {
            $user = User::factory()->create(['type' => 'admin']);
        }

        // Get a random project belonging to the user
        $project = Project::query()
            ->withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->inRandomOrder()
            ->first();

        // Get two different employees belonging to the same user
        $employees = Emp::query()
            ->withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->inRandomOrder()
            ->limit(2)
            ->get();

        if ($employees->count() < 2) {
            while ($employees->count() < 2) {
                $employees->push(Emp::factory()->create(['user_id' => $user->id]));
            }
        }

        $sender = $employees[0];
        $receiver = $employees[1];

        return [
            'project_id' => $project->id,
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph,
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'time_in_minutes' => $this->faker->numberBetween(30, 240),
            'start_date' => Carbon::now()->subDays(rand(1, 30)),
            'is_recurring' => $this->faker->boolean(20),
            'recurrence_interval_days' => $this->faker->randomElement([7, 14, 30]),
            'next_occurrence' => Carbon::now()->addDays($this->faker->numberBetween(1, 30)),
            'parent_id' => null,
            'exact_time' => Carbon::now()->hour * 60 + Carbon::now()->minute,

        ];
    }
}
