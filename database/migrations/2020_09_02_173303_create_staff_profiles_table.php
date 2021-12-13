<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('role_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('tags')->nullable();
            $table->string('sms')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('address')->nullable();
            $table->integer('zip')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('job_title')->nullable();
            $table->text('notes')->nullable();
            $table->string('pinterest')->nullable();
            $table->string('twitter')->nullable();
            $table->string('fb')->nullable();
            $table->string('insta')->nullable();
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
        Schema::dropIfExists('staff_profiles');
    }
}
