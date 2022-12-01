<?php

use Illuminate\Database\Seeder;
use App\Admin;
use App\Freelancer;
use App\User;
use App\Payment;
use App\Porteria;
use App\Visit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    // $modules = [
    //   'Censo'                  => 'census',
    //   'Cartelera'              => 'posts',
    //   'Censo'                  => 'census',
    //   'Facturación Residentes' => 'residents_billing',
    //   'Manuales & Documentos'  => 'documents',
    //   'Mensajes masivos'       => 'whatsapp',
    //   'Notificaciones'         => 'notifications',
    //   'Novedades'              => 'news',
    //   'Pagos'                  => 'payment_links',
    //   'Solicitudes'            => 'requests',
    //   'Visitas'                => 'visits'
    // ];

    // foreach ($modules as $name => $slug) {
    //   \App\Module::create([
    //     'name' => $name,
    //     'slug' => $slug
    //   ]);
    // }
    DB::unprepared(file_get_contents(storage_path('app/ddbb.sql')));
  }
}
