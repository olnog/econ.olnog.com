<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsForBuildingTypesTableToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildingTypes', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
            $table->text('skill')->nullable()->change();
            $table->text('actions')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buildingTypes', function (Blueprint $table) {
            //
        });
    }
}
