<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Ad;
use Faker\Generator as Faker;

$factory->define(Ad::class, function (Faker $faker) {
    $file = Storage::get('public/colombia.json');
    $municipios = json_decode( $file, true );
    $totalMunicipios = count($municipios);
    $municipio = $municipios[ $faker->numberBetween(0,$totalMunicipios - 1) ];
    $totalCiudades = count($municipio['ciudades']);
    $ciudad = $municipio['ciudades'][ $faker->numberBetween(0,$totalCiudades - 1) ];
    
    return [
      'name'        => $faker->name,
      'description' => $faker->text(50),
      'state'       => $municipio['departamento'],
      'city'        => $ciudad,
      'address'     => $faker->address,
      'phone_1'     => $faker->phoneNumber,
      'phone_2'     => $faker->phoneNumber,
      'email'       => $faker->email,
      'pictures'    => [
        [
          "name" => "Random",
          "url"  => "http://placeimg.com/300/300/tech"
        ],
        [
          "name" => "Random",
          "url"  => "http://placeimg.com/300/300/tech"
        ]
      ]
    ];
});
