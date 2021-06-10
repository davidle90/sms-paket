<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsResponsesDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms_responses_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->integer('response_id')->unsigned();
            $table->integer('element_id')->unsigned();
            $table->morphs('sourceable');
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
        Schema::dropIfExists('forms_responses_data');
    }
}
