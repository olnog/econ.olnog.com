<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
	    $table->char('category', 32);
	    $table->bigInteger('itemTypeID')->unsigned();
	    $table->integer('price')->unsigned();
	    $table->char('until', 32);
	    $table->bigInteger('userID')->unsigned();
	    $table->integer('condition')->unsigned();
	    $table->integer('conditionFulfilled')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
