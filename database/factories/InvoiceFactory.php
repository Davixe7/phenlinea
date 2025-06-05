<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'number' => fake()->randomNumber(9),
            'nit'    => fake()->randomNumber(9),
            'date'   => now()->startOfMonth()->format('Y-m-d'),
            'total'  => 165000
        ];
    }
}
