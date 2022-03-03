<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('products', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->timestamps();
      $table->string('name');
      $table->text('description');
      $table->double('price');
      $table->unsignedBigInteger('category_id');
      $table->unsignedBigInteger('store_id');
      $table->foreign('store_id')->references('id')->on('stores');
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('products');
  }
}
