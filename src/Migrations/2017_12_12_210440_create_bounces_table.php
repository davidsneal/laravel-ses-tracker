<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBouncesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laravel_ses_tracker_email_bounces', function (Blueprint $table) {
            $table->id();
            $table->string('message_id');
            $table->unsignedBigInteger('sent_email_id');
            $table->string('type');
            $table->string('email');
            $table->dateTime('bounced_at');
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
        Schema::dropIfExists('laravel_ses_tracker_email_bounces');
    }
}
