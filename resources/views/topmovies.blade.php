<html>
<head>
    <title>Top 10 Phim</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>DANH SÁCH 10 BỘ PHIM ĐIỂM BÌNH CHỌN CAO NHẤT</h2>
    <table>
        <tr>
            <th>Tên bộ phim</th>
            <th>Ngày phát hành</th>
            <th>Điểm bình chọn</th>
        </tr>
        @foreach($movies as $row)
            <tr>
                <td>{{ $row->movie_name }}</td>
                <td>{{ $row->release_date }}</td>
                <td>{{ $row->vote_average }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>