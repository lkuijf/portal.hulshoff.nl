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
        Schema::create('hulshoff_user_klantcodes', function (Blueprint $table) {
            $table->unsignedBigInteger('hulshoff_user_id');
            $table->foreign('hulshoff_user_id')->references('id')->on('hulshoff_users')->cascadeOnDelete();
            $table->string('klantCode', 30);
            $table->foreign('klantCode')->references('klantCode')->on('customers')->cascadeOnDelete();
            $table->timestamps();
            $table->index(['hulshoff_user_id', 'klantCode'], 'hukhuik');
            $table->primary(['hulshoff_user_id', 'klantCode']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hulshoff_user_klantcodes');
    }
};
