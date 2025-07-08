<?php

namespace Database\Factories;

use App\Porteria;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoveltyFactory extends Factory {
    public function definition(){
        return [
            'porteria_id' => Porteria::first()->id,
            'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Impedit, adipisci? Illo obcaecati accusamus minus voluptate? Quae quod assumenda earum, nesciunt molestiae cum voluptatum rem repudiandae provident maiores ipsum repellendus cumque.',
            'read_at'     => false
        ];
    }
}
