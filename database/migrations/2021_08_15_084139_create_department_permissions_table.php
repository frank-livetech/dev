<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_permissions', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->nullable();
            $table->integer("dept_id")->nullable();
            $table->string("name")->nullable();
            $table->integer("permitted")->default('0');
            $table->integer("updated_by")->nullable();
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
        Schema::dropIfExists('department_permissions');
    }
}
