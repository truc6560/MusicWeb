<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MusicWeb')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: linear-gradient(135deg, #0a0a2a 0%, #1a1a3e 100%);
            /* QUAN TRỌNG: Cho phép cuộn */
            overflow-y: auto;
            overflow-x: hidden;
            padding: 40px 20px;
        }
        
        /* Sao băng */
        .shooting-star {
            position: fixed;
            top: 0;
            left: 0;
            width: 2px;
            height: 2px;
            background: linear-gradient(45deg, #fff, rgba(255,255,255,0));
            border-radius: 50%;
            animation: shoot 3s linear infinite;
            z-index: 0;
        }
        
        @keyframes shoot {
            0% {
                transform: translateX(0) translateY(0);
                opacity: 1;
            }
            100% {
                transform: translateX(-300px) translateY(300px);
                opacity: 0;
            }
        }
        
        /* Container chính - có thể cuộn */
        .auth-container { 
            width: 100%; 
            max-width: 500px; 
            margin: 0 auto; 
            position: relative; 
            z-index: 10;
        }
        
        .logo { text-align: center; margin-bottom: 30px; }
        .logo h1 { 
            color: #8A2BE2;
            font-size: 36px; 
            font-weight: bold; 
            text-shadow: 0 0 20px rgba(138, 43, 226, 0.5);
        }
        .logo p { color: #aaa; font-size: 14px; }
        
        .auth-card {
            background: rgba(18, 18, 18, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(138, 43, 226, 0.3);
        }
        
        .auth-card h2 { color: #fff; text-align: center; margin-bottom: 25px; font-size: 24px; }
        
        .input-group { margin-bottom: 18px; }
        .input-group label { display: block; color: #aaa; margin-bottom: 6px; font-size: 13px; font-weight: 500; }
        .input-group input {
            width: 100%; padding: 12px 14px; background: rgba(42, 42, 42, 0.8);
            border: 1px solid #3a3a3a; border-radius: 10px;
            color: #fff; font-size: 14px;
            transition: 0.3s;
        }
        .input-group input:focus { outline: none; border-color: #8A2BE2; background: #333; }
        
        .btn-auth {
            width: 100%; padding: 14px; background: #8A2BE2;
            border: none; border-radius: 50px; color: #fff;
            font-weight: bold; font-size: 16px; cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }
        .btn-auth:hover { background: #9b4de0; transform: scale(1.02); }
        
        .divider { display: flex; align-items: center; margin: 20px 0; color: #666; }
        .divider-line { flex: 1; height: 1px; background: #3a3a3a; }
        .divider-text { padding: 0 12px; font-size: 13px; }
        
        .btn-outline {
            width: 100%; padding: 12px; background: transparent;
            border: 1px solid #3a3a3a; border-radius: 50px;
            color: #fff; cursor: pointer; margin-bottom: 10px;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            transition: 0.3s;
            font-size: 14px;
        }
        .btn-outline:hover { border-color: #8A2BE2; background: rgba(138, 43, 226, 0.15); }
        
        .auth-link { text-align: center; margin-top: 20px; color: #aaa; font-size: 14px; }
        .auth-link a { color: #8A2BE2; text-decoration: none; }
        .auth-link a:hover { text-decoration: underline; color: #9b4de0; }
        
        .error-message { color: #ff4466; font-size: 12px; margin-top: 5px; }
        
        /* Ngôi sao nhấp nháy cố định */
        .star {
            position: fixed;
            background-color: white;
            border-radius: 50%;
            z-index: 0;
        }
    </style>
</head>
<body>
    <div id="stars"></div>

        <div class="auth-card">
            @yield('content')
        </div>
    <script>
        // Tạo sao băng
        function createShootingStar() {
            const star = document.createElement('div');
            star.classList.add('shooting-star');
            star.style.left = Math.random() * 100 + '%';
            star.style.top = Math.random() * 50 + '%';
            star.style.animationDuration = Math.random() * 2 + 1 + 's';
            star.style.animationDelay = Math.random() * 5 + 's';
            document.body.appendChild(star);
            
            setTimeout(() => {
                star.remove();
            }, 3000);
        }
        
        setInterval(createShootingStar, 800);
        
        // Tạo ngôi sao nhấp nháy cố định
        for (let i = 0; i < 150; i++) {
            const star = document.createElement('div');
            star.classList.add('star');
            star.style.width = Math.random() * 3 + 1 + 'px';
            star.style.height = star.style.width;
            star.style.left = Math.random() * 100 + '%';
            star.style.top = Math.random() * 100 + '%';
            star.style.opacity = Math.random() * 0.8 + 0.2;
            star.style.animation = 'twinkle ' + (Math.random() * 3 + 1) + 's infinite';
            document.body.appendChild(star);
        }
        
        const style = document.createElement('style');
        style.textContent = `
            @keyframes twinkle {
                0%, 100% { opacity: 0.2; }
                50% { opacity: 1; }
            }
        `;
        document.head.appendChild(style);
            // ========== THÊM ĐOẠN NÀY ==========
    // Hàm cập nhật avatar trên header (gọi từ trang profile)
    window.updateHeaderAvatar = function(imageUrl) {
        var headerAvatar = document.getElementById('headerAvatar');
        if (headerAvatar) {
            if (headerAvatar.tagName === 'IMG') {
                headerAvatar.src = imageUrl;
            } else {
                // Nếu là div (placeholder), thay bằng img
                var newImg = document.createElement('img');
                newImg.src = imageUrl;
                newImg.id = 'headerAvatar';
                newImg.style.width = '35px';
                newImg.style.height = '35px';
                newImg.style.borderRadius = '50%';
                newImg.style.objectFit = 'cover';
                headerAvatar.parentNode.replaceChild(newImg, headerAvatar);
            }
        }
    };
    
    // Lắng nghe sự kiện cập nhật avatar từ trang profile
    window.addEventListener('storage', function(e) {
        if (e.key === 'avatar_updated') {
            var newAvatar = localStorage.getItem('avatar_url');
            if (newAvatar) {
                window.updateHeaderAvatar(newAvatar);
            }
        }
    });
        </script>
</body>
</html>