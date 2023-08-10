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
        Schema::create('custom_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->string('straat', 50)->default('');
            $table->string('huisnummer', 5)->default('');
            $table->string('postcode', 10)->default('');
            $table->string('plaats', 50)->default('');
            $table->string('contactpersoon', 50)->default('');
            $table->string('telefoon', 25)->default('');
            $table->text('informatie');
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
        Schema::dropIfExists('custom_addresses');
    }
};
