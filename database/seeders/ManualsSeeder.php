<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Manual;

class ManualsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($x=0;$x<10;$x++) {
            $manual = new Manual;
            $manual->url = Str::random(10) . '/' . Str::random(10);
            $manual->text = Str::random(299);
            $manual->save();
        }
    }
}
