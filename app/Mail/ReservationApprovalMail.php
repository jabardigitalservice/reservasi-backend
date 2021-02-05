<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;
use App\Enums\ReservationStatusEnum;

class ReservationApprovalMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $reservation;
    public $status;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;

        $this->status = 'Ditolak';
        if ($reservation->status == ReservationStatusEnum::already_approved()) {
            $this->status = 'Disetujui';
        }
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
                        'status' => $this->status,
                        'url' => config('app.web_url') . '/reservasi'
                    ]);
    }
}
