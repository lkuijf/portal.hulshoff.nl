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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('klantCode', 30);
            $table->foreign('klantCode')->references('klantCode')->on('customers')->onDelete('cascade');
            $table->string('artikelCode', 30);
            $table->string('omschrijving', 100);
            $table->unsignedBigInteger('productgroup_id');
            $table->foreign('productgroup_id')->references('id')->on('productgroups')->onDelete('cascade');
            $table->integer('stuksPerBundel');
            $table->float('prijs', 8, 2);
            $table->unsignedBigInteger('producttype_id');
            $table->foreign('producttype_id')->references('id')->on('producttypes')->onDelete('cascade');
            $table->integer('minimaleVoorraad');
            $table->unsignedBigInteger('productbrand_id');
            $table->foreign('productbrand_id')->references('id')->on('productbrands')->onDelete('cascade');
            $table->string('bijzonderheden', 200);
            $table->string('kleur', 50);
            $table->integer('lengte');
            $table->integer('breedte');
            $table->integer('hoogte');
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
        Schema::dropIfExists('products');
    }
};
