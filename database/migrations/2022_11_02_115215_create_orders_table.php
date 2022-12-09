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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hulshoff_user_id')->nullable();
            $table->foreign('hulshoff_user_id')->references('id')->on('hulshoff_users')->nullOnDelete();
            $table->boolean('is_reservation')->default(0);
            $table->string('orderCodeKlant', 30)->nullable()->default(null); // nog niet duidelijk wat het gebruik hiervan is, overgenomen van WMS
            $table->string('orderCodeAflever', 50)->nullable()->default(null); // nog niet duidelijk wat het gebruik hiervan is, overgenomen van WMS
            $table->string('aleverenDatum', 8); // overgenomen van WMS: 20221123.
            $table->string('aleverenTijd', 4); // overgenomen van WMS: 1330. changed to varchar because of leading zeros.
            $table->timestamps();
        });
    }
    // $table->string('klantCode', 30)->nullable();
    // $table->foreign('klantCode')->references('klantCode')->on('customers')->nullOnDelete();

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
