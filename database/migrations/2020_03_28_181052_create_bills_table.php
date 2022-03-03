<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('bills', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->timestamps();
      
      $table->unsignedInteger('admin_id');
      $table->string('title')->nullable();
      $table->string('url')->nullable();
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('bills');
  }
}
