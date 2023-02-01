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
      $table->string('dni');
      $table->string('name');
      $table->string('phone')->nullable();
      $table->enum('type', ['singular', 'company']);
      $table->string('company')->nullable();
      $table->string('arl')->nullable();
      $table->string('eps')->nullable();
      $table->string('plate')->nullable();
      $table->datetime('checkin');
      $table->datetime('checkout')->nullable();
      $table->unsignedBigInteger('extension_id');
      $table->foreign('extension_id')->references('id')->on('extensions')->onDelete('cascade');
      $table->unsignedBigInteger('admin_id');
      $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
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
