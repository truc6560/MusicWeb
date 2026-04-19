<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 500px; margin: 0 auto; background: #1a1c26; border-radius: 16px; padding: 30px; color: #fff;">
        <h2 style="text-align: center; color: #8A2BE2;">🎵 MusicWeb</h2>
        <h3 style="text-align: center;">Đặt lại mật khẩu</h3>
        
        <p>Xin chào <strong>{{ $name }}</strong>,</p>
        <p>Nhấp vào nút bên dưới để đặt lại mật khẩu của bạn:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $resetLink }}" style="background: #8A2BE2; color: #fff; padding: 12px 25px; border-radius: 50px; text-decoration: none;">
                Đặt lại mật khẩu
            </a>
        </div>
        
        <p>Hoặc copy link: <a href="{{ $resetLink }}">{{ $resetLink }}</a></p>
        <p>Link có hiệu lực trong 60 phút.</p>
        
        <p>Cảm ơn bạn đã sử dụng MusicWeb!</p>
    </div>
</body>
</html>