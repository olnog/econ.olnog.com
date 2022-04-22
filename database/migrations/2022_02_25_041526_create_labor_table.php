<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaborTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labor', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('workHours')->unsigned();
            $table->bigInteger('equipped')->unsigned();
            $table->tinyInteger('availableSkillPoints')->unsigned();
            $table->tinyInteger('maxSkillPoints')->unsigned();
            $table->tinyInteger('startingSkillPoints')->unsigned();
            $table->tinyInteger('allocatedSkillPoints')->unsigned();
            $table->bigInteger('itemCapacity')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('labor');
    }
}
