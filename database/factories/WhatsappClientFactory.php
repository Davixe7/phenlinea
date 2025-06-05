<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\WhatsappClient>
 */
class WhatsappClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id'               => 1,
            'name'                  => fake()->name,
            'base_url'              => 'https://example.com',
            'comunity_instance_id'  => str_replace('-', '', substr(fake()->uuid(),0,12)),
            'delivery_instance_id'  => str_replace('-', '', substr(fake()->uuid(),0,12)),
            'access_token'          => str_replace('-', '', substr(fake()->uuid(),0,12))
        ];
    }
}
