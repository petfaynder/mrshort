<!DOCTYPE html>
<html>
<head>
    <title>İşletim Sistemlerine Göre Rapor</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; text-align: left; }
    </style>
</head>
<body>
    <h2>İşletim Sistemlerine Göre Tıklama Raporu</h2>
    <table>
        <thead>
            <tr>
                <th>İşletim Sistemi</th>
                <th>Tıklama Sayısı</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $osStats)
                <tr>
                    <td>{{ $osStats->os ?? 'Bilinmiyor' }}</td>
                    <td>{{ $osStats->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>