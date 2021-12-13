<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketFollowUpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_follow_up', function (Blueprint $table) {
            $table->id();

            $table->integer('ticket_id')->nullable();
            $table->char('schedule_type')->nullable();
            $table->timestamp('custom_date')->nullable();
            $table->integer('schedule_time')->nullable();
            $table->integer('follow_up_dept_id')->nullable();

            $table->integer('follow_up_priority')->nullable();
            $table->integer('follow_up_assigned_to')->nullable();
            $table->integer('follow_up_status')->nullable();
            $table->integer('follow_up_type')->nullable();
            $table->integer('follow_up_project')->nullable();

            $table->string('follow_up_notes')->nullable();
            $table->integer('is_recurring')->default('0');
            $table->time('recurrence_time')->nullable();
            $table->time('recurrence_time2')->nullable();
            $table->string('recurrence_pattern')->nullable();

            $table->timestamp('recurrence_start')->nullable();
            $table->string('recurrence_end_type')->nullable();
            $table->string('recurrence_end_val')->nullable();
            $table->string('date')->nullable();            
            $table->tinyInteger('passed')->default('0');

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->tinyInteger('is_deleted')->default('0');
            $table->timestamp('deleted_at')->nullable();            
            $table->integer('deleted_by')->nullable();
            
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
        Schema::dropIfExists('ticket_follow_up');
    }
}
