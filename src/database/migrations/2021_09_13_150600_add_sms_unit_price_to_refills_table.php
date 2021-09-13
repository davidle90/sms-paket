<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSmsUnitPriceToRefillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sms_refills', function (Blueprint $table) {
            $table->decimal('sms_unit_price', 6,4)->nullable()->after('count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sms_refills', function (Blueprint $table) {
            $table->dropColumn('sms_unit_price');
        });
    }
}
