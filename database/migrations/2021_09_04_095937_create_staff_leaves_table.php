<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_leaves', function (Blueprint $table) {
            $table->id();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->longText('reason')->nullable();
            $table->integer('status')->default('0');
            $table->integer('requested_by')->nullable();
            $table->integer('processed_by')->nullable();
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
        Schema::dropIfExists('staff_leaves');
    }
}
