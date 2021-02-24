<?php

namespace App\Listeners;

use App\Events\ReservationEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\ReservationApprovalMail;
use Illuminate\Support\Facades\Mail;

class SendReservationEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ReservationEmail  $event
     * @return void
     */
    public function handle(ReservationEmail $reservationEvent)
    {
        try {
            Mail::to($reservationEvent->reservation->email)->send(new ReservationApprovalMail($reservationEvent));
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()]);
        }
    }
}
