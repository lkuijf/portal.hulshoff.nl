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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->nullable()->default(null);
            $table->timestamp('start_time')->nullable()->default(null);
            $table->timestamp('end_time')->nullable()->default(null);
            $table->unsignedBigInteger('total_files')->nullable()->default(null);
            $table->unsignedBigInteger('processed')->nullable()->default(null);
            $table->unsignedBigInteger('skipped')->nullable()->default(null);
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
        Schema::dropIfExists('jobs');
    }
};
