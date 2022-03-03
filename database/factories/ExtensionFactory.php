<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Extension;
use Faker\Generator as Faker;

$factory->define(Extension::class, function (Faker $faker) {
    $password = Str::random(12);
    $name = $faker->numberBetween(0,1000);
    return [
      'admin_id'   => 1,
      'email'      => 1 . $name . '@phenlinea.com',
      'name'     => $name,
      'phone_1'  => '0321789' . $faker->numberBetween(100,999),
      'phone_2'  => '0321789' . $faker->numberBetween(100,999),
      
      '_email'      => 1 . $name . '@phenlinea.com',
      '_password'   => $password,
      'password'    => bcrypt( $password )
    ];
});
