<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsNexmoReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_nexmo_receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message_id')->nullable();
            $table->timestamp('message_timestamp')->nullable();
            $table->string('msisdn')->nullable();
            $table->string('scts')->nullable();
            $table->decimal('price', 20, 6)->nullable();
            $table->string('network')->nullable();
            $table->string('status')->nullable();
            $table->integer('error_code')->nullable();
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
        Schema::dropIfExists('sms_nexmo_receipts');
    }
}
