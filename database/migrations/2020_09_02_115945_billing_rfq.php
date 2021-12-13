<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BillingRfq extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('billing_rfq')){
            Schema::create('billing_rfq', function (Blueprint $table) {
                $table->id();
                $table->string('subject')->nullable();
                $table->string('to_mails')->nullable();
                $table->string('rfq_details')->nullable();
                $table->string('purchase_order')->nullable();
                $table->text('contact_mail')->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable(); 
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
        Schema::dropIfExists('billing_rfq');
    }
}
