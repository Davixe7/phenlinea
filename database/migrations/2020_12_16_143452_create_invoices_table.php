<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('invoices', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->timestamps();
      $table->string('number');
      $table->string('nit');
      $table->date('date');
      $table->unsignedBigInteger('total');
      $table->string('status')->default('pendiente')->nullable();
      $table->dateTime('paid_at')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('invoices');
  }
}
