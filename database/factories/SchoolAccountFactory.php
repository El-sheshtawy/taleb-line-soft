<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SchoolAccount>
 */
class SchoolAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
         return [
            'school_name' => $this->faker->company,
            'school_logo_url' => $this->faker->imageUrl(100, 100, 'education'),
            'username' => $this->faker->userName,
            'password' => bcrypt('password'),
            'subscription_state' => 'active',
            'edu_region' => $this->faker->city,
            'teachers_default_password' => '123456',
            'students_default_password' => 'student123',
            'follow_up_id' => null,
            'level_id' => null,
        ];
    }
}
