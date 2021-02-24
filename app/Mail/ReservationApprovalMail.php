<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;
use App\Enums\ReservationStatusEnum;
use App\Events\AfterReservation;

class ReservationApprovalMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $reservation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AfterReservation $event)
    {
        $this->reservation = $event->reservation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.reservationApproval')
                    ->subject('[Digiteam Reservasi Aset] Persetujuan Reservasi Aset')
                    ->with([
                        'url' => config('app.web_url') . '/reservasi'
                    ]);
    }
}
