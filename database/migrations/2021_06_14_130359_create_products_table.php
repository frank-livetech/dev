<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('specs')->nullable();
            $table->text('extra_details')->nullable();
            $table->text('short_desc')->nullable();
            $table->text('long_desc')->nullable();

            $table->string('sell_as_sub')->nullable();
            $table->integer('how_often_quantity')->nullable();
            $table->integer('how_often_terms')->nullable();
            $table->string('shipping')->nullable();
            $table->string('upc_gtin')->nullable();

            $table->string('isbn')->nullable();
            $table->integer('part_no')->nullable();
            $table->integer('brand_no')->nullable();
            $table->integer('sku')->nullable();
            $table->integer('internal_id')->nullable();

            $table->integer('product_id')->nullable();
            $table->integer('vendor_price')->nullable();
            $table->integer('oor_sale_price')->nullable();
            $table->integer('oor_regular_price')->nullable();
            $table->integer('msrp')->nullable();

            $table->integer('wholesale_price')->nullable();
            $table->string('in_stock')->nullable();
            $table->integer('stock_quantity')->nullable();
            $table->integer('is_stock_on_mul_loc')->nullable();
            $table->integer('length')->nullable();

            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->string('shipping_type')->nullable();
            $table->integer('setup_fee_required')->nullable();

            $table->string('has_special_con')->nullable();
            $table->text('special_condition_text')->nullable();
            $table->string('feature_image')->nullable();
            $table->string('feature_video')->nullable();
            $table->string('video_link')->nullable();

            $table->integer('is_type')->nullable();
            $table->integer('is_submit')->nullable();
            $table->integer('is_deleted')->nullable();
            $table->integer('created_by')->nullable();

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
        Schema::dropIfExists('products');
    }
}
