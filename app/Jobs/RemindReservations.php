<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\HulshoffUser;
use App\Mail\ReservationReminder;

class RemindReservations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            $reservations = Order::where('is_reservation', 1)->get();
// dd($reservations);
            if(count($reservations)) {
// echo "\n";
                foreach($reservations as $reservation) {
// echo $reservation->created_at . "\n";
                    $reservationAgeSeconds = date('U') - strtotime($reservation->created_at);
                    $reservationAgeDays = (int)($reservationAgeSeconds / 3600 / 24);
// echo $reservationAgeDays . "\n";
// echo $reservationAgeDays % 7 . "\n";
                    if($reservationAgeDays % 7 == 0) { // exactly 1, 2, 3 etc... weeks old.

                        $hhUser = HulshoffUser::find($reservation->hulshoff_user_id);

                        Mail::to($hhUser->email)->send(new ReservationReminder($reservation));
                        $extraEmails = json_decode($hhUser->extra_email);
                        if($extraEmails && count($extraEmails)) {
                            foreach($extraEmails as $e_email) {
                                Mail::to($e_email)->send(new ReservationReminder($reservation));
                            }
                        }

                        

                    }
                }
            }

        } catch (\Exception $e) {
            Mail::raw($e->getMessage(), function ($message) {
                $message
                ->to('leon@wtmedia-events.nl')
                ->subject('ReservationReminder job failed!');
            });
            throw $e; //rethrow
        }
    }
}
