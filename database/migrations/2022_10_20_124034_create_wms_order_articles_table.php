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
        Schema::create('wms_order_articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wms_order_id');
            $table->foreign('wms_order_id')->references('id')->on('wms_orders')->onDelete('cascade');
            $table->string('artikelCode', 30);
            $table->integer('stuksUitgeleverd');
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
        Schema::dropIfExists('wms_order_articles');
    }
};
