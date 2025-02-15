<?php

namespace Database\Factories;

use App\Models\Emp;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmpFactory extends Factory
{
    protected $model = Emp::class;

        /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    public function definition(): array
    {
         // Fetch an admin user
         $adminUser = User::where('type', 'admin')->inRandomOrder()->first();

        
        return [
            'user_id' => $adminUser->id,
            'name' => $this->faker->name,
            'email' => fake()->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber,
            'number_of_hours_per_day' => $this->faker->numberBetween(4, 12),
            'day_off' => [$this->faker->dayOfWeek],
            'is_admin' => $this->faker->boolean(10),
            'post_url' => $this->faker->url,
            'sheet_api_url' => $this->faker->url,
            'can_show' => $this->faker->boolean(),
            'is_active' => $this->faker->boolean(80),
            'request_status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'password' => static::$password ??= Hash::make('demo1234'),
            'remember_token' => Str::random(10),
        ];
    }
}
