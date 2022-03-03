<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Store;
use Faker\Generator as Faker;

$factory->define(Store::class, function (Faker $faker) {
    return [
      "name" => $faker->name,
      "description" => $faker->text(210),
      "phone_1" => "3211231234",
      "phone_2" => null,
      "email" => $faker->safeEmail,
      "nit" => "201233456",
      "lat" => "21.000000",
      "lng" => "12.000000",
      "address" => $faker->address,
      "logo" => null,
      "pictures" => [],
      "category" => ['Comidas', 'Ferreterías', 'Carnicerías', 'Supermercados', 'Licores', 'Legumbrerias', 'Tiendas', 'Otros' ][$faker->numberBetween($min = 0, $max = 7)],
      "schedule" => [
       [
         "name" => "Lun",
         "open" => 720,
         "close" => 1260,
         "_open" => "12:00",
         "_close" => "21:00",
         "works" => true,
       ],
       [
         "name" => "Mar",
         "open" => 720,
         "close" => 1260,
         "_open" => "12:00",
         "_close" => "21:00",
         "works" => true,
       ],
       [
         "name" => "Mie",
         "open" => 720,
         "close" => 1260,
         "_open" => "12:00",
         "_close" => "21:00",
         "works" => true,
       ],
       [
         "name" => "Jue",
         "open" => 720,
         "close" => 1260,
         "_open" => "12:00",
         "_close" => "21:00",
         "works" => true,
       ],
       [
         "name" => "Vie",
         "open" => 720,
         "close" => 1260,
         "_open" => "12:00",
         "_close" => "21:00",
         "works" => true,
       ],
       [
         "name" => "Sab",
         "open" => 720,
         "close" => 1260,
         "_open" => "12:00",
         "_close" => "21:00",
         "works" => true,
       ],
       [
         "name" => "Dom",
         "open" => 720,
         "close" => 1260,
         "_open" => "12:00",
         "_close" => "21:00",
         "works" => true,
       ],
      ],
      "_email" => $faker->safeEmail,
      "password" => bcrypt(123456),
      "api_token" => '$2y$10$Wa7P3b5m5DgXExMmNPMYcOLJ0cgZaSAemuPStaMruf7sm4TZMfbB.',
      "status" => "pending",
    ];
});
