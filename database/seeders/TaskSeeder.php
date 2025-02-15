<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::withoutEvents(function () {
            Task::factory()->count(50)->create();
        });
    }
}
