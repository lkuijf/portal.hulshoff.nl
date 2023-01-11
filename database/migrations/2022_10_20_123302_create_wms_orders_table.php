<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wms_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('bericht_id')->unique();
            $table->string('klantCode', 30);
            $table->foreign('klantCode')->references('klantCode')->on('customers')->onDelete('cascade');
            $table->string('orderCodeKlant', 30);
            $table->string('orderCodeAflever', 50);
            $table->integer('orderNr')->nullable();
            $table->integer('ataAleverenDatum');
            $table->integer('ataAleverenTijd');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wms_orders');
    }
};
