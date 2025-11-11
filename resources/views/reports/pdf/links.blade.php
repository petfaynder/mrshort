<!DOCTYPE html>
<html>
<head>
    <title>Linklere Göre Rapor</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; text-align: left; }
    </style>
</head>
<body>
    <h2>Linklere Göre Tıklama Raporu</h2>
    <table>
        <thead>
            <tr>
                <th>Orijinal Link</th>
                <th>Kısaltılmış Link</th>
                <th>Tekil Tıklama</th>
                <th>Toplam Tıklama</th>
                <th>Kazanç ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $linkStats)
                <tr>
                    <td>{{ $linkStats['original_url'] }}</td>
                    <td>{{ $linkStats['short_link'] }}</td>
                    <td>{{ $linkStats['unique_clicks'] }}</td>
                    <td>{{ $linkStats['total_clicks'] }}</td>
                    <td>${{ $linkStats['earnings'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>