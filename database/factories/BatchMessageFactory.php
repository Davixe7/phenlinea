<?php

namespace Database\Factories;

use App\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\BatchMessage>
 */
class BatchMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $numbers = [];
        for($i = 0; $i < 100; $i++){
            $numbers[] = fake()->numerify('##########');
        }

        return [
            'admin_id' => 1,
            'numbers'  => '000000000',
            'title' => join(' ', fake()->words(3)),
            'body' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Veniam quisquam repellendus aspernatur quae nam? Expedita reprehenderit quaerat impedit rem autem, velit, totam, soluta vitae qui animi nam voluptates neque? Voluptatibus.'
        ];
    }
}
