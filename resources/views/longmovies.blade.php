<!DOCTYPE html>
<html>
<head>
    <title>Danh sách phim trên 120 phút</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Top 10 bộ phim có thời lượng hơn 120 phút</h2>
    <table>
        <thead>
            <tr>
                <th>Tên bộ phim</th>
                <th>Ngày phát hành</th>
                <th>Thời lượng (phút)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movies as $movie)
            <tr>
                <td>{{ $movie->movie_name }}</td>
                <td>{{ $movie->release_date }}</td>
                <td>{{ $movie->runtime }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>