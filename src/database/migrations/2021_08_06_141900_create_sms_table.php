<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nexmo_id')->unsigned()->index()->nullable();
            $table->string('sender_title')->nullable();
            $table->string('sender_phone')->nullable();
            $table->string('receiver_title')->nullable();
            $table->string('receiver_phone')->nullable();
            $table->string('country')->nullable();
            $table->integer('quantity');
            $table->timestamp('sent_at');
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
        Schema::dropIfExists('sms');
    }
}
