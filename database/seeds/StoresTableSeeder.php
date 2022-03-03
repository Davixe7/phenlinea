<?php

use Illuminate\Database\Seeder;
use App\Store;

class StoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $json_store = '{
      "name": "Test Store",
      "description": "Lorem ipsum dolor sit amet",
      "phone_1": "3211231234",
      "phone_2": null,
      "email": "store@test.com",
      "nit": "201233456",
      "lat": "21.000000",
      "lng": "12.000000",
      "address": "Lorem ipsum dolor sit amet",
      "logo": null,
      "pictures": [],
      "category": "Comidas",
      "schedule": [
          {
              "name": "Lun",
              "open": 720,
              "close": 1260,
              "_open": "12:00",
              "_close": "21:00",
              "works": true
          },
          {
              "name": "Mar",
              "open": 720,
              "close": 1260,
              "_open": "12:00",
              "_close": "21:00",
              "works": true
          },
          {
              "name": "Mie",
              "open": 720,
              "close": 1260,
              "_open": "12:00",
              "_close": "21:00",
              "works": true
          },
          {
              "name": "Jue",
              "open": 720,
              "close": 1260,
              "_open": "12:00",
              "_close": "21:00",
              "works": true
          },
          {
              "name": "Vie",
              "open": 720,
              "close": 1260,
              "_open": "12:00",
              "_close": "21:00",
              "works": true
          },
          {
              "name": "Sab",
              "open": 720,
              "close": 1260,
              "_open": "12:00",
              "_close": "21:00",
              "works": true
          },
          {
              "name": "Dom",
              "open": 720,
              "close": 1260,
              "_open": "12:00",
              "_close": "21:00",
              "works": true
          }
      ],
      "_email": "store@test.com",
      "password": "$2y$10$5XbL1kLrcgqD.bg3a/ET1uewwYOHCIcUJY02t5DwOMDv72ZJuY1sm",
      "api_token": "$2y$10$Wa7P3b5m5DgXExMmNPMYcOLJ0cgZaSAemuPStaMruf7sm4TZMfbB.",
      "status": "pending"
    }';
    
    Store::create( json_decode( $json_store, true ) );
    factory(App\Store::class, 20)->create();
    }
}
