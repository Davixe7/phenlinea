<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('apto');

            $table->string('concepto1')->nullable();
            $table->unsignedBigInteger('vencido1')->nullable();
            $table->unsignedBigInteger('actual1')->nullable();

            $table->string('concepto2')->nullable();
            $table->unsignedBigInteger('vencido2')->nullable();
            $table->unsignedBigInteger('actual2')->nullable();

            $table->string('concepto3')->nullable();
            $table->unsignedBigInteger('vencido3')->nullable();
            $table->unsignedBigInteger('actual3')->nullable();

            $table->date('periodo');
            $table->date('emision');
            $table->date('limite');

            $table->string('link')->nullable();
            $table->text('note')->nullable();

            $table->unsignedBigInteger('admin_id');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
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
        Schema::dropIfExists('facturas');
    }
}
