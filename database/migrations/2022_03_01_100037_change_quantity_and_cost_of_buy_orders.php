<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeQuantityAndCostOfBuyOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buyOrders', function (Blueprint $table) {
		$table->bigInteger('quantity')->unsigned()->default(10)->change();
		$table->bigInteger('cost')->unsigned()->default(10)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buyOrders', function (Blueprint $table) {
            //
        });
    }
}
