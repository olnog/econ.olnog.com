<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStartingSkillPointsInLabor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('labor', function (Blueprint $table) {
            $table->integer('startingSkillPoints')->unsigned()->default(4)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('labor', function (Blueprint $table) {
            //
        });
    }
}
