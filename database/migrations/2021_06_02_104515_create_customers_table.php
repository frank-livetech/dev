<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->integer('woo_id')->nullable();
            $table->string('account_id')->nullable();
            $table->string('username')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();

            $table->string('cust_password')->nullable();
            $table->tinyInteger('is_paying_customer')->default(0);
            $table->text('avatar_url')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_type')->nullable();
            $table->string('address')->nullable();

            $table->string('apt_address')->nullable();
            $table->bigInteger('company_id')->nullable();
            $table->string('country')->nullable();
            $table->string('cust_state')->nullable();
            $table->string('cust_city')->nullable();

            $table->string('cust_zip')->nullable();
            $table->string('vertical')->nullable();
            $table->integer('business_residential')->default(0);
            $table->string('fb')->nullable();
            $table->string('insta')->nullable();

            $table->string('twitter')->nullable();
            $table->string('pinterest')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->tinyInteger('is_deleted')->default(0);
            $table->string('bill_st_add')->nullable();

            $table->string('bill_apt_add')->nullable();
            $table->integer('bill_add_country')->nullable();
            $table->string('bill_add_city')->nullable();
            $table->integer('bill_add_state')->nullable();
            $table->string('bill_add_zip')->nullable();

            $table->integer('is_bill_add')->nullable();
            $table->integer('cust_type')->nullable();
            $table->string('linkedin')->nullable();
            $table->integer('has_account')->default(0);


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
        Schema::dropIfExists('customers');
    }
}
