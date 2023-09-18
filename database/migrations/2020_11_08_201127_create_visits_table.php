<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('visits', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->timestamps();
      
      $table->datetime('start_date');
      $table->datetime('end_date');
      $table->datetime('checkin');
      $table->datetime('checkout')->nullable();
      $table->string('password')->nullable();

      $table->string('extension_name');
      
      $table->unsignedBigInteger('admin_id');
      
      $table->unsignedBigInteger('visitor_id');
      $table->foreign('visitor_id')->references('id')->on('visitors')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('visits');
  }
}
