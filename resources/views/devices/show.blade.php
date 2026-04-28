<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Alat</title>
</head>
<body>
    <h1>Detail Alat {{ $device['code'] }}</h1>

    <ul>
        <li>Nama: {{ $device['name'] }}</li>
        <li>Tipe: {{ $device['type'] }}</li>
        <li>Lokasi: {{ $device['location'] }}</li>
        <li>Status: {{ $device['status'] }}</li>
    </ul>

    <p><a href="{{ route('devices.index') }}">Kembali ke daftar alat</a></p>
</body>
</html>