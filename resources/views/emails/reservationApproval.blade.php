@component('mail::message')
# Persetujuan Reservasi Aset

Terima kasih Anda sudah melakukan reservasi pada Aplikasi Digiteam Reservasi Aset.
Melalui surat elektronik ini, berdasarkan data reservasi yang kami terima yaitu:
- Judul Kegiatan: {{ $reservation->title }}
- Catatan Kegiatan: {{ $reservation->description }}
- Tanggal dan Waktu Kegiatan: {{ $reservation->start_time }} sd. {{ $reservation->end_time }}
- Tanggal Dibuat: {{ $reservation->created_at }}
- Status Persetujuan: {{ $status }}
- Catatan Persetujuan: {{ $reservation->note }}

@component('mail::button', ['url' => $url])
Lihat Reservasi
@endcomponent

Terimakasih,<br>
Tim {{ config('app.name') }}
@endcomponent
