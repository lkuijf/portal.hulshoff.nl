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
            $table->integer('stuksPerBundel');
            $table->float('prijs', 8, 2);
            $table->integer('minimaleVoorraad');
            $table->integer('voorraad')->default(0);
            $table->integer('aantal_besteld_onverwerkt')->default(0);
            $table->string('bijzonderheden', 200);
            $table->integer('lengte');
            $table->integer('breedte');
            $table->integer('hoogte');
            $table->unsignedBigInteger('productgroup_id');
            $table->foreign('productgroup_id')->references('id')->on('productgroups')->onDelete('cascade');
            $table->unsignedBigInteger('producttype_id');
            $table->foreign('producttype_id')->references('id')->on('producttypes')->onDelete('cascade');
            $table->unsignedBigInteger('productbrand_id');
            $table->foreign('productbrand_id')->references('id')->on('productbrands')->onDelete('cascade');
            $table->unsignedBigInteger('productcolor_id');
            $table->foreign('productcolor_id')->references('id')->on('productcolors')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['klantCode', 'artikelCode']);
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
