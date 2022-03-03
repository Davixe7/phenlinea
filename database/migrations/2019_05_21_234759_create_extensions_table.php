<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtensionsTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('extensions', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->timestamps();
      
      $table->string('_email');
      $table->string('_password');
      $table->string('password');
      
      $table->string('name', 20)->nullable();
      $table->string('phone_1');
      $table->string('phone_2')->nullable();
      $table->string('phone_3')->nullable();
      $table->string('phone_4')->nullable();
      
      $table->string('owner_phone')->nullable();
      $table->integer('pets_count')->nullable()->default(0);
      $table->string('parking_number1')->nullable();
      $table->string('parking_number2')->nullable();
      $table->json('vehicles')->nullable();
      $table->boolean('has_deposit')->default(0);
      $table->string('email')->nullable();
      
      $table->string('api_token', 80)->unique()->nullable()->default(null);
      $table->unsignedBigInteger('admin_id')->nullable();
    });
  }

  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('extensions');
  }
}
