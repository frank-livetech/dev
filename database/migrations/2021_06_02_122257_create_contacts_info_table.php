<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts_info', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company')->nullable();
            $table->string('email_1')->unique();
            $table->string('email_2')->nullable();

            $table->string('office_num')->nullable();
            $table->string('cell_num')->unique();
            $table->string('street_addr_1')->nullable();
            $table->string('street_addr_2')->nullable();
            $table->string('city_name')->nullable();

            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country_name')->nullable();
            $table->mediumText('notes')->nullable();
            $table->dateTime('last_called')->nullable();

            $table->string('email_list_tags')->nullable();
            $table->enum('active_customer', ['Yes', 'No'])->default('No');
            $table->string('tag_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('is_deleted')->nullable();
            $table->integer('updated_by')->nullable();

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
        Schema::dropIfExists('contacts_info');
    }
}
