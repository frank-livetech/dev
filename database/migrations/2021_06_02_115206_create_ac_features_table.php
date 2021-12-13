<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_features', function (Blueprint $table) {
            $table->increments('f_id');
            $table->string('title')->nullable();
            $table->string('route')->nullable();
            $table->integer('sequence')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('is_active')->default(0);
            $table->string('role_id')->nullable();
            $table->integer('feature_type')->nullable();
            $table->string('menu_icon')->nullable();
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
        Schema::dropIfExists('ac_features');
    }
}
