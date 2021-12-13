<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlaPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sla_plan', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('reply_deadline')->nullable();
            $table->string('due_deadline')->nullable();
            $table->integer('sla_status')->default('0');
            $table->integer('is_default')->default('0');
            $table->integer('is_deleted')->default('0');
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
        Schema::dropIfExists('sla_plan');
    }
}
