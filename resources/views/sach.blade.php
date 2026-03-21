<html>
<head>
</head>
<body>
    <table>
    <thead>
        <tr>
            <th>Thể loại tiếng anh</th>
            <th>Thể loại tiếng việt</th>
        </tr>
    </thead>
    <tbody>
        @foreach($TL as $key => $row)
        <tr>
            <td>{{ $row->genre_name }}</td>
            <td>{{ $row->genre_name_vn }}</td>
      
        </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>