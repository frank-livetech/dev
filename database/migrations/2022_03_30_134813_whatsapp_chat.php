<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WhatsappChat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('whatsapp_chat', function (Blueprint $table) {

            $table->id();

            $table->integer('from')->nullable();
            $table->integer('to')->nullable();
            $table->text('body')->nullable();

            $table->integer('num_media')->default('0');
            $table->text('media_url')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('whatsapp_chat');
    }
}
