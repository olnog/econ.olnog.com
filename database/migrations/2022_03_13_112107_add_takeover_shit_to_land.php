<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTakeoverShitToLand extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('land', function (Blueprint $table) {
		$table->integer('hostileTakeoverNum')->unsigned()->default(0);
		$table->integer('bribe')->unsigned()->default(0);
		$table->dateTime('changedOwnerAt')->nullable();
		$table->integer('valuation')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('land', function (Blueprint $table) {
            //
        });
    }
}
