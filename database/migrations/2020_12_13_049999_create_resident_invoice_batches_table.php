<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resident_invoice_batches', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->date('periodo');
            $table->date('emision');
            $table->date('limite');
            $table->string('link')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('admin_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resident_invoice_batches');
    }
};
