<!DOCTYPE html>
<html>
<head>
    <title>Ülkelere Göre Rapor</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; text-align: left; }
    </style>
</head>
<body>
    <h2>Ülkelere Göre Tıklama Raporu</h2>
    <table>
        <thead>
            <tr>
                <th>Ülke</th>
                <th>Tıklama Sayısı</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['labels'] as $index => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ $data['data'][$index] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>