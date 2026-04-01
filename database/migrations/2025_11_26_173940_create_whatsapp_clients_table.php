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
        Schema::create('whatsapp_clients', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('base_url')->nullable();
            $table->string('access_token')->nullable();
            $table->boolean('enabled');
            $table->string('batch_instance_id');
            $table->string('comunity_instance_id')->nullable();
            $table->string('delivery_instance_id')->nullable();
            $table->string('batch_instance_phone')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('whatsapp_clients');
    }
};
