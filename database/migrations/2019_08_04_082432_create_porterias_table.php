<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePorteriasTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('porterias', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->timestamps();
      $table->rememberToken();
      
      $table->string('name');
      $table->string('email')->unique();
      $table->string('password');
      $table->integer('admin_id');
      
      $table->string('api_token', 60)->nullable()->default(null);
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('porterias');
  }
}
