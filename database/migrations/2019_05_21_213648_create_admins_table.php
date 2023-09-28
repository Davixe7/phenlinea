<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('admins', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->timestamps();
      $table->rememberToken();
      $table->string('email')->unique();
      $table->string('password');
      $table->string('contact_email')->nullable();
      $table->string('name');
      $table->string('nit')->unique();
      $table->string('phone');
      $table->string('phone_2')->nullable();
      $table->string('phone_3')->nullable();
      $table->string('phone_4')->nullable();
      $table->text('address');
      $table->unsignedInteger('status')->default(1);
      $table->string('api_token', 60)->nullable()->default(null);
      $table->timestamp('email_verified_at')->nullable();
      $table->string('phone_verification', 6)->nullable();
      $table->string('whatsapp_instance_id')->nullable();
      $table->string('whatsapp_status')->default('offline');
      $table->string('whatsapp_group_id')->nullable();
      $table->string('whatsapp_group_url')->nullable();
      
      $table->string('device_serial_number')->nullable();
      $table->string('device_2_serial_number')->nullable();
      $table->string('device_community_id')->nullable();
      $table->string('device_building_id')->nullable();
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('admins');
  }
}
