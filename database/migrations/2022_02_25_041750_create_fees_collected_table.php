<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeesCollectedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feesCollected', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('userID')->unsigned();
            $table->integer('fee')->unsigned();
            $table->boolean('wasThisEnough');
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feesCollected');
    }
}
