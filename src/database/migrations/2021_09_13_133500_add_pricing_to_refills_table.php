<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPricingToRefillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sms_refills', function (Blueprint $table) {
            $table->integer('vat_rate')->nullable()->after('count');
            $table->decimal('price_incl_vat', 20,6)->nullable()->after('vat_rate');
            $table->decimal('price_excl_vat', 20,6)->nullable()->after('price_incl_vat');
            $table->decimal('price_vat', 20,6)->nullable()->after('price_excl_vat');
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
            $table->dropColumn('vat_rate');
            $table->dropColumn('price_incl_vat');
            $table->dropColumn('price_excl_vat');
            $table->dropColumn('price_vat');
        });
    }
}
