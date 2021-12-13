<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_queues', function (Blueprint $table) {
            $table->id();
            $table->string('mail_queue_address')->nullable();
            $table->string('queue_type')->nullable();
            $table->string('protocol')->nullable();
            $table->string('queue_template')->nullable();
            $table->string('is_enabled')->nullable();

            $table->string('php_mailer')->nullable();

            $table->string('mailserver_hostname')->nullable();
            $table->string('mailserver_port')->nullable();
            $table->text('mailserver_username')->nullable();
            $table->text('mailserver_password')->nullable();
            $table->string('from_name')->nullable();

            $table->string('from_mail')->nullable();
            $table->integer('mail_dept_id')->nullable();
            $table->integer('mail_type_id')->nullable();
            $table->integer('mail_status_id')->nullable();
            $table->integer('mail_priority_id')->nullable();

            $table->string('registration_required')->nullable();
            $table->string('autosend')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('updated_by')->nullable();

            $table->integer('deleted_by')->nullable();
            $table->integer('is_deleted')->default(0);
            $table->string('outbound')->default('0');


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
        Schema::dropIfExists('email_queues');
    }
}
