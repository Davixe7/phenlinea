<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('payments', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->timestamps();
      $table->unsignedBiginteger('admin_id');
      
      $table->date('year')->default('2019-01-01');
      $table->integer('m1')->nullable()->default(0);
      $table->integer('m2')->nullable()->default(0);
      $table->integer('m3')->nullable()->default(0);
      $table->integer('m4')->nullable()->default(0);
      $table->integer('m5')->nullable()->default(0);
      $table->integer('m6')->nullable()->default(0);
      $table->integer('m7')->nullable()->default(0);
      $table->integer('m8')->nullable()->default(0);
      $table->integer('m9')->nullable()->default(0);
      $table->integer('m10')->nullable()->default(0);
      $table->integer('m11')->nullable()->default(0);
      $table->integer('m12')->nullable()->default(0);
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('payments');
  }
}
