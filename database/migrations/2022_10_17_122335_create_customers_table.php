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
        Schema::create('customers', function (Blueprint $table) {
            // $table->id();
            // $table->tinyInteger('id')->nullable();
            $table->string('klantCode', 30)->unique();
            $table->string('naam', 50)->default('');
            $table->string('straat', 50)->default('');
            $table->string('huisnummer', 5)->default('');
            $table->string('postcode', 10)->default('');
            $table->string('plaats', 50)->default('');
            $table->string('landCode', 3)->default('');
            $table->string('contactpersoon', 50)->default('');
            $table->string('telefoon', 25)->default('');
            $table->string('eMailadres', 100)->default('');
            $table->string('website', 100)->default('');
            $table->timestamps();
            // $table->primary('klantCode');
        });

        DB::table('customers')->insert(
            array(
                'klantCode' => '1',
                'naam' => 'Customer A',
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
};
