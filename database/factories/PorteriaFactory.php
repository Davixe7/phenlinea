<?php

namespace Database\Factories;

use App\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Porteria>
 */
class PorteriaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name,
            'email' => fake()->safeEmail,
            'password' => bcrypt('password'),
            'admin_id' => Admin::first()->id,
        ];
    }
}
