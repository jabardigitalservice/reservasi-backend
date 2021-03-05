<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Events\AfterReservation;

class ReservationApprovalMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $reservation;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AfterReservation $event, $user)
    {
        $this->reservation = $event->reservation;
        $this->user = $user;
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
                        'hostkey' => $this->user->host_key,
                        'url' => config('app.web_url') . '/reservasi'
                    ]);
    }
}
