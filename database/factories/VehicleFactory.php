<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'resident_id'  => 1,
            'extension_id' => 1,
            'plate'        => fake()->numerify('######'),
            'tag'          => fake()->numerify('######'),
            'type'         => ['bike', 'car'][fake()->numberBetween(0,1)],
        ];
    }
}
