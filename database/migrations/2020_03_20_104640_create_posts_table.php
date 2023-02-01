<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('posts', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->timestamps();
      
      $table->unsignedInteger('admin_id');
      $table->string('title');
      $table->text('description');
      $table->enum('type', ['post', 'doc'])->default('post');
    });
  }
  
  /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down()
  {
    Schema::dropIfExists('posts');
  }
}
