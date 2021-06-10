<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms_elements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->integer('type_id')->unsigned();
            $table->string('validator')->nullable();
            $table->integer('table_id')->unsigned()->nullable();
            $table->integer('options_id')->unsigned()->nullable();
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
        Schema::dropIfExists('forms_elements');
    }
}
