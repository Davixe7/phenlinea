<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Visitor>
 */
class VisitorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'dni'   => fake()->numerify('##########'),
            'name'  => fake()->firstName,
            'plate' => fake()->numerify('######'),
            'type'  => ['singular', 'company'][fake()->numberBetween(0,1)]
        ];
    }
}
