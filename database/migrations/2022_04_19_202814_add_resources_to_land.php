<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResourcesToLand extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('land', function (Blueprint $table) {
		$table->bigInteger('stone')->unsigned();
		$table->bigInteger('iron')->unsigned();
		$table->bigInteger('coal')->unsigned();
		$table->bigInteger('copper')->unsigned();
		$table->bigInteger('oil')->unsigned();
		$table->bigInteger('sand')->unsigned();
		$table->bigInteger('uranium')->unsigned();
		$table->bigInteger('logs')->unsigned();
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
