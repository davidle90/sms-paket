<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ListFormsElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_forms_elements', function (Blueprint $table) {
            $table->increments('id');
			$table->string('label');
			$table->string('description');
			$table->string('template');
			$table->boolean('enabled');
			$table->integer('sort_order')->unsigned();
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
        Schema::dropIfExists('list_forms_elements');
    }
}
