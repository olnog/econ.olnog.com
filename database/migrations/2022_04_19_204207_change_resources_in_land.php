<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeResourcesInLand extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('land', function (Blueprint $table) {
		$table->bigInteger('stone')->unsigned()->nullable()->change();
		$table->bigInteger('iron')->unsigned()->nullable()->change();
		$table->bigInteger('coal')->unsigned()->nullable()->change();
		$table->bigInteger('copper')->unsigned()->nullable()->change();
		$table->bigInteger('oil')->unsigned()->nullable()->change();
		$table->bigInteger('sand')->unsigned()->nullable()->change();
		$table->bigInteger('uranium')->unsigned()->nullable()->change();
		$table->bigInteger('logs')->unsigned()->nullable()->change();
		$table->boolean('depleted')->default(false);
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
