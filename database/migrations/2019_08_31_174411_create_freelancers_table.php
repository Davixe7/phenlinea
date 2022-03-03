<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFreelancersTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('freelancers', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->timestamps();
      $table->rememberToken();
      
      $table->string('name');
      $table->string('email')->unique();
      $table->string('password');
      $table->string('phone')->nullable();
      $table->integer('rate')->nullable()->default(15000);
      $table->string('api_token')->nullable();
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('freelancers');
  }
}
