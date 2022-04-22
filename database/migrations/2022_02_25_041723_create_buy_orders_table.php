<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyOrders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('itemTypeID')->unsigned();
            $table->bigInteger('quantity')->unsigned();
            $table->bigInteger('cost')->unsigned();
            $table->timestamp('filled_at');
            $table->bigInteger('filledBy')->unsigned();
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
        Schema::dropIfExists('buyOrders');
    }
}
