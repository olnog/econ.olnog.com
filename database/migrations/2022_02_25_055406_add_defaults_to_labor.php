<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultsToLabor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('labor', function (Blueprint $table) {
          $table->integer('workHours')->unsigned()->default(10000)->change();
          $table->bigInteger('equipped')->unsigned()->default(null)->change();
          $table->smallInteger('availableSkillPoints')->unsigned()->default(6)->change();
          $table->smallInteger('maxSkillPoints')->unsigned()->default(30)->change();
          $table->smallInteger('startingSkillPoints')->unsigned()->default(6)->change();
          $table->smallInteger('allocatedSkillPoints')->unsigned()->default(0)->change();
          $table->bigInteger('itemCapacity')->unsigned()->default(100)->change();
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
