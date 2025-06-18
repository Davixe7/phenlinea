<?php

namespace Database\Factories;

use App\Admin;
use App\Visitor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitFactory extends Factory {

    function definition(){return [
        'start_date' => now(),
        'end_date' => now()->addDays(1),
        'checkin' => now(),
        'checkout' => null,
        'plate' => fake()->numerify('######'),
        'authorized_by' => fake()->firstNameMale,
        'extension_name' => fake()->numerify('####'),
        'admin_id' => Admin::first()->id,
        'visitor_id' => Visitor::first()->id
    ];}
}
