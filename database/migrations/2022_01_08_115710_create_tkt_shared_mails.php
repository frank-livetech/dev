<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTktSharedMails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tkt_shared_mails', function (Blueprint $table) {
            $table->id();
            $table->text('email')->nullable();
            $table->integer('ticket_id')->nullable();
            $table->integer('mail_type')->default(0);
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
        Schema::dropIfExists('tkt_shared_mails');
    }
}
