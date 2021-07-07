<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsElementsTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms_elements_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label');
            $table->text('description');
            $table->string('slug');
            $table->boolean('enabled')->default(1);
            $table->integer('sort_order')->unsigned();
            $table->string('data_type');
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
        Schema::dropIfExists('forms_elements_types');
    }
}
