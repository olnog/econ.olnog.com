<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToActions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->bigInteger('userID')->unsigned();
            $table->integer('actionTypeID')->unsigned();
            $table->bigInteger('totalUses')->unsigned()->default(0);
            $table->bigInteger('nextRank')->unsigned()->default(100);
            $table->integer('rank')->unsigned()->default(0);
            $table->boolean('unlocked')->default(false);
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
