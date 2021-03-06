<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailOpensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_email_opens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sent_email_id')->index();
            $table->string('email');
            $table->unsignedBigInteger('email_id')->nullable()->index();
            $table->uuid('beacon_identifier');
            $table->string('url');
            $table->dateTime('opened_at')->nullable();
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
        Schema::dropIfExists('sent_email_opens');
    }
}
