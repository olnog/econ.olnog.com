<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeContracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
		$table->bigInteger('buildingTypeID')->unsigned()->nullable();
		$table->bigInteger('buildingID')->unsigned()->nullable();
		$table->string('action', 32)->nullable();
		$table->string('until', 32)->nullable()->change();
		$table->bigInteger('itemTypeID')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            //
        });
    }
}
