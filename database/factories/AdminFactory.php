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
        $name  = fake()->company;
        $email = str_replace(' ', '', $name);
        $email = str_replace(',', '', $email);
        $email = strtolower(str_replace('-', '', $email)) . '@phenlinea.com';

        return [
            'email' => $email,
            'password' => '123456',
            'contact_email' => $email,
            'name' => $name,
            'nit' => fake()->numberBetween(1000000000,9999999999),

            'phone'   => '3210000000',
            'phone_2' => '',
            'phone_3' => '',
            'phone_4' => '',
            'address' => fake()->streetAddress,
            'status'  => 1,
            'api_token' => '',
            'email_verified_at' => '2024-01-01 00:00:00',
            'phone_verification'=> '',

            'whatsapp_instance_id' => '',
            'whatsapp_status'      => 'offline',
            'whatsapp_group_id'    => '',
            'whatsapp_group_url'   => '',

            'device_serial_number'   => '',
            'device_2_serial_number' => '',
            'device_community_id'    => '',
            'device_building_id'     => '',
        ];
    }
}
