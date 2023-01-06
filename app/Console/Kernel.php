<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\ArchiveXml;
use App\Jobs\ParseVoorraadXml;
use App\Jobs\ParseArtikelXml;
use App\Jobs\ParseKlantXml;
use App\Jobs\ParseOrderXml;
use Illuminate\Support\Facades\Mail;

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
        // $schedule->job(new ArchiveXml)->dailyAt(1);
        $schedule->job(new ArchiveXml)->everyMinute()->onFailure(function (Stringable $error) {
            Mail::raw($error, function ($message) {
                $message
                  ->to('leon@wtmedia-events.nl')
                  ->subject('ArchiveXml job failed!');
              });
        });
        // $schedule->job(new ArchiveXml)->everyMinute()->emailOutputOnFailure('leon@wtmedia-events.nl');
        $schedule->job(new ParseArtikelXml)->hourly(); // dailyAt('14:23')
        $schedule->job(new ParseKlantXml)->hourly();
        $schedule->job(new ParseOrderXml)->hourly();
        $schedule->job(new ParseVoorraadXml)->hourly();
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
