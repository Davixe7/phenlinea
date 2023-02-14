<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePetitionsTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('petitions', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->timestamps();
      $table->unsignedBigInteger('admin_id');
      $table->string('extension_name')->nullable();
      $table->string('name');
      $table->string('phone');
      $table->string('phone_2')->nullable();

      $table->text('description');
      $table->text('answer')->nullable();
      $table->timestamp('read_at')->nullable();
      $table->timestamp('replied_at')->nullable();

      $table->enum('status', ['pending', 'read', 'replied'])->default('pending');
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('petitions');
  }
}
