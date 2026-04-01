<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoveltiesTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('novelties', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->boolean('read')->default(1);
      $table->dateTime('read_at')->nullable();
      $table->text('description');
      $table->unsignedBigInteger('porteria_id');
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
    Schema::dropIfExists('novelties');
  }
}
