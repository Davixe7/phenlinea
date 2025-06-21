<?php

namespace Database\Factories;

use App\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetitionFactory extends Factory {
    public function definition(){
        return [
            'admin_id'       => Admin::first()->id,
            'extension_name' => fake()->numerify('####'),
            'name'           => fake()->name,
            'phone'          => fake()->numerify('##########'),
            'description'    => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nihil, aut cum! Nobis aspernatur consequatur, sint vitae eum numquam veniam rerum hic, nulla dolorem culpa cum mollitia soluta ex dolor ab.',
        ];
    }
}
