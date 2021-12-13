<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustCcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cust_cc', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->nullable();
            $table->string('payment_token')->nullable();
            $table->string('customer_vault_id')->nullable();
            $table->string('billing_id')->nullable();
            $table->string('fname')->nullable();

            $table->string('lname')->nullable();
            $table->string('address1')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();

            $table->string('exp')->nullable();
            $table->string('card_type')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('cust_cc');
    }
}
