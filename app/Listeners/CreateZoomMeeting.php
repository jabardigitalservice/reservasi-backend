<?php

namespace App\Listeners;

use App\Events\AfterReservation;
use App\Enums\ResourceTypeEnum;
use \MacsiDigital\Zoom\Facades\Zoom;

class CreateZoomMeeting
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
     * @param  AfterReservation  $event
     * @return void
     */
    public function handle(AfterReservation $event)
    {
        $reservation = $event->reservation;
        $asset = $event->asset;
        if ($asset->resource_type == ResourceTypeEnum::online()) {
            // Membuat Meeting Baru
            $timeInMinute = $reservation->end_time->diffInMinutes($reservation->start_time);
            $meetings = Zoom::user()->find(config('zoom.email'))->meetings()->create([
                'topic' => $reservation->title,
                'duration' => $timeInMinute,
                'type' => '2',
                'start_time' => $reservation->start_time,
                'timezone' => 'Asia/Jakarta',
            ]);

            // Update join_url from this reservation
            $reservation->join_url = $meetings->join_url;
            $reservation->save();
        }

        return $reservation;
    }
}
