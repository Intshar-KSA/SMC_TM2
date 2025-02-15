<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        // Fetch an admin user
        $adminUser = User::where('type', 'admin')->inRandomOrder()->first();

       

        return [
            'user_id' => $adminUser->id,
            'name' => $this->faker->company,
            'whatsapp_group_id' => $this->faker->uuid,
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'facebook_user' => $this->faker->userName,
            'tiktok_user' => $this->faker->userName,
            'instagram_user' => $this->faker->userName,
            'snap_user' => $this->faker->userName,
            'x_user' => $this->faker->userName,
            'facebook_pass' => $this->faker->password,
            'tiktok_pass' => $this->faker->password,
            'instagram_pass' => $this->faker->password,
            'snap_pass' => $this->faker->password,
            'x_pass' => $this->faker->password,
            'store_url' => $this->faker->url,
            'store_user' => $this->faker->userName,
            'store_password' => $this->faker->password,
        ];
    }
}
