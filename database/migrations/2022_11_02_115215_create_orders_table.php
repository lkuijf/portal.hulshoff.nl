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
            $table->unsignedBigInteger('hulshoff_user_id');
            $table->foreign('hulshoff_user_id')->references('id')->on('hulshoff_users')->onDelete('cascade');
            $table->boolean('is_reservation')->default(0);
            $table->string('orderCodeKlant', 30); // nog niet duidelijk wat het gebruik hiervan is, overgenomen van WMS
            $table->string('orderCodeAflever', 50); // nog niet duidelijk wat het gebruik hiervan is, overgenomen van WMS
            $table->integer('aleverenDatum'); // overgenomen van WMS
            $table->integer('aleverenTijd'); // overgenomen van WMS
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
        Schema::dropIfExists('orders');
    }
};
