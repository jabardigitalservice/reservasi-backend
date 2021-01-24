<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;

class ReservationStoreMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $reservation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.reservationStore')
                    ->subject('[Digiteam Reservasi Aset] Reservasi Aset Baru')
                    ->with([
                        'url' => config('app.web_url')  . '/reservasi'
                    ]);
    }
}
