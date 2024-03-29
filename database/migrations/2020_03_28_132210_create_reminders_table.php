<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemindersTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('reminders', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->timestamps();
      
      $table->unsignedInteger('admin_id');
      $table->unsignedInteger('extension_id');
      $table->string('title');
      $table->text('description');
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('reminders');
  }
}
