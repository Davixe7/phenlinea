<?php

namespace Database\Seeders;

use App\Admin;
use App\Porteria;
use Database\Factories\PorteriaFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::factory(100)->has(Porteria::factory()->state(fn($attributes, Admin $admin)=>[
            'name'  => "Portería de " . $admin->name,
            'email' => "porteria." . $admin->email
        ]))->create();
    }
}
