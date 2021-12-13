<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->integer('ticket_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('msgno')->nullable();
            $table->longText('reply')->nullable();
            $table->text('cc')->nullable();
            $table->dateTime('date')->nullable();
            $table->integer('is_published')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('ticket_replies');
    }
}
