<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Alat</title>
</head>
<body>
    <h1>Daftar Alat Greenhouse</h1>

    <p><a href="{{ route('dashboard') }}">Kembali ke dashboard</a></p>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Tipe</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($devices as $device)
                <tr>
                    <td>{{ $device['code'] }}</td>
                    <td>{{ $device['name'] }}</td>
                    <td>{{ $device['type'] }}</td>
                    <td>{{ $device['location'] }}</td>
                    <td>{{ $device['status'] }}</td>
                    <td>
                        <a href="{{ route('devices.show', $device['code']) }}">Detail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>