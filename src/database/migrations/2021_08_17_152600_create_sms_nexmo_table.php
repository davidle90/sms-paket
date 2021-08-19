<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsNexmoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_nexmo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sms_id')->unsigned()->index()->nullable();
            $table->string('message_id')->nullable();
            $table->integer('status')->nullable();
            $table->string('to')->nullable();
            $table->decimal('balance', 20, 6)->nullable();
            $table->decimal('price', 20, 6)->nullable();
            $table->string('network')->nullable();
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
        Schema::dropIfExists('sms_nexmo');
    }
}
