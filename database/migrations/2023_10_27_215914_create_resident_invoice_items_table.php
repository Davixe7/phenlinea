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
        Schema::create('resident_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('resident_invoice_id');
            $table->string('title');
            $table->double('pending')->nullable()->default(0);
            $table->double('current')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resident_invoice_items');
    }
};
