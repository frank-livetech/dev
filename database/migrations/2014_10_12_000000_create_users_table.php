<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('account_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->mediumText('password');

            $table->rememberToken();
            $table->integer('user_type')->default(0);
            $table->string('tags')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('status')->nullable();

            $table->string('theme')->nullable();
            $table->string('text_dark')->nullable();
            $table->string('bg_dark')->nullable();
            $table->string('text_light')->nullable();
            $table->string('bg_light')->nullable();

            $table->timestamp('last_seen_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('is_deleted')->default(0);
            $table->integer('deleted_by')->nullable();

            $table->string('sms')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('address')->nullable();
            $table->string('apt_address')->nullable();
            $table->string('phone_number')->nullable();

            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('twitter')->nullable();
            $table->string('pinterest')->nullable();

            $table->string('fb')->nullable();
            $table->string('insta')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('job_title')->nullable();
            $table->double('zip')->nullable();

            $table->text('notes')->nullable();
            $table->text('device_token')->nullable();
            $table->mediumText('alt_pwd')->nullable();
            $table->string('website')->nullable();
            $table->integer('is_support_staff')->default(0);
            
            $table->string('google_id')->nullable();
            $table->string('company_id')->nullable();
            $table->integer('privacy_policy')->default(0);
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
        Schema::dropIfExists('users');
    }
}
