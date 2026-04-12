<x-client-layout>
@php
    $genreKey = strtolower(trim((string) $genre->name));
    $genreThemeMap = [
        'pop' => ['#ff5f6d', '#ffc371'],
        'ballad' => ['#7f7fd5', '#86a8e7'],
        'rock' => ['#f12711', '#f5af19'],
        'hip hop' => ['#11998e', '#38ef7d'],
        'r&b' => ['#b24592', '#f15f79'],
        'edm' => ['#00c6ff', '#0072ff'],
        'acoustic' => ['#c79081', '#dfa579'],
        'indie' => ['#4568dc', '#b06ab3'],
        'jazz' => ['#614385', '#516395'],
        'classical' => ['#8e9eab', '#eef2f3'],
        'k pop' => ['#ee0979', '#ff6a00'],
        'v pop' => ['#00b09b', '#96c93d'],
    ];

    $theme = $genreThemeMap[$genreKey] ?? ['#2facff', '#b04aff'];
    $accentA = $theme[0];
    $accentB = $theme[1];
@endphp

<style>
    .genre-page {
        --accent-a: {{ $accentA }};
        --accent-b: {{ $accentB }};
        --accent-soft: color-mix(in srgb, var(--accent-a) 18%, transparent);
        padding: 20px;
    }

    .genre-hero {
        background:
            radial-gradient(circle at top right, color-mix(in srgb, var(--accent-b) 35%, transparent) 0%, transparent 52%),
            linear-gradient(135deg, color-mix(in srgb, var(--accent-a) 28%, #0f1422) 0%, #0d0f17 100%);
        border: 1px solid color-mix(in srgb, var(--accent-a) 35%, #ffffff 8%);
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 24px;
        box-shadow: 0 14px 30px color-mix(in srgb, var(--accent-a) 25%, transparent);
    }

    .genre-title {
        font-size: 34px;
        font-weight: 900;
        color: #fff;
        margin-bottom: 8px;
    }

    .genre-subtitle {
        color: #9aa3b8;
        font-size: 14px;
    }

    .song-table {
        width: 100%;
        border-collapse: collapse;
        background: #14161f;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid color-mix(in srgb, var(--accent-a) 22%, transparent);
    }

    .song-table th {
        text-align: left;
        color: #7f889f;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        border-bottom: 1px solid #262a38;
        padding: 14px 16px;
    }

    .song-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #1e2230;
        color: #d8def0;
    }

    .song-item-row {
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .song-item-row:hover {
        background: color-mix(in srgb, var(--accent-a) 16%, transparent);
    }

    .song-meta {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .song-cover {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        object-fit: cover;
    }

    .song-title {
        font-weight: 700;
        color: #fff;
    }

    .genre-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #fff;
        background: linear-gradient(90deg, var(--accent-a), var(--accent-b));
        padding: 6px 12px;
        border-radius: 999px;
        margin-bottom: 12px;
    }

    .song-artist {
        color: #9aa3b8;
        font-size: 13px;
        margin-top: 2px;
    }

    .empty-box {
        background: #14161f;
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 14px;
        color: #8f95af;
        padding: 30px;
        text-align: center;
    }
</style>

<div class="genre-page">
    <div class="genre-hero">
        <div class="genre-chip">
            <i class="fas fa-music"></i>
            Thể loại
        </div>
        <div class="genre-title">{{ $genre->name }}</div>
        <div class="genre-subtitle">Danh sách bài hát theo thể loại {{ $genre->name }}. Chạm vào một bài để phát ngay.</div>
    </div>

    @if($songs->isEmpty())
        <div class="empty-box">Hiện chưa có bài hát nào cho thể loại này.</div>
    @else
        <table class="song-table">
            <thead>
                <tr>
                    <th style="width: 72px;">#</th>
                    <th>Bài hát</th>
                    <th style="text-align:right; width: 140px;">Lượt nghe</th>
                </tr>
            </thead>
            <tbody>
                @foreach($songs as $index => $song)
                    @php
                        $cover = $song->image_url ?: asset('image/default_cover.png');
                    @endphp
                    <tr class="song-item-row"
                        data-id="{{ $song->song_id }}"
                        data-title="{{ $song->title }}"
                        data-artist="{{ $song->artist->name ?? 'Unknown Artist' }}"
                        data-src="{{ route('song.stream', ['id' => $song->song_id]) }}"
                        data-cover="{{ $cover }}">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="song-meta">
                                <img src="{{ $cover }}" class="song-cover" alt="{{ $song->title }}">
                                <div>
                                    <div class="song-title">{{ $song->title }}</div>
                                    <div class="song-artist">{{ $song->artist->name ?? 'Unknown Artist' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="text-align:right;">{{ number_format($song->plays ?? 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
</x-client-layout>
