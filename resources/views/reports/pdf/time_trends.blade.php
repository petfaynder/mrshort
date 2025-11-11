<!DOCTYPE html>
<html>
<head>
    <title>Zamana Göre Trendler Raporu</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; text-align: left; }
    </style>
</head>
<body>
    <h2>Zamana Göre Tıklama Trendleri Raporu</h2>
    <table>
        <thead>
            <tr>
                <th>Tarih</th>
                <th>Tıklama Sayısı</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $trendData)
                <tr>
                    <td>{{ $trendData->click_date }}</td>
                    <td>{{ $trendData->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>