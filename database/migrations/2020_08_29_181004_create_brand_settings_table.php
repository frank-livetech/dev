<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('brand_settings')){
            Schema::create('brand_settings', function (Blueprint $table) {
                $table->id();
                $table->string('site_title')->nullable();
                $table->string('site_logo_title')->nullable();
                $table->string('site_logo')->nullable();
                $table->string('login_logo')->nullable();
                $table->string('customer_logo')->nullable();
                $table->string('company_logo')->nullable();
                $table->string('user_logo')->nullable();
                $table->string('site_favicon')->nullable();
                $table->string('site_footer')->nullable();
                $table->string('site_version')->nullable();
                $table->string('site_domain')->nullable();
                $table->string('text_dark')->nullable();
                $table->string('text_light')->nullable();
                $table->string('bg_dark')->nullable();
                $table->string('bg_light')->nullable();
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
        Schema::dropIfExists('brand_settings');
        // $table->dropColumn('site_version');
    }
}
