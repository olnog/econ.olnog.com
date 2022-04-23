<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNextRankAndRankInActions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->bigInteger('nextRank')->unsigned()->default(150)->change();
            $table->integer('rank')->unsigned()->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('actions', function (Blueprint $table) {
            //
        });
    }
}
