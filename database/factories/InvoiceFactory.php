<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Admin>
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
            'number' => 'A' . fake()->numberBetween(5000,6000),
            'nit'    => fake()->numerify('##########'),
            'date'   => now()->startOfMonth(),
            'total'  => 135000,
            'status' => 'pendiente',
            'paid_at' => null
        ];
    }
}
