<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('batch_messages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('admin_id');
            $table->text('numbers')->nullable();
            $table->text('message');
            $table->text('filename')->nullable();
            $table->text('media_url')->nullable();
            $table->string('status')->default('pending');
            $table->string('group_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_messages');
    }
};
