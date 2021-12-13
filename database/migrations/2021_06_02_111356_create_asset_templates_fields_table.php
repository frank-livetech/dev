<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetTemplatesFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_templates_fields', function (Blueprint $table) {
            $table->id();
            $table->integer('asset_forms_id')->unique();
            $table->string('label')->nullable();
            $table->string('type')->nullable();
            $table->string('placeholder')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('required')->default(0);
            $table->tinyInteger('is_multi')->default(0);
            $table->text('options')->nullable();
            $table->integer('col_width')->default(12);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('is_deleted')->default(0);
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
        Schema::dropIfExists('asset_templates_fields');
    }
}
