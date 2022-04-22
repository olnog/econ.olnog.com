<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBuildingsProperlyDuh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
		$table->integer('uses')->unsigned()->nullable()->change();
		$table->integer('totalUses')->unsigned()->nullable()->change();
		$table->integer('repairedTo')->unsigned()->nullable()->change();
		$table->string('durabilityCaption', 32)->nullable()->change();
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
