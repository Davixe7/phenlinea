<?php
namespace Database\Factories;

use App\Extension;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResidentFactory extends Factory {
    public function definition(){
        return [
            'extension_id' => Extension::first()->id,
            'name' => fake()->name,
            'age'  => fake()->numberBetween(18, 90),
            'dni'  => fake()->numerify('##########'),
            'card' => fake()->numerify('##########'),
            'is_owner'      => fake()->numberBetween(0,1),
            'is_resident'   => fake()->numberBetween(0,1),
            'is_authorized' => fake()->numberBetween(0,1),
            'disability'    => false
        ];
    }
}
