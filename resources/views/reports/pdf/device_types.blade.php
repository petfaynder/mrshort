<!DOCTYPE html>
<html>
<head>
    <title>Cihaz Türlerine Göre Rapor</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; text-align: left; }
    </style>
</head>
<body>
    <h2>Cihaz Türlerine Göre Tıklama Raporu</h2>
    <table>
        <thead>
            <tr>
                <th>Cihaz Türü</th>
                <th>Tıklama Sayısı</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $deviceStats)
                <tr>
                    <td>{{ $deviceStats->device_type ?? 'Bilinmiyor' }}</td>
                    <td>{{ $deviceStats->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>