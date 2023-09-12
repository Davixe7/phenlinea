<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('dni');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->enum('type', ['singular', 'company']);
            $table->string('company')->nullable();
            $table->string('arl')->nullable();
            $table->string('eps')->nullable();
            $table->string('plate')->nullable();
            //$table->foreign('extension_id')->references('id')->on('extensions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitors');
    }
}
