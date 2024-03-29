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
        Schema::create('resident_invoice_item_resident_invoice_payment', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('resident_invoice_item_id');
            $table->foreignId('resident_invoice_payment_id');
            $table->double('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resident_invoice_item_resident_invoice_payment');
    }
};
