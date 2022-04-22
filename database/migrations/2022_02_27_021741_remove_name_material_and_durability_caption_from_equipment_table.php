<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveNameMaterialAndDurabilityCaptionFromEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipment', function (Blueprint $table) {
		$table->renameColumn('equipmentTypeID', 'itemTypeID');
		$table->dropColumn(['name', 'material', 'durabilityCaption']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipment', function (Blueprint $table) {
            //
        });
    }
}
