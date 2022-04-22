<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSettingsToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
		$table->boolean('soundSetting')->default(true);
		$table->boolean('eatFoodSetting')->default(true);
		$table->boolean('useHerbMedsSetting')->default(true);
		$table->boolean('useBioMedsSetting')->default(true);
		$table->boolean('useNanoMedsSetting')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
