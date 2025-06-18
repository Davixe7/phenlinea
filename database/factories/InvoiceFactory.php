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
            'number'  => fake()->numerify('##########'),
            'nit'     => fake()->numerify('##########'),
            'date'    => now()->startOfMonth(),
            'total'   => [135000, 140000][fake()->numberBetween(0,1)],
            'status'  => 'pending',
            'paid_at' => null
        ];
    }
}
