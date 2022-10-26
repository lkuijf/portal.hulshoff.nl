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
        Schema::create('hulshoff_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('klantCode', 30)->nullable();
            $table->foreign('klantCode')->references('klantCode')->on('customers')->nullOnDelete();
            $table->json('extra_email');
            $table->boolean('can_reserve');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('hulshoff_users')->insert(
            array(
                'name' => 'TEST klant',
                'email' => 'abc@def.nl',
                'password' => Hash::make('test123'),
                'klantCode' => 'Customer A',
                'extra_email' => '[{"email":"Belg@rade"},{"email":"Pa@ris"},{"email":"Madr@id"}]',
                'can_reserve' => 1,
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
        Schema::dropIfExists('hulshoff_users');
    }
};
