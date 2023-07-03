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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('klantCode', 30)->nullable();
            $table->foreign('klantCode')->references('klantCode')->on('customers')->nullOnDelete();

            $table->string('naam', 50)->default('');
            $table->string('straat', 50)->default('');
            $table->string('huisnummer', 5)->default('');
            $table->string('postcode', 10)->default('');
            $table->string('plaats', 50)->default('');
            $table->string('landCode', 3)->default('');
            $table->string('contactpersoon', 50)->default('');
            $table->string('telefoon', 25)->default('');
            $table->string('eMailadres', 100)->default('');

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
        Schema::dropIfExists('addresses');
    }
};
