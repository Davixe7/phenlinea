<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidentInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resident_invoices', function (Blueprint $table) {
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

            $table->string('concepto4')->nullable();
            $table->unsignedBigInteger('vencido4')->nullable();
            $table->unsignedBigInteger('actual4')->nullable();

            $table->string('concepto5')->nullable();
            $table->unsignedBigInteger('vencido5')->nullable();
            $table->unsignedBigInteger('actual5')->nullable();

            $table->string('concepto6')->nullable();
            $table->unsignedBigInteger('vencido6')->nullable();
            $table->unsignedBigInteger('actual6')->nullable();

            $table->unsignedDouble('ultimo_pago')->nullable();
            $table->date('fecha_ultimo_pago')->nullable();
            $table->foreignId('resident_invoice_batch_id');
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
        Schema::dropIfExists('resident_invoices');
    }
}
