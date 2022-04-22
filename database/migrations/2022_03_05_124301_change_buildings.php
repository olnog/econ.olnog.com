<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBuildings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
		$table->integer('uses')->unsigned()->change();
		$table->integer('totalUses')->unsigned()->change();
		$table->integer('repairedTo')->unsigned()->change();
		$table->string('durabilityCaption', 32)->unsigned()->change();
		$table->integer('wheat')->unsigned()->nullable();
		$table->datetime('harvestAfter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buildings', function (Blueprint $table) {
            //
        });
    }
}
