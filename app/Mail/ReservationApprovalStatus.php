<?php

namespace App\Mail;

use App\Models\Reservation;
use App\Enums\ReservationStatusEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationApprovalStatus extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $reservation;
    protected $status;
    public $subject;
    public $infos;
    public $approvalInfo;
     
    public function __construct(Reservation $reservation, $status)
    {
        $this->reservation = $reservation;
        $this->status = $status;
        $this->subject = '[Digiteam Reservasi Aset] Persetujuan Reservasi Aset';
        $this->infos[] = 'Terima kasih Anda sudah melakukan reservasi pada Aplikasi Digiteam Reservasi Aset.';
        $this->infos[] = 'Melalui surat elektronik ini, berdasarkan data reservasi yang kami terima yaitu:';
        $this->approvalInfo = 'Melalui surat elektronik ini, kami bermaksud untuk menyampaikan bahwa reservasi Anda sudah diterima.';
        $this->noteApprovalLabel = 'Catatan Persetujuan';
        if (ReservationStatusEnum::rejected()) {
            $this->approvalInfo = 'Melalui surat elektronik ini, kami bermaksud untuk menyampaikan bahwa reservasi Anda ditolak.';
            $this->noteApprovalLabel = 'Catatan Penolakan';
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.reservationApprovalStatus')
            ->subject($this->subject)
            ->with([
                'reservation' => $this->reservation,
                'infos' => $this->infos,
                'noteApprovalLabel' => $this->noteApprovalLabel,
                'approvalInfo' => $this->approvalInfo,
                'link' => env('APP_WEB_URL') . '/reservasi',
                // 'from' => env('MAIL_FROM_NAME'),
                // 'hotLine' => env('HOTLINE_PIKOBAR')
            ]);
    }
}
