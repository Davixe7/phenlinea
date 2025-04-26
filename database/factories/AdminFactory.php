<?php

namespace Database\Factories;

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
            'email' => fake()->unique()->safeEmail,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'contact_email' => fake()->optional()->safeEmail,
            'name' => fake()->name,
            'nit' => fake()->unique()->numerify('##########'),
            'phone' => fake()->phoneNumber,
            'address' => fake()->address,
            'status' => fake()->randomElement([0, 1]),
        ];
    }
}
