<!DOCTYPE html>
<html lang="en">
<head>
	<title>Document</title>
	<style>
		table {
                border-collapse: collapse;
                width: auto;
                margin: 20px auto;
                
            }
            th, td {
                border: 1px solid #000;
                padding: 8px 12px;
                
            }
            th {
                background-color: #f2f2f2;
            }
	</style>
</head>
<body>
	<h2 style="text-align: center;">Các bộ phim của Canada</h2>
	<table>
		<tr>
			<th>Tên</th>
			<th>Ngày phát hành</th>
            <th>Thời lượng</th>
            
		</tr>
		@foreach($phim as $row)
			<tr>
				
				<td>{{ $row->movie_name}}</td>
				<td>{{ $row->release_date}}</td>
				<td>{{ $row->runtime}}</td>
				
			</tr>
		@endforeach
	</table>

</body>
</html>
			