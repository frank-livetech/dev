<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_items', function (Blueprint $table) {
            $table->id();

            $table->integer('subscription_id')->nullable();
            $table->string('name')->nullable();
            $table->string('sku')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('variation_id')->nullable();

            $table->integer('order_id')->nullable();
            $table->integer('woo_order_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('tax_class')->nullable();
            $table->double('price')->default('0');

            $table->double('subtotal')->default('0');
            $table->double('subtotal_tax')->default('0');
            $table->double('fees')->nullable();
            $table->double('shipping')->nullable();
            $table->double('discount')->nullable();

            $table->double('tax')->nullable();
            $table->double('total')->default('0');
            $table->double('total_tax')->default('0');
            $table->string("item_details")->nullable();
            $table->string("routine")->nullable();

            $table->string("subscription_cost")->nullable();
            $table->string("item_end_date")->nullable();
            $table->longText('meta');
            $table->integer('created_by');
            $table->integer('updated_by');

            $table->integer('is_deleted')->default(0);
            $table->dateTime('deleted_at');
            $table->integer('deleted_by');

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
        Schema::dropIfExists('line_items');
    }
}
