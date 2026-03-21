<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Melodify</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        /* CSS Khung nền chuẩn của project cũ */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: sans-serif; }
        body { background-color: #12141d; color: #fff; display: flex; min-height: 100vh; }
        
        /* Thanh Sidebar bên trái */
        aside { width: 260px; background-color: #1a1c26; padding: 30px 20px; border-right: 1px solid #2d2f3b; }
        aside h2 { color: #00d1ff; text-align: center; margin-bottom: 40px; font-size: 24px; }
        
        .nav-item { 
            padding: 15px; margin-bottom: 10px; border-radius: 8px; 
            display: block; color: #a0a0a0; text-decoration: none; 
            transition: 0.3s; font-weight: 600;
        }
        .nav-item:hover, .nav-item.active { 
            color: #00d1ff; background: rgba(0, 209, 255, 0.1); 
        }
        .nav-item i { margin-right: 10px; width: 20px; text-align: center; }

        /* Vùng nội dung chính bên phải */
        main { flex: 1; padding: 30px; overflow-y: auto; }
        
        /* CSS dùng chung cho các thẻ Card và Table ở các trang con */
        .card { background-color: #1a1c26; border: 1px solid #2d2f3b; border-radius: 16px; padding: 30px; }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .card-header h2 { font-size: 1.8rem; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { text-align: left; color: #888; padding: 15px; border-bottom: 2px solid #2d2f3b; text-transform: uppercase; font-size: 0.9rem;}
        td { padding: 15px; border-bottom: 1px solid #2d2f3b; vertical-align: middle;}
        
        /* Nút bấm dùng chung */
        .btn-add { background: #00d1ff; color: #12141d; text-decoration: none; padding: 10px 20px; border-radius: 10px; font-weight: bold; }
        .btn-edit { background: transparent; color: #ffd000; border: 1px solid #ffd000; padding: 6px 12px; border-radius: 6px; cursor: pointer;}
        .btn-delete { background: transparent; color: #ff4466; border: 1px solid #ff4466; padding: 6px 12px; border-radius: 6px; cursor: pointer;}
    </style>
</head>
<body>

    <aside>
        <h2><i class="fas fa-compact-disc"></i> Melodify</h2>
        <nav>
            <a href="#" class="nav-item"><i class="fas fa-chart-pie"></i> Bảng điều khiển</a>
            
            <a href="{{ route('admin.artists.index') }}" class="nav-item active">
                <i class="fas fa-microphone-lines"></i> Quản lý Nghệ sĩ
            </a>
            
            <a href="#" class="nav-item"><i class="fas fa-music"></i> Quản lý Bài hát</a>
            <a href="#" class="nav-item"><i class="fas fa-music"></i> Quản lý Thể loại</a>
            <a href="#" class="nav-item"><i class="fas fa-users"></i> Quản lý Người dùng</a>
            <a href="#" class="nav-item"><i class="fas fa-music"></i> Thống kê</a>
        </nav>
    </aside>

    <main>
        @yield('content')
    </main>

</body>
</html>