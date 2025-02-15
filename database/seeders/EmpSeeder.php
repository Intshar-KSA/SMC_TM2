<?php

namespace Database\Seeders;

use App\Models\Emp;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        Emp::withoutEvents(function () {
            Emp::factory()->count(20)->create();
        });

    }
}
