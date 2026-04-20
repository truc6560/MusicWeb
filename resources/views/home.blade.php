<x-client-layout>
    <style>
        /* CSS DÀNH RIÊNG CHO TRANG CHỦ (Đã tinh chỉnh từ bản gốc) */
        .section-header { margin: 40px 0 20px 0; }
        .section-title { 
            font-size: 20px; font-weight: 800; color: #fff; 
            text-transform: uppercase; border-left: 4px solid #bd00ff; 
            padding-left: 12px; margin-bottom: 20px;
        }
        
        /* TIN TỨC - AUTO SCROLL */
        .news-scroll-container { 
            overflow: hidden; /* Giấu thanh cuộn đi */
            white-space: nowrap; 
            padding-bottom: 15px; 
            position: relative;
            /* Tạo hiệu ứng mờ dần ở 2 mép cực xịn */
            mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%);
            -webkit-mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%);
        }
        
        .news-track {
            display: inline-flex;
            gap: 20px;
            /* Chỉnh số 30s để cho nó chạy nhanh hay chậm tùy ý Trúc */
            animation: scrollNews 30s linear infinite; 
            padding-right: 20px;
        }

        /* Khi người dùng rê chuột vào thì dừng lại cho người ta đọc */
        .news-track:hover {
            animation-play-state: paused; 
        }

        /* Hiệu ứng trôi từ phải qua trái */
        @keyframes scrollNews {
            0% { transform: translateX(0); }
            100% { transform: translateX(calc(-50% - 10px)); }
        }

        .news-card { 
            width: 270px; background: #161822; border-radius: 10px; 
            flex-shrink: 0; cursor: pointer; border: 1px solid rgba(255,255,255,0.03); 
            overflow: hidden; white-space: normal; transition: 0.3s;
        }
        .news-card:hover { transform: translateY(-5px); border-color: rgba(189, 0, 255, 0.4); box-shadow: 0 10px 20px rgba(189,0,255,0.15); }
        .news-img { width: 100%; height: 150px; object-fit: cover; }
        .news-content { padding: 15px; height: 100px; display: flex; flex-direction: column; justify-content: flex-end; }
        .news-big-title { font-size: 14px; font-weight: 700; color: #fff; margin-bottom: 5px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; } 

        .news-modal {
            position: fixed;
            inset: 0;
            z-index: 2200;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .news-modal.is-open {
            display: flex;
        }

        .news-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(3, 6, 14, 0.78);
            backdrop-filter: blur(10px);
        }

        .news-modal-dialog {
            position: relative;
            width: min(860px, 100%);
            max-height: min(88vh, 900px);
            overflow: hidden;
            border-radius: 22px;
            background: linear-gradient(180deg, #151927 0%, #0f121b 100%);
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 30px 70px rgba(0,0,0,0.5);
            display: grid;
            grid-template-columns: minmax(260px, 320px) minmax(0, 1fr);
        }

        .news-modal-cover {
            width: 100%;
            height: 100%;
            object-fit: cover;
            background: #0a0c13;
        }

        .news-modal-body {
            padding: 24px 24px 28px;
            overflow: auto;
        }

        .news-modal-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
            color: #8f95af;
            font-size: 13px;
        }

        .news-modal-title {
            color: #fff;
            font-size: 28px;
            font-weight: 900;
            line-height: 1.2;
            margin-bottom: 16px;
        }

        .news-modal-content {
            color: #d6dbeb;
            font-size: 15px;
            line-height: 1.85;
            white-space: pre-wrap;
        }

        .news-modal-close {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: rgba(255,255,255,0.08);
            color: #fff;
            cursor: pointer;
            z-index: 2;
        }

        .news-modal-close:hover {
            background: rgba(255,255,255,0.14);
        }

        @media (max-width: 820px) {
            .news-modal-dialog {
                grid-template-columns: 1fr;
            }

            .news-modal-cover {
                max-height: 240px;
            }

            .news-modal-title {
                font-size: 22px;
            }
        }
        /* BẢNG XẾP HẠNG */
        .charts-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .chart-box { background: #14161f; border-radius: 15px; padding: 20px; border: 1px solid rgba(255,255,255,0.03); }
        .chart-box-title { font-size: 16px; font-weight: 800; margin-bottom: 15px; display: block; color: #fff; }
        .chart-row { display: flex; align-items: center; gap: 10px; padding: 8px; border-radius: 8px; transition: 0.2s; cursor: pointer; }
        .chart-row:hover { background: rgba(255,255,255,0.08); transform: translateX(5px); }
        .rank-num { font-size: 18px; font-weight: 900; width: 25px; text-align: center; }
        .rank-1 { color: #ffdb00; } .rank-2 { color: #c0c0c0; } .rank-3 { color: #cd7f32; }
        .chart-img { width: 45px; height: 45px; border-radius: 6px; object-fit: cover; }
    
        .song-info-link {
            display: flex;
            align-itesms: center;
            gap: 10px;
            flex: 1;
            text-decoration: none;
            color: inherit;
        }
        .song-info-link:hover {
            text-decoration: none;
        }
        
        .charts-more-wrap {
            display: flex;
            justify-content: center;
            margin-top: 24px;
        }
        
        .charts-more-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 22px;
            border-radius: 999px;
            background: linear-gradient(90deg, #bd00ff, #00d1ff);
            color: #fff;
            text-decoration: none;
            font-weight: 800;
            letter-spacing: 0.3px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 10px 24px rgba(0, 209, 255, 0.18);
        }
        
        .charts-more-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(189, 0, 255, 0.22);
        }
    </style>

    <div class="section-header">
        <div class="section-title">Tin Tức Nổi Bật</div>
    </div>
    
    <div class="news-scroll-container">
        <div class="news-track">
            @if(isset($news_list) && count($news_list) > 0)
                
                @for ($i = 0; $i < 2; $i++)
                    @foreach($news_list as $news)
                    @php
                        $newsPayload = [
                            'title' => $news->title,
                            'image_url' => $news->image_url ?: asset('image/default-cover.jpg'),
                            'post_date' => \Carbon\Carbon::parse($news->post_date)->format('d/m/Y'),
                            'content' => $news->content,
                        ];
                    @endphp
                    <div class="news-card"
                         role="button"
                         tabindex="0"
                         data-news='@json($newsPayload)'>
                        <img src="{{ $news->image_url }}" class="news-img" onerror="this.src='https://via.placeholder.com/270x150'">
                        <div class="news-content">
                            <div class="news-big-title">{{ $news->title }}</div>
                            <div style="font-size: 12px; color: #888;">
                                {{ \Carbon\Carbon::parse($news->post_date)->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endfor

            @else
                <p style="color:#666; padding-left: 10px; font-style: italic;">Đang cập nhật tin tức...</p>
            @endif
        </div>
    </div>

    <div id="newsModal" class="news-modal" aria-hidden="true">
        <div class="news-modal-backdrop" data-news-modal-close="1"></div>
        <div class="news-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="newsModalTitle">
            <button type="button" class="news-modal-close" id="newsModalClose" aria-label="Đóng">&times;</button>
            <img id="newsModalCover" class="news-modal-cover" src="" alt="">
            <div class="news-modal-body">
                <div class="news-modal-meta">
                    <span id="newsModalDate">--</span>
                    <span>Tin tức nổi bật</span>
                </div>
                <div id="newsModalTitle" class="news-modal-title"></div>
                <div id="newsModalContent" class="news-modal-content"></div>
            </div>
        </div>
    </div>

    <div class="section-header">
        <div class="section-title">Bảng Xếp Hạng</div>
    </div>
    
    <div class="charts-grid">
        <div class="chart-box" style="border-top: 3px solid #bd00ff;">
            <span class="chart-box-title">🔥 TOP THỊNH HÀNH</span>
            <div class="chart-list">
                @foreach ($chart_trending as $idx => $song)
                <div class="chart-row song-item-row"
                     data-id="{{ $song->song_id }}"
                     data-title="{{ $song->title }}"
                     data-artist="{{ $song->artist->name ?? 'Unknown Artist' }}"
                     data-src="{{ route('song.stream', ['id' => $song->song_id]) }}"
                     data-cover="{{ $song->image_url ?: asset('image/default-cover.jpg') }}">
                    <div class="rank-num rank-{{ $idx + 1 }}">{{ $idx + 1 }}</div>
                    <a href="{{ route('song.details', ['id' => $song->song_id]) }}" class="song-info-link">
                        <img src="{{ $song->image_url }}" class="chart-img" onerror="this.src='https://via.placeholder.com/45'">
                        <div style="flex: 1; overflow: hidden;">
                            <div style="color: #fff; font-size: 14px; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $song->title }}</div>
                            <div style="color: #888; font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $song->artist->name ?? 'Unknown Artist' }}</div>
                        </div>
                    </a>
                    <button type="button" class="song-play-btn" style="width: 30px; height: 30px; border: none; border-radius: 50%; background: #00d1ff; color: #041018; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-play" style="font-size: 11px;"></i>
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        <div class="chart-box" style="border-top: 3px solid #00d1ff;">
            <span class="chart-box-title">🇻🇳 TOP VIỆT NAM</span>
            <div class="chart-list">
                @foreach ($chart_vn as $idx => $song)
                <div class="chart-row song-item-row"
                     data-id="{{ $song->song_id }}"
                     data-title="{{ $song->title }}"
                     data-artist="{{ $song->artist_name ?? 'Unknown Artist' }}"
                     data-src="{{ route('song.stream', ['id' => $song->song_id]) }}"
                     data-cover="{{ $song->image_url ?: asset('image/default-cover.jpg') }}">
                    <div class="rank-num rank-{{ $idx + 1 }}">{{ $idx + 1 }}</div>
                    <a href="{{ route('song.details', ['id' => $song->song_id]) }}" class="song-info-link">
                        <img src="{{ $song->image_url }}" class="chart-img" onerror="this.src='https://via.placeholder.com/45'">
                        <div style="flex: 1; overflow: hidden;">
                            <div style="color: #fff; font-size: 14px; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $song->title }}</div>
                            <div style="color: #888; font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $song->artist_name }}</div>
                        </div>
                    </a>
                    <button type="button" class="song-play-btn" style="width: 30px; height: 30px; border: none; border-radius: 50%; background: #00d1ff; color: #041018; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-play" style="font-size: 11px;"></i>
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        <div class="chart-box" style="border-top: 3px solid #ff0077;">
            <span class="chart-box-title">🌍 TOP QUỐC TẾ</span>
            <div class="chart-list">
                @foreach ($chart_usuk as $idx => $song)
                <div class="chart-row song-item-row"
                     data-id="{{ $song->song_id }}"
                     data-title="{{ $song->title }}"
                     data-artist="{{ $song->artist_name ?? 'Unknown Artist' }}"
                     data-src="{{ route('song.stream', ['id' => $song->song_id]) }}"
                     data-cover="{{ $song->image_url ?: asset('image/default-cover.jpg') }}">
                    <div class="rank-num rank-{{ $idx + 1 }}">{{ $idx + 1 }}</div>
                    <a href="{{ route('song.details', ['id' => $song->song_id]) }}" class="song-info-link">
                        <img src="{{ $song->image_url }}" class="chart-img" onerror="this.src='https://via.placeholder.com/45'">
                        <div style="flex: 1; overflow: hidden;">
                            <div style="color: #fff; font-size: 14px; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $song->title }}</div>
                            <div style="color: #888; font-size: 12px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $song->artist_name }}</div>
                        </div>
                    </a>
                    <button type="button" class="song-play-btn" style="width: 30px; height: 30px; border: none; border-radius: 50%; background: #00d1ff; color: #041018; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-play" style="font-size: 11px;"></i>
                    </button>
                </div>
                @endforeach
            </div>
        </div>
    
        <div class="charts-more-wrap">
            <a href="{{ route('charts') }}" class="charts-more-btn">
                <i class="fas fa-chart-line"></i>
                Xem thêm bảng xếp hạng
            </a>
        </div>
    </div>

    <script>
        (function () {
            const modal = document.getElementById('newsModal');
            const closeBtn = document.getElementById('newsModalClose');
            const cover = document.getElementById('newsModalCover');
            const title = document.getElementById('newsModalTitle');
            const content = document.getElementById('newsModalContent');
            const date = document.getElementById('newsModalDate');

            if (!modal || !closeBtn || !cover || !title || !content || !date) return;

            const openModal = (payload) => {
                cover.src = payload.image_url || 'https://via.placeholder.com/860x480';
                cover.alt = payload.title || 'News cover';
                title.textContent = payload.title || 'Tin tức';
                date.textContent = payload.post_date || '--';
                content.textContent = payload.content || 'Chưa có nội dung.';
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            };

            const closeModal = () => {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            };

            document.querySelectorAll('.news-card[data-news]').forEach((card) => {
                card.addEventListener('click', () => {
                    try {
                        const payload = JSON.parse(card.dataset.news || '{}');
                        openModal(payload);
                    } catch (error) {
                        console.error('Không thể mở tin tức:', error);
                    }
                });

                card.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        card.click();
                    }
                });
            });

            closeBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', (event) => {
                if (event.target && event.target.hasAttribute('data-news-modal-close')) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && modal.classList.contains('is-open')) {
                    closeModal();
                }
            });
        })();
    </script>
</x-client-layout>