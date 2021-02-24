<?php

namespace App\Listeners;

use App\Events\AfterReservation;
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
     * @param AfterReservation  $event
     * @return void
     */
    public function handle(AfterReservation  $event)
    {
        try {
            Mail::to($event->reservation->email)->send(new ReservationApprovalMail($event));
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()]);
        }
    }
}
