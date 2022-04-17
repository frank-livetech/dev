<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('companies')){
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->integer('woo_id')->nullable();
                $table->string('name')->nullable();
                $table->string('poc_first_name')->nullable();
                $table->string('poc_last_name')->nullable();
                $table->string('email')->nullable();

                $table->string('address')->nullable();
                $table->text('apt_address')->nullable();
                $table->string('cmp_bill_add')->nullable();
                $table->string('cmp_ship_add')->nullable();
                $table->string('phone')->nullable();

                $table->string('cmp_country')->nullable();
                $table->string('cmp_state')->nullable();
                $table->string('cmp_city')->nullable();
                $table->double('cmp_zip')->nullable();
                $table->text('bill_st_add')->nullable();

                $table->text('bill_apt_add')->nullable();
                $table->integer('bill_add_country')->nullable();
                $table->integer('bill_add_state')->nullable();
                $table->string('bill_add_city')->nullable();
                $table->string('bill_add_zip')->nullable();

                $table->integer('is_bill_add')->nullable();
                $table->text('notes')->nullable();
                $table->string('fb')->nullable();
                $table->string('insta')->nullable();
                $table->string('twitter')->nullable();

                $table->string('pinterest')->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->timestamp('deleted_at')->nullable();
                $table->integer('is_deleted')->default(0);

                $table->text('com_logo')->nullable();
                $table->integer('is_default')->default(0);
                $table->string('website')->nullable();
                $table->string('com_sla')->nullable();


                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(!Schema::hasTable('companies')){
            Schema::dropIfExists('companies');
        }
    }
}
