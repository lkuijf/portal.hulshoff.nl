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
            $table->string('password')->nullable();
            $table->string('klantCode', 30)->nullable();
            $table->foreign('klantCode')->references('klantCode')->on('customers')->nullOnDelete();
            $table->string('last_known_klantCode_name')->nullable();
            $table->json('extra_email')->nullable();
            $table->json('privileges')->nullable();
            $table->boolean('can_reserve')->default(0);
            $table->boolean('is_admin')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('hulshoff_users')->insert(
            array(
                'name' => 'TEST klant',
                'email' => 'customer_a@hulshoffportal.nl',
                'email_verified_at' => '2022-01-01 00:00:00',
                'password' => Hash::make('v482kS0Y'),
                'klantCode' => '1234',
                'last_known_klantCode_name' => '1234',
                'extra_email' => '[{"email":"Belg@rade"},{"email":"Pa@ris"},{"email":"Madr@id"}]',
                // 'privileges' => '["show_tiles","free_search","lotcode_search"]',
                'can_reserve' => 1,
                'is_admin' => 0,
            )
        );
        DB::table('hulshoff_users')->insert(
            array(
                'name' => 'TEST admin',
                'email' => 'admin@hulshoffportal.nl',
                'email_verified_at' => '2022-01-01 00:00:00',
                'password' => Hash::make('6weY9e5H'),
                'is_admin' => 1,
            )
        );
        DB::table('hulshoff_users')->insert(
            array(
                'name' => 'Leon',
                'email' => 'leon@wtmedia-events.nl',
                'email_verified_at' => '2022-01-01 00:00:00',
                'password' => Hash::make('kuijf'),
                'can_reserve' => 1,
                'is_admin' => 1,
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
