<?php

use App\BatchMessage;
use App\Extension;
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
        Schema::create('batch_message_extension', function (Blueprint $table) {
            $table->foreignIdFor(BatchMessage::class);
            $table->foreignIdFor(Extension::class);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batch_message_extension');
    }
};
