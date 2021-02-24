<?php

namespace App\Listeners;

use App\Events\ReservationEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\ReservationApprovalMail;
use App\Models\Reservation;
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
    public function handle(Reservation $reservation)
    {
        Mail::to(auth()->user()->email)->send(new ReservationApprovalMail($reservation));
    }
}
