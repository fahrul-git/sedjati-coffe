<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Greenhouse</title>
</head>
<body>
    <h1>Dashboard Greenhouse</h1>

    <p>Total alat: {{ $summary['total_devices'] }}</p>
    <p>Alat aktif: {{ $summary['active_devices'] }}</p>
    <p>Total tanaman: {{ $summary['total_plants'] }}</p>
    <p>Rata-rata suhu: {{ $summary['avg_temperature'] }} C</p>
    <p>Rata-rata kelembapan: {{ $summary['avg_humidity'] }} %</p>

    <h2>Peringatan</h2>
    <ul>
        @foreach ($alerts as $alert)
            <li>{{ $alert }}</li>
        @endforeach
    </ul>

    <p><a href="{{ route('devices.index') }}">Lihat daftar alat</a></p>
</body>
</html>