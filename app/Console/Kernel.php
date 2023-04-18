<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\ArchiveXml;
use App\Jobs\ParseVoorraadXml;
use App\Jobs\ParseArtikelXml;
use App\Jobs\ParseKlantXml;
use App\Jobs\ParseOrderXml;
use App\Jobs\SendOrder;
use App\Jobs\RemindReservations;
// use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Stringable;

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
        // Laravel Task Scheduling not providing output on Failure (https://stackoverflow.com/questions/65941010/laravel-task-scheduling-not-providing-output-on-failure)
        // Set a Try Catch block within the job handle() !!

        $schedule->job(new ArchiveXml)->dailyAt(1);
        // $schedule->job(new ArchiveXml)->everyMinute();
        // $schedule->job(new ArchiveXml)->everyMinute()->onFailure(function () {
        //     Mail::raw($error, function ($message) {
        //         $message
        //           ->to('leon@wtmedia-events.nl')
        //           ->subject('ArchiveXml job failed!');
        //       });
        // });
        // $schedule->job(new ArchiveXml)->everyMinute()->emailOutputOnFailure('leon@wtmedia-events.nl');
        $schedule->job(new ParseArtikelXml)->everyMinute(); // dailyAt('14:23') // hourly()
        $schedule->job(new ParseKlantXml)->everyMinute();
        $schedule->job(new ParseOrderXml)->everyMinute();
        $schedule->job(new ParseVoorraadXml)->everyTwoMinutes();

        $schedule->job(new SendOrder)->everyMinute(); // webportal -> WMS

        $schedule->job(new RemindReservations)->dailyAt('14:00');
        // $schedule->job(new RemindReservations)->everyMinute();
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
