<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingLeasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_leases', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
	    $table->boolean('active')->default(true);
	    $table->bigInteger('contractID')->unsigned();
	    $table->bigInteger('buildingID')->unsigned();
	    $table->bigInteger('userID')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('building_leases');
    }
}
