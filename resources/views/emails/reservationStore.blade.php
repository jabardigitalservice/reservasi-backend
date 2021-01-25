@component('mail::message')
# Reservasi Aset Baru

Melalui surat elektronik ini, kami memberitahukan bahwa ada data reservasi yang kami terima yaitu:
- Judul Kegiatan: {{ $reservation->title }}
- Catatan Kegiatan: {{ $reservation->description }}
- Tanggal dan Waktu Kegiatan: {{ $reservation->start_time }} sd. {{ $reservation->end_time }}
- Tanggal Dibuat: {{ $reservation->created_at }}

@component('mail::button', ['url' => $url])
Lihat Reservasi
@endcomponent

Terimakasih,<br>
Tim {{ config('app.name') }}
@endcomponent
