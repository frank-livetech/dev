<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('woo_id')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('custom_id')->nullable();
            $table->string('number')->nullable();
            $table->string('order_key')->nullable();

            $table->string('created_via')->nullable();
            $table->string('version')->nullable();
            $table->string('status')->nullable();
            $table->integer('is_published')->default('0');
            $table->string('status_text')->nullable();

            $table->double('fees')->nullable();
            $table->double('discount')->nullable();
            $table->double('tax')->nullable();
            $table->string('currency')->nullable();
            $table->double('discount_total')->nullable();

            $table->double('discount_tax')->nullable();
            $table->double('shipping_total')->nullable();
            $table->double('shipping_tax')->nullable();
            $table->double('cart_tax')->nullable();
            $table->double('total')->default('0');

            $table->double('grand_total')->default('0');
            $table->double('total_tax')->nullable();
            $table->tinyInteger('prices_include_tax');
            $table->integer('customer_id')->nullable();
            $table->integer('customer_woo_id')->nullable();

            $table->string('customer_ip_address')->nullable();
            $table->text('customer_user_agent')->nullable();
            $table->mediumText('customer_note')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_method_title')->nullable();

            $table->string('transaction_id')->nullable();
            $table->dateTime('date_paid')->nullable();
            $table->dateTime('date_completed')->nullable();           
            $table->text('cart_hash')->nullable();
            $table->string('ord_notes')->nullable();
            
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
        Schema::dropIfExists('orders');
    }
}
