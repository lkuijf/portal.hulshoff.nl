<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\ArchiveXml;
use App\Jobs\ParseVoorraadXml;
use App\Jobs\ParseArtikelXml;
use App\Jobs\ParseKlantXml;
use App\Jobs\ParseOrderXml;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new ArchiveXml)->everyMinute();
        $schedule->job(new ParseArtikelXml)->everyMinute();
        $schedule->job(new ParseKlantXml)->everyMinute();
        $schedule->job(new ParseOrderXml)->everyMinute();
        $schedule->job(new ParseVoorraadXml)->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
