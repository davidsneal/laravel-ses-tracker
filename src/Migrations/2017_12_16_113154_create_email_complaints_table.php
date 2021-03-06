<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_email_complaints', function (Blueprint $table) {
            $table->id();
            $table->string('message_id');
            $table->unsignedBigInteger('sent_email_id')->index();
            $table->string('type');
            $table->string('email');
            $table->dateTime('complained_at');
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
        Schema::dropIfExists('sent_email_complaints');
    }
}
