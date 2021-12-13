<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('sender_id')->nullable();
            $table->integer('receiver_id')->nullable();
            $table->string('slug')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->string('noti_type')->nullable();
            $table->text('noti_data')->nullable();
            $table->text('noti_title')->nullable();
            $table->text('noti_desc')->nullable();
            $table->string('noti_icon')->default('fa fa-link');
            $table->string('btn_class')->default('btn-danger');
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
        Schema::dropIfExists('notifications');
    }
}
