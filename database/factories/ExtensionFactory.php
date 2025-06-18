<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Extension>
 */
class ExtensionFactory extends Factory {
  public function definition(){
    $name = fake()->numberBetween(0,1000);
    return [
      'admin_id' => 1,
      'name'     => $name,
      'phone_1'  => "3" . fake()->numerify('########'),
      'phone_2'  => "3" . fake()->numerify('########'),
      
      'email'     => $name . '@phenlinea.com',
    ];
  }
}
