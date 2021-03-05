<?php

namespace App\Listeners;

use App\Events\AfterReservation;
use App\Mail\ReservationApprovalMail;
use Illuminate\Support\Facades\Mail;
use MacsiDigital\Zoom\Facades\Zoom;

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
    public function handle(AfterReservation $event)
    {
        try {
            $user = Zoom::user()->find($event->asset->zoom_email);
            Mail::to($event->reservation->email)->send(new ReservationApprovalMail($event, $user));
        } catch (\Exception $e) {
            return response()->json(["message" => $e]);
        }
    }
}
