<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVerifySearchedToSmsNexmoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sms_nexmo', function (Blueprint $table) {
            $table->boolean('verify_search')->default(0)->nullable()->after('network');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sms_nexmo', function (Blueprint $table) {
            $table->dropColumn('verify_search');
        });
    }
}
