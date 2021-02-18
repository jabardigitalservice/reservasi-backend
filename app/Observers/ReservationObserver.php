<?php

namespace App\Observers;

use App\Enums\ReservationStatusEnum;
use App\Mail\ReservationApprovalMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationStoreMail;
use Illuminate\Support\Arr;
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
     * @param  \App\Models\Reservation  $reservation
     * @return void
     */
    public function updated(Reservation $reservation)
    {
        Mail::to($reservation->email)->send(new ReservationApprovalMail($reservation));
        $reservations = Reservation::where('asset_id', $reservation->asset_id)
                            ->where('id', '!=', $reservation->id)
                            ->validateTime($reservation)
                            ->get();
        if ($reservation->has_already_approved && $reservations) {
            $cc = $reservations->unique('email')->pluck('email');
            $id = $reservations->pluck('id');
            Reservation::whereIn('id', $id)->update(['approval_status' => ReservationStatusEnum::rejected()]);
            //send rejection emails to users other than those already approved
            Mail::to(Arr::first($cc))->cc($cc)->send(new ReservationApprovalMail($reservation));
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
