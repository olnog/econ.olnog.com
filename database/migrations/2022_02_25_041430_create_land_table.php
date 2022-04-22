<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('land', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->char('type', 16);
            $table->unsignedBigInteger('userID'); //unsigned
            $table->boolean('protected')->default(false);
            $table->unsignedBigInteger('hostileTakeoverBy');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('land');
    }
}
