<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('stores', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->timestamps();
      $table->string('name');
      $table->text('description');
      $table->string('phone_1');
      $table->string('phone_2')->nullable();
      $table->string('email')->nullable();
      $table->string('nit');
      $table->decimal('lat', 8, 6)->nullable();
      $table->decimal('lng', 9, 6)->nullable();
      $table->text('address');
      $table->text('logo')->nullable();
      $table->text('pictures')->nullable();
      $table->string('category')->nullable();
      $table->text('schedule')->nullable();
      
      $table->string('_email')->unique()->nullable();
      $table->string('password')->nullable();
      $table->string('api_token')->nullable();
      $table->enum('status', ['pending', 'active', 'suspended'])->default('pending');
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('stores');
  }
}
