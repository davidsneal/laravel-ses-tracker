<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_email_links', function (Blueprint $table) {
            $table->id();
            $table->uuid('link_identifier');
            $table->unsignedBigInteger('sent_email_id')->index();
            $table->string('original_url');
            $table->unsignedBigInteger('email_id')->nullable()->index();
            $table->boolean('clicked')->default(false);
            $table->integer('click_count')->default(0);
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
        Schema::dropIfExists('sent_email_links');
    }
}
