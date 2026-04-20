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

        .detail-title-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 0.5rem;
        }

        .lrc-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 999px;
            padding: 4px 9px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.4px;
            text-transform: uppercase;
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
            color: rgba(255, 255, 255, 0.78);
            line-height: 2;
            font-size: 1rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-height: 60vh;
            overflow-y: auto;
        }

        .detail-lyrics-box {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .detail-lyric-line {
            display: block;
            color: rgba(255, 255, 255, 0.52);
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.9;
            transition: transform 0.18s ease, color 0.18s ease, text-shadow 0.18s ease, background 0.18s ease;
            transform-origin: left center;
            will-change: transform;
        }

        .detail-lyric-line[data-lyric-time] {
            cursor: pointer;
            border-radius: 6px;
            padding: 1px 4px;
            margin-left: -4px;
            margin-right: -4px;
        }

        .detail-lyric-line[data-lyric-time]:hover {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.9);
        }

        .detail-lyric-line.active {
            color: #ffffff;
            font-size: 1rem;
            font-weight: 600;
            transform: scale(1.03);
            text-shadow: 0 0 8px rgba(255, 255, 255, 0.16);
        }

        .detail-lyrics-empty {
            color: rgba(255, 255, 255, 0.68);
            font-size: 1rem;
            font-weight: 600;
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
        $detailLyricsText = trim((string) ($song->lyrics ?? ''));
        $detailLrcUrl = !empty($song->lrc_file) ? asset('lyrics/' . $song->lrc_file) : '';

        $detailLyricsPayload = [
            'songId' => $song->song_id,
            'title' => $song->title,
            'artist' => $song->artist->name ?? 'Unknown Artist',
            'fallbackLyrics' => $detailLyricsText,
            'lrcUrl' => $detailLrcUrl,
        ];
    @endphp

    <div class="spotify-detail" style="--song-cover-bg: {{ $coverBg }};">
        <div class="spotify-detail-content">
            <div class="detail-header">
                <div class="cover-wrapper">
                    <img src="{{ $coverUrl }}" alt="{{ $song->title }}" class="detail-cover">
                </div>
                <div class="detail-info">
                    <div class="detail-type">BÀI HÁT</div>
                    <div class="detail-title-wrap">
                        <h1 class="detail-title" style="margin: 0;">{{ $song->title }}</h1>
                        @if(($hasLrc ?? false) || !empty($detailLrcUrl))
                            <span class="lrc-badge">L</span>
                        @endif
                    </div>
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
                <div class="lyrics-box detail-lyrics-box" id="detailLyricsBox">
                    @if(!empty($detailLyricsText))
                        {!! nl2br(e($detailLyricsText)) !!}
                    @else
                        <span class="detail-lyrics-empty">Đang tải lời bài hát...</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script type="application/json" id="detailLyricsPayload">{!! json_encode($detailLyricsPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>

    <script>
        (function() {
            function initDetailLyrics() {
                const payloadNode = document.getElementById('detailLyricsPayload');
                const lyricsBox = document.getElementById('detailLyricsBox');
                const audio = document.getElementById('audioPlayer');
                if (!payloadNode || !lyricsBox || !audio) return;

                let payload = {};
                try {
                    payload = JSON.parse(payloadNode.textContent || '{}') || {};
                } catch (error) {
                    payload = {};
                }

                const songId = String(payload.songId || '');
                const title = String(payload.title || '').trim();
                const artist = String(payload.artist || '').trim();
                const fallbackLyrics = String(payload.fallbackLyrics || '').trim();
                const lrcUrl = String(payload.lrcUrl || '');

                let parsedLyrics = [];
                let activeLyricIndex = -1;
                let lyricsRequestToken = 0;

                function escapeHtml(str) {
                    return String(str || '').replace(/[&<>"']/g, (char) => ({
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#39;'
                    }[char]));
                }

                function parseLrcContent(lrcText) {
                    if (!lrcText || typeof lrcText !== 'string') return [];

                    const lines = lrcText.split(/\r?\n/);
                    const parsed = [];

                    for (const rawLine of lines) {
                        const line = rawLine.trim();
                        if (!line) continue;

                        const matches = [...line.matchAll(/\[(\d{1,2}):(\d{2})(?:\.(\d{1,3}))?\]/g)];
                        if (!matches.length) continue;

                        const text = line.replace(/\[(\d{1,2}):(\d{2})(?:\.(\d{1,3}))?\]/g, '').trim() || '...';
                        matches.forEach((match) => {
                            const minute = Number(match[1]) || 0;
                            const second = Number(match[2]) || 0;
                            const fractionRaw = match[3] || '0';
                            const fraction = fractionRaw.length === 3 ? Number(fractionRaw) / 1000 : Number(fractionRaw) / 100;
                            parsed.push({ time: (minute * 60) + second + fraction, text });
                        });
                    }

                    return parsed.sort((a, b) => a.time - b.time);
                }

                function buildLyricFileCandidates(songTitle, songArtist) {
                    const normalize = (value) => {
                        return String(value || '')
                            .replace(/[\\/:*?"<>|]/g, ' ')
                            .replace(/\s+/g, ' ')
                            .trim();
                    };

                    const base = [
                        songTitle,
                        `${songArtist || ''} - ${songTitle || ''}`,
                        `${songTitle || ''} - ${songArtist || ''}`
                    ]
                        .map((item) => normalize(item))
                        .filter(Boolean);

                    return [...new Set(base)];
                }

                async function fetchLrcByCandidates(candidates) {
                    for (const fileStem of candidates) {
                        const url = `/lyrics/${encodeURIComponent(fileStem)}.lrc`;

                        try {
                            const response = await fetch(url, { cache: 'no-store' });
                            if (!response.ok) continue;

                            const content = await response.text();
                            if (content && content.trim()) {
                                return content;
                            }
                        } catch (error) {
                            continue;
                        }
                    }

                    return null;
                }

                function renderTimedLyrics(lines) {
                    parsedLyrics = Array.isArray(lines) ? lines : [];
                    activeLyricIndex = -1;

                    if (!parsedLyrics.length) {
                        lyricsBox.innerHTML = '<span class="detail-lyrics-empty">Chưa có lời bài hát</span>';
                        return;
                    }

                    lyricsBox.innerHTML = parsedLyrics.map((line, index) => (
                        `<span class="detail-lyric-line" data-lyric-index="${index}" data-lyric-time="${Number(line.time || 0)}" title="Nhấn để tua đến câu này">${escapeHtml(line.text)}</span>`
                    )).join('');

                    updateActiveLyricLine(audio ? audio.currentTime : 0);
                }

                function renderPlainLyrics(text) {
                    parsedLyrics = [];
                    activeLyricIndex = -1;

                    const normalized = String(text || 'Chưa có lời bài hát').trim();
                    const html = normalized
                        .split(/\r?\n/)
                        .filter((line) => line.trim() !== '')
                        .map((line) => `<span class="detail-lyric-line">${escapeHtml(line)}</span>`)
                        .join('');

                    lyricsBox.innerHTML = html || '<span class="detail-lyrics-empty">Chưa có lời bài hát</span>';
                }

                function updateActiveLyricLine(currentTime) {
                    if (!parsedLyrics.length) return;

                    let nextIndex = -1;
                    for (let i = 0; i < parsedLyrics.length; i += 1) {
                        if (currentTime >= parsedLyrics[i].time) {
                            nextIndex = i;
                        } else {
                            break;
                        }
                    }

                    if (nextIndex === activeLyricIndex) return;

                    const previousActive = lyricsBox.querySelector('.detail-lyric-line.active');
                    if (previousActive) {
                        previousActive.classList.remove('active');
                    }

                    activeLyricIndex = nextIndex;
                    if (activeLyricIndex < 0) return;

                    const nextActive = lyricsBox.querySelector(`.detail-lyric-line[data-lyric-index="${activeLyricIndex}"]`);
                    if (!nextActive) return;

                    nextActive.classList.add('active');
                }

                async function loadLyrics() {
                    const currentToken = ++lyricsRequestToken;

                    if (lrcUrl) {
                        try {
                            const response = await fetch(lrcUrl, { cache: 'no-store' });
                            if (response.ok) {
                                const lrcText = await response.text();
                                if (currentToken !== lyricsRequestToken) return;

                                const timedLyrics = parseLrcContent(lrcText);
                                if (timedLyrics.length) {
                                    renderTimedLyrics(timedLyrics);
                                    return;
                                }
                            }
                        } catch (error) {
                            // fall through to legacy text below
                        }
                    }

                    const fallbackCandidates = buildLyricFileCandidates(title, artist);
                    const candidateLrcText = await fetchLrcByCandidates(fallbackCandidates);
                    if (currentToken !== lyricsRequestToken) return;

                    if (candidateLrcText) {
                        const timedLyrics = parseLrcContent(candidateLrcText);
                        if (timedLyrics.length) {
                            renderTimedLyrics(timedLyrics);
                            return;
                        }
                    }

                    renderPlainLyrics(fallbackLyrics);
                }

                function syncWithPlayer() {
                    if (!audio || !songId) return;

                    const currentSong = typeof window.getCurrentSongData === 'function' ? window.getCurrentSongData() : null;
                    if (!currentSong || String(currentSong.id) !== String(songId)) return;

                    updateActiveLyricLine(audio.currentTime || 0);
                }

                function seekToLyricTime(seconds) {
                    const target = Number(seconds);
                    if (!Number.isFinite(target) || target < 0) return;

                    const currentSong = typeof window.getCurrentSongData === 'function' ? window.getCurrentSongData() : null;
                    if (currentSong && String(currentSong.id) === String(songId)) {
                        audio.currentTime = target;
                        return;
                    }

                    if (typeof window.playSong === 'function') {
                        const onceSeek = () => {
                            audio.currentTime = target;
                            audio.removeEventListener('loadedmetadata', onceSeek);
                        };
                        audio.addEventListener('loadedmetadata', onceSeek);
                        window.playSong(songId);
                    }
                }

                loadLyrics();

                lyricsBox.addEventListener('click', (event) => {
                    const line = event.target.closest('.detail-lyric-line[data-lyric-time]');
                    if (!line) return;

                    seekToLyricTime(line.dataset.lyricTime);
                });

                audio.addEventListener('timeupdate', syncWithPlayer);
                audio.addEventListener('seeked', syncWithPlayer);
                audio.addEventListener('loadedmetadata', syncWithPlayer);
                window.addEventListener('storage', syncWithPlayer);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initDetailLyrics);
            } else {
                initDetailLyrics();
            }
        })();
    </script>

</x-client-layout>