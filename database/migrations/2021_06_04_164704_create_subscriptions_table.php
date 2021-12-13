<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->integer('woo_id');
            $table->integer('parent_id')->nullable();
            $table->string('status')->nullable();
            $table->string('order_key')->nullable();
            $table->string('currency')->nullable();

            $table->string('version')->nullable();
            $table->tinyInteger('prices_include_tax')->default('0');
            $table->integer('customer_id')->nullable();

            $table->double('discount_total')->nullable();
            $table->double('discount_tax')->nullable();
            $table->double('shipping_total')->nullable();
            $table->double('shipping_tax')->nullable();
            $table->double('cart_tax')->nullable();

            $table->double('total')->nullable();
            $table->double('total_tax')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_method_title')->nullable();
            $table->string('transaction_id')->nullable();

            $table->string('customer_ip_address')->nullable();
            $table->text('customer_user_agent')->nullable();
            $table->string('created_via')->nullable();
            $table->string('customer_note')->nullable();
            $table->dateTime('date_completed')->nullable();

            $table->dateTime('date_paid')->nullable();  
            $table->text('cart_hash')->nullable();
            $table->string('billing_period')->nullable();
            $table->string('billing_interval')->nullable();
            $table->dateTime('start_date')->nullable();
            
            $table->dateTime('trial_end_date')->nullable();  
            $table->dateTime('next_payment_date')->nullable();
            $table->dateTime('end_date')->nullable();
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
        Schema::dropIfExists('subscriptions');
    }
}
