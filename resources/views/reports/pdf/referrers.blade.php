<!DOCTYPE html>
<html>
<head>
    <title>Yönlendirenlere Göre Rapor</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; text-align: left; }
    </style>
</head>
<body>
    <h2>Yönlendiren Domainlere Göre Tıklama Raporu</h2>
    <table>
        <thead>
            <tr>
                <th>Yönlendiren Domain</th>
                <th>Tıklama Sayısı</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $referrerStats)
                <tr>
                    <td>{{ $referrerStats->referrer ?? 'Doğrudan / Bilinmiyor' }}</td>
                    <td>{{ $referrerStats->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>