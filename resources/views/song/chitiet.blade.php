<x-client-layout>
    @php
        $isLikedArtist = Auth::check() && Auth::user()->likedArtists()->where('artists.artist_id', $song->artist->artist_id)->exists();
    @endphp

    <style>
        /* Reset riêng cho trang detail */
        .spotify-detail {
            min-height: 100%;
            padding: 2rem 2rem 6rem 2rem;
            border-radius: 0;
            position: relative;
            overflow: hidden;
        }

        .spotify-detail::before {
            content: '';
            position: absolute;
            inset: -28px;
            background-image: var(--song-cover-bg);
            background-size: cover;
            background-position: center;
            filter: blur(24px) saturate(1.15);
            transform: scale(1.08);
            opacity: 0.52;
            z-index: 0;
        }

        .spotify-detail::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(13, 15, 23, 0.76), rgba(13, 15, 23, 0.44));
            z-index: 1;
        }

        .spotify-detail-content {
            position: relative;
            z-index: 2;
        }

        /* Header giống Spotify */
        .detail-header {
            display: flex;
            gap: 2rem;
            align-items: flex-end;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }

        .cover-wrapper {
            flex-shrink: 0;
            box-shadow: 0 20px 35px rgba(0,0,0,0.5);
            border-radius: 8px;
            overflow: hidden;
            width: 220px;
            height: 220px;
        }

        .detail-cover {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .detail-info {
            flex: 1;
            min-width: 200px;
        }

        .detail-type {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #b3b3b3;
            margin-bottom: 0.5rem;
        }

        .detail-title {
            font-size: 4rem;
            font-weight: 900;
            margin: 0 0 0.5rem 0;
            letter-spacing: -0.02em;
            line-height: 1.2;
            color: white;
        }

        .detail-artist {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #b3b3b3;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .dot {
            font-size: 0.5rem;
            vertical-align: middle;
        }

        .detail-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .spotify-play-btn {
            background: #d0ecff;
            border: none;
            border-radius: 500px;
            padding: 12px 28px;
            min-height: 44px;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 1px;
            color: #000;
            cursor: pointer;
            transition: transform 0.2s ease, background 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .spotify-play-btn:hover {
            background: #9ad3f8;
            transform: scale(1.02);
        }

        .spotify-like-btn {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 500px;
            padding: 12px 22px;
            min-height: 44px;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 1px;
            color: #e6e6e6;
            cursor: pointer;
            transition: transform 0.2s ease, background 0.2s, border-color 0.2s, color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .spotify-like-btn:hover {
            color: #fff;
            border-color: rgba(255, 255, 255, 0.45);
            background: rgba(255, 255, 255, 0.14);
            transform: scale(1.02);
        }

        .spotify-like-btn.liked {
            color: #ff007a;
            border-color: rgba(255, 0, 122, 0.7);
            background: rgba(255, 0, 122, 0.16);
        }

        /* Phần lời bài hát */
        .detail-lyrics-section {
            max-width: 800px;
            margin-top: 2rem;
        }

        .lyrics-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1.5rem;
            border-left: 4px solid #d0ecff;
            padding-left: 12px;
        }

        .lyrics-box {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            border-radius: 12px;
            padding: 1.5rem;
            color: #ddd;
            line-height: 1.8;
            font-size: 0.95rem;
            white-space: pre-wrap;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-height: 60vh;
            overflow-y: auto;
        }

        /* Responsive */
        @media (max-width: 700px) {
            .detail-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .detail-title {
                font-size: 2.5rem;
            }
            .detail-actions {
                justify-content: center;
            }
            .cover-wrapper {
                width: 160px;
                height: 160px;
            }
        }

        .spotify-add-to-queue {
            background: rgba(255,255,255,0.1);
            border: none;
            border-radius: 500px;
            padding: 12px 22px;
            min-height: 44px;
            font-weight: 700;
            font-size: 14px;
            color: #fff;
            cursor: pointer;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .spotify-add-to-queue:hover {
            background: rgba(255,255,255,0.2);
            transform: scale(1.02);
        }
    </style>

    @php
        $coverUrl = asset($song->image_url ?? 'image/icon2.png');
        $coverBg = "url('" . $coverUrl . "')";
    @endphp

    <div class="spotify-detail" style="--song-cover-bg: {{ $coverBg }};">
        <div class="spotify-detail-content">
            <div class="detail-header">
                <div class="cover-wrapper">
                    <img src="{{ $coverUrl }}" alt="{{ $song->title }}" class="detail-cover">
                </div>
                <div class="detail-info">
                    <div class="detail-type">BÀI HÁT</div>
                    <h1 class="detail-title">{{ $song->title }}</h1>
                    <div class="detail-artist">
                        <span>{{ $song->artist->name ?? 'Unknown Artist' }}</span>
                        <span class="dot">•</span>
                        <span>{{ number_format($song->plays ?? 0) }} lượt nghe</span>
                    </div>
                    <div class="detail-actions">
                        <button class="spotify-play-btn" data-id="{{ $song->song_id }}">
                            <i class="fas fa-play"></i> PHÁT NHẠC
                        </button>
                        <button class="spotify-like-btn btn-like-song" data-id="{{ $song->song_id }}">
                            <i class="far fa-heart"></i>
                        </button>
                        <button class="spotify-like-btn btn-like-artist {{ $isLikedArtist ? 'liked' : '' }}" data-id="{{ $song->artist->artist_id }}">
                            <i class="fas fa-user-plus"></i> NGHỆ SĨ
                        </button>
                        <button class="spotify-add-to-queue" data-id="{{ $song->song_id }}">
                            <i class="fas fa-list"></i> THÊM VÀO DANH SÁCH CHỜ
                        </button>
                    </div>
                </div>
            </div>

            <div class="detail-lyrics-section">
                <h3 class="lyrics-title">Lời bài hát</h3>
                <div class="lyrics-box">{!! nl2br(e($song->lyrics ?? 'Chưa có lời bài hát')) !!}</div>
            </div>
        </div>
    </div>

</x-client-layout>