<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('direct_line')->nullable();
            $table->string('phone')->nullable();
            $table->string('categories')->nullable();
            $table->string('tags')->nullable();
            $table->string('has_account')->nullable();
            $table->text('address')->nullable();
            $table->integer('comp_id')->nullable();
            $table->string('comp_name')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->integer('zip')->nullable();
            $table->string('twitter')->nullable();
            $table->string('fb')->nullable();
            $table->string('insta')->nullable();
            $table->string('pinterest')->nullable();
            $table->string('cmp_bill_add')->nullable();
            $table->string('cmp_ship_add')->nullable();
            $table->string('cmp_pr_add')->nullable();
            $table->text('notes')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();            
            // $table->string('name');
            // $table->string('company');
            // $table->string('website');
            // $table->string('email');
            // $table->string('direct_line');
            // $table->string('phone');
            // $table->string('categories');
            // $table->string('tags');
            // $table->integer('has_account')->default(0);
            // $table->integer('created_by');
            // $table->integer('updated_by');
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
        Schema::dropIfExists('vendors');
    }
}
