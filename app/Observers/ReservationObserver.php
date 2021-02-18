<?php

namespace App\Observers;

use App\Enums\ReservationStatusEnum;
use App\Mail\ReservationApprovalMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationStoreMail;
use App\Models\Reservation;

class ReservationObserver
{
    /**
     * Handle the reservation "created" event.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return void
     */
    public function created(Reservation $reservation)
    {
        Mail::to(config('mail.admin_address'))->send(new ReservationStoreMail($reservation));
    }

    /**
     * Handle the reservation "updated" event.
     *
     * @param  \App\Reservation  $reservation
     * @return void
     */
    public function updated(Reservation $reservation)
    {
        Mail::to($reservation->email)->send(new ReservationApprovalMail($reservation));
        if ($reservation->has_already_approved) {
            //get reservation data that intersects with the agreed time
            $reservations = Reservation::where('asset_id', $reservation->asset_id)
                        ->where('id', '!=', $reservation->id)
                        ->validateTime($reservation)
                        ->get();
            //send rejection emails to users other than those already approved
            foreach ($reservations as $reservation) {
                $reservation->update(['approval_status' => ReservationStatusEnum::rejected()]);
                Mail::to($reservation->email)->send(new ReservationApprovalMail($reservation));
            }
        }

    }

    /**
     * Handle the reservation "deleted" event.
     *
     * @param  \App\Reservation  $reservation
     * @return void
     */
    public function deleted(Reservation $reservation)
    {
        //
    }

    /**
     * Handle the reservation "restored" event.
     *
     * @param  \App\Reservation  $reservation
     * @return void
     */
    public function restored(Reservation $reservation)
    {
        //
    }

    /**
     * Handle the reservation "force deleted" event.
     *
     * @param  \App\Reservation  $reservation
     * @return void
     */
    public function forceDeleted(Reservation $reservation)
    {
        //
    }
}
