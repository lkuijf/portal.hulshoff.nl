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
        Schema::create('product_lotcodes', function (Blueprint $table) {
            // $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->string('lotcode', 30);
            $table->integer('voorraad')->default(0);
            $table->timestamps();
            $table->unique(['product_id', 'lotcode']);
            $table->primary(['product_id', 'lotcode']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_lotcodes');
    }
};
