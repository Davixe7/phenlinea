<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidentsTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('residents', function (Blueprint $table) {
      $table->string('name');
      $table->integer('age');
      $table->string('dni');
      $table->boolean('is_owner');
      $table->boolean('is_resident');
      
      $table->unsignedBigInteger('extension_id');
      $table->bigIncrements('id');
      $table->timestamps();
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('residents');
  }
}
