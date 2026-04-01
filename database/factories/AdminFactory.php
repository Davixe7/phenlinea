<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Admin>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'address' => fake()->address,
            'email' => fake()->companyEmail,
            'name' => fake()->company,
            'nit' => fake()->numerify('##########'),
            'phone' => fake()->numerify('##########'),
            'password' => bcrypt('123456'),
            'api_token' => Str::random(60)
        ];
    }
}
