<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('dept_id')->nullable();
            $table->integer('priority')->nullable();
            $table->integer('assigned_to')->nullable();
            $table->string('subject')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('ticket_detail')->nullable();
            $table->integer('status')->nullable();
            $table->integer('type')->nullable();
            $table->string('coustom_id')->nullable();
            $table->timestamp('deadline')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('is_deleted')->default('0');
            $table->integer('trashed')->default('0');
            $table->timestamp('deleted_at')->nullable();
            $table->integer('is_flagged')->default('0');
            $table->integer('seq_custom_id')->nullable();
            $table->string('reply_deadline')->nullable();
            $table->string('resolution_deadline')->nullable();
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
        Schema::dropIfExists('tickets');
    }
}
