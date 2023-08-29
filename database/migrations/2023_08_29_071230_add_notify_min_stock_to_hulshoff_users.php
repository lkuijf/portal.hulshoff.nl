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
        Schema::table('hulshoff_users', function (Blueprint $table) {
            $table->boolean('notify_min_stock')->default(0)->after('is_admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hulshoff_users', function (Blueprint $table) {
            $table->dropColumn('notify_min_stock');
        });
    }
};
