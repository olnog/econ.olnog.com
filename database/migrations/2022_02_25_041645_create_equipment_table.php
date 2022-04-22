<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('equipmentTypeID')->unsigned();
            $table->bigInteger('userID')->unsigned();
            $table->integer('uses')->unsigned();
            $table->integer('totalUses')->unsigned();
            $table->char('durabilityCaption', 32);
            $table->char('material', 16);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment');
    }
}
