<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRobotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('robots', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
	    $table->integer('skillTypeID')->unsigned()->nullable();
	    $table->integer('equipped')->unsigned()->nullable();
	    $table->integer('num')->unsigned()->default(1);
	    $table->integer('uses')->unsigned()->default(1000);
	    $table->integer('userID')->unsigned();
	    $table->string('defaultAction', 64)->nullable();
	    $table->boolean('doDefaultWhenAble')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('robots');
    }
}
