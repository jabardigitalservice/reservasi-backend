<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use \MacsiDigital\Zoom\Facades\Zoom;

class ZoomController extends Controller
{
    public function createZoom(Request $request)
    {
        /**
         *
         * Menampilkan Seluruh User di Akun Zoom
         */
        // $user = Zoom::user()->all();
        // return [$user];

        /**
         *
         * Menampilkan Seluruh User di Akun Zoom yang Aktif, pending, atau tidak aktif
         */
        // $user = Zoom::user()->where('status', 'active')->get(); // Allowed values active, inactive and pending
        // return [$user];

        /**
         *
         * Membuat User Baru
         */
        // $user = Zoom::user()->create([
        //     'first_name' => 'Digiteam',
        //     'last_name' => 'Reservasi Aset',
        //     'email' => 'digiteamreservasi@gmail.com',
        //     'password' => 'DigiteamReservasi123,./'
        // ]);
        // return [$user];

        /**
         *
         * Menampilkan seluruh Daftar Meeting yang dimiliki User
         */
        // $meetings = Zoom::user()->find(config('zoom.email'))->meetings;
        // return [$meetings];

        /**
         *
         * Membuat Meeting Baru
         */
        // $meetings = Zoom::user()->find(config('zoom.email'))->meetings()->create([
        //     'topic' => 'Rapat Mini Project',
        //     'duration' => 35,
        //     'type' => '2',
        //     'start_time' => new Carbon('2021-03-01 10:00:00'),
        //     'timezone' => 'Asia/Jakarta',
        // ]);
        // return [$meetings];
        $timeInSecond = $request->end_time->diffInSeconds($request->start_time);
        $meetings = Zoom::user()->find($request->email)->meetings()->create([
            'topic' => $request->topic,
            'duration' => gmdate('H:i:s', $timeInSecond),
            'type' => '2',
            'start_time' => $request->start_time,
            'timezone' => 'Asia/Jakarta',
        ]);
        return [$meetings];

        /**
         *
         * Cek Config Zoom
         */
        // return [
        //     'email' => config('zoom.email'),
        //     'api_key' => config('zoom.api_key'),
        //     'api_secret' => config('zoom.api_secret'),
        // ];
    }
}
