<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_notes', function (Blueprint $table) {
            $table->id();
            $table->integer('ticket_id')->nullable();
            $table->string('color')->nullable();
            $table->string('type')->nullable();
            $table->mediumText('note')->nullable();
            $table->string('visibility')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->tinyInteger('is_deleted')->default('0');
            $table->integer('followup_id')->nullable();
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
        Schema::dropIfExists('ticket_notes');
    }
}
