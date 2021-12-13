<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('project_type')->nullable();
            $table->text('project_slug')->nullable();
            $table->text('project_roadmap_table')->nullable();
            $table->integer('folder_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('project_manager_id')->nullable();
            $table->text('description')->nullable();
            $table->string('hostname')->nullable();
            $table->string('site_type')->nullable();
            $table->string('url')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('is_deleted')->default('0');
            $table->integer('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
