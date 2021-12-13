<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('version')->nullable();
            $table->string('title')->nullable();
            $table->integer('project_id')->nullable();
            $table->string('task_type')->nullable();

            $table->mediumText('task_description')->nullable();
            $table->string('task_status')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('assign_to')->nullable();
            $table->text('remarks')->nullable();
            
            $table->integer('remarks_by')->nullable();
            $table->string('other_tech')->nullable();
            $table->string('work_tech')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

            $table->integer('is_deleted')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->string('completion_time')->nullable();
            $table->string('task_priority')->nullable();
            $table->string('estimated_time')->nullable();

            $table->integer('sort_id')->nullable();
            $table->integer('worked_time')->default('0');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->date('start_date')->nullable();
            
            $table->integer('reverted_count')->default('0');
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
        Schema::dropIfExists('tasks');
    }
}
