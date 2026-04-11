<x-client-layout>
<style>
    /* --- HEADER ALBUM --- */
    .album-header {
        display: flex;
        align-items: flex-end;
        gap: 30px;
        margin-bottom: 40px;
    }

    .album-cover {
        width: 230px;
        height: 230px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .album-info h5 {
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 10px;
        color: #00d1ff; /* Đồng bộ màu cyan của Soundwave */
    }

    .album-info h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 15px;
        line-height: 1;
        color: #fff;
    }

    .album-info p {
        color: #ccc;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .album-desc {
        color: #888;
        margin-top: 15px;
        font-size: 13px;
        max-width: 600px;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* --- PLAY BUTTON ON HEADER --- */
    /* Giữ nguyên logic nút bấm của bạn nhưng sử dụng class chung của hệ thống */
    .btn-play-all {
        background-color: #bd00ff;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 30px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 20px;
        transition: transform 0.2s;
    }

    .btn-play-all:hover {
        transform: scale(1.05);
        background-color: #a300dc;
    }

    /* --- SONG TABLE (Đồng bộ với danhsachyeuthich) --- */
    .song-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .song-table th {
        text-align: left;
        color: #888;
        padding: 12px 20px;
        border-bottom: 1px solid #2d2f3b;
        font-size: 14px;
    }

    .song-table td {
        padding: 10px 20px; 
        border-bottom: 1px solid #1f212d;
        color: #ddd;
        vertical-align: middle;
    }

    .song-table tr {
        cursor: pointer;
        transition: 0.2s;
    }

    .song-table tr:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }

    /* --- SONG INFO INSIDE TABLE (NEW STYLES) --- */
    .song-info-flex {
        display: flex;
        align-items: center;
        gap: 15px; 
    }

    .song-thumb-small {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 4px;
    }

    .song-title-row {
        font-size: 15px;
        font-weight: 500;
        color: #fff;
    }

    .song-artist-row {
        font-size: 13px;
        color: #a0a0a0;
        margin-top: 3px;
    }

    /* --- LIKE BUTTON --- */
    .btn-like {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.1rem;
        color: #666;
        transition: 0.2s;
    }

    .btn-like:hover {
        color: #fff;
        transform: scale(1.1);
    }

    .btn-like.liked {
        color: #ff0077;
    }
</style>

<div class="album-header">
    <img src="{{ $album->image_url }}" class="album-cover" alt="Album Cover">
    <div class="album-info">
        <h5>Album</h5>
        <h1>{{ $album->title }}</h1>
        <p style="color: var(--text-sub);">
            <i class="fas fa-user-circle"></i> {{ $album->artist->name }} • {{ $album->release_year }}
        </p>
        
        @if($album->description)
            <div class="album-desc">{{ $album->description }}</div>
        @endif

        <button class="btn-play-all" id="btnPlayRandom">
            <i class="fas fa-play" style="margin-right: 8px;"></i> Phát ngẫu nhiên
        </button>
    </div>
</div>

<table class="song-table">
    <thead>
        <tr>
            <th style="width: 50px;">#</th>
            <th>Bài hát</th>
            <th style="text-align: right;">Thời lượng</th>
            <th style="width: 80px; text-align: center;">Thích</th>
        </tr>
    </thead>
    <tbody id="album-tracklist">
        @foreach($songs as $index => $song)
            <x-song-row 
                :song="$song" 
                :index="$index + 1" 
                :albumArtist="$album->artist->name" 
                :albumImage="$album->image_url" 
            />
        @endforeach
    </tbody>
</table>

<script>
    // 1. Hàm Toggle Like
    function toggleLike(e, songId) {
        e.stopPropagation();
        const btn = e.currentTarget; 
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (!@json(Auth::check())) {
            alert('Vui lòng đăng nhập để thêm bài hát vào danh sách yêu thích.');
            btn.classList.remove('liked');
            return;
        }

        fetch("{{ route('albums.toggleLike') }}", { // Sử dụng route Laravel thay cho file .php lẻ
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                song_id: songId
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                btn.classList.toggle('liked', data.action === 'liked');
            } else if (data.message) {
                alert(data.message);
            } else {
                alert('Không thể cập nhật bài hát yêu thích.');
            }
        })
        .catch(err => {
            alert('Vui lòng đăng nhập để thêm bài hát vào danh sách yêu thích.');
            btn.classList.remove('liked');
            console.error("Lỗi tương tác Like:", err);
        });
    }

    // 2. XỬ LÝ NÚT PHÁT NGẪU NHIÊN 
    const playAllBtn = document.getElementById('btnPlayRandom'); // Đồng bộ ID với HTML
    
    if (playAllBtn) {
        playAllBtn.onclick = () => {
            const rows = document.querySelectorAll('#album-tracklist .song-item-row');
            const albumPlaylist = [];

            if (rows.length === 0) {
                alert("Album này chưa có bài hát nào!");
                return;
            }

            // Duyệt qua từng dòng và lấy dữ liệu
            rows.forEach(row => {
                albumPlaylist.push({
                    id: row.dataset.id,
                    title: row.dataset.title,
                    artist: row.dataset.artist,
                    src: row.dataset.src,
                    cover: row.dataset.cover
                });
            });

            // Cập nhật vào trình phát nhạc
            if (typeof songList !== 'undefined') {
                // Gán danh sách nhạc CHỈ CỦA ALBUM vào biến toàn cục
                songList = albumPlaylist; 
                
                // Bật chế độ Shuffle
                isShuffle = true;
                const shuffleBtnUI = document.getElementById("shuffleBtn");
                if (shuffleBtnUI) shuffleBtnUI.classList.add("active");

                //Chọn ngẫu nhiên 1 bài để phát
                currentIndex = Math.floor(Math.random() * songList.length);
                
                //Gọi hàm phát nhạc
                if (typeof loadSong === 'function') {
                    loadSong(songList[currentIndex]);
                    // Tự động phát nhạc nếu hàm playSong có sẵn
                    if (typeof playSong === 'function') playSong();
                }
                
                //Cập nhật lại highlight bài đang hát
                if (typeof updateRowHighlight === 'function') {
                    updateRowHighlight(songList[currentIndex].id);
                }
            } else {
                console.error("Chưa tải được trình phát nhạc (player.js)");
            }
        };
    }
</script>
</x-client-layout>