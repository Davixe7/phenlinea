<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Reminder;
use Faker\Generator as Faker;

$factory->define(Reminder::class, function (Faker $faker) {
    return [
      'title'        => 'Lorem ipsum',
      'description'  => $faker->text(180),
      'admin_id'     => 1,
      'extension_id' => 1
    ];
});
