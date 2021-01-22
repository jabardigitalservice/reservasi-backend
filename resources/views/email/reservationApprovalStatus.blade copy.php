<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>E-Mail Digiteam Reservasi Aset</title>
</head>
<body>
    <div>
        <div>Kepada Yth,</div>
        <div>{{ $reservation->user_fullname }}</div>
    </div>
    <div>
        @foreach ($infos as $info)
        <p>{{ $info }}</p>
        @endforeach
    </div>
    <table>
        <tr>
            <td>Judul Kegiatan</td>
            <td>:</td>
            <td>{{ $reservation->title }}</td>
        </tr>
        <tr>
            <td>Catatan Kegiatan</td>
            <td>:</td>
            <td>{{ $reservation->description }}</td>
        </tr>
        <tr>
            <td>Tanggal dan Waktu Kegiatan</td>
            <td>:</td>
            <td>{{ $reservation->date . ' ' . $reservation->start_time . ' sd. ' . $reservation->end_time }}</td>
        </tr>
        <tr>
            <td>Tanggal Dibuat</td>
            <td>:</td>
            <td>{{ $reservation->created_at }} UTC</td>
        </tr>
        <tr>
            <td>{{ $noteApprovalLabel }}</td>
            <td>:</td>
            <td>{{ $reservation->note }}</td>
        </tr>
    </table>
    <div>
        <p>{{ $approvalInfo }}</p>
    </div>
    <div>
        <div>Silahkan klik tombol / link di bawah ini untuk melihat detail reservasi Anda</div>
        @component('mail::button', ['url' => $link, 'color' => 'blue'])
        Detail Reservasi
        @endcomponent
    </div>
</body>
</html>