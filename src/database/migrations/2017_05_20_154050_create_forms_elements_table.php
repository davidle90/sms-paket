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
			$table->integer('form_id')->unsigned()->index();
			$table->integer('list_element_id')->unsigned()->index();
			$table->integer('section_id')->unsigned()->index();
			$table->string('label');
			$table->text('help_text');
			$table->string('required_text');
			$table->boolean('attr_required');
			$table->boolean('attr_disabled');
			$table->boolean('attr_readonly');
			$table->boolean('attr_novalidate');
			$table->boolean('attr_autocomplete');
			$table->boolean('attr_multiple');
			$table->boolean('hidden');
			$table->boolean('other');
			$table->text('default_value')->nullable();
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
        Schema::dropIfExists('forms_elements');
    }
}
