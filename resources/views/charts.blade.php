<x-client-layout>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    .chart-container {
        padding: 20px;
        background-color: #0d0f17;
        min-height: 100vh;
    }

    .chart-hero {
        min-height: 220px;
        display: flex;
        align-items: center;
        padding: 30px 50px;
        background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);
        border-radius: 20px;
        margin-bottom: 22px;
        box-shadow: 0 10px 30px rgba(245, 87, 108, 0.3);
    }

    .hero-content h1 {
        font-size: 4.5rem;
        font-weight: 900;
        letter-spacing: -3px;
        color: #fff;
        margin: 0;
        line-height: 1;
        text-shadow: 0 10px 20px rgba(0,0,0,0.3);
    }

    .hero-content p {
        color: rgba(255,255,255,0.8);
        margin-top: 10px;
        font-weight: 500;
        max-width: 760px;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .chart-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 18px;
    }

    .chart-tab {
        border: 1px solid rgba(255,255,255,0.08);
        background: #14161f;
        color: #c9ceda;
        padding: 12px 18px;
        border-radius: 999px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .chart-tab:hover {
        background: rgba(255,255,255,0.06);
        color: #fff;
    }

    .chart-tab.active {
        background: linear-gradient(90deg, #bd00ff, #00d1ff);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 12px 24px rgba(0, 209, 255, 0.16);
    }

    .ranking-table-wrapper {
        background: #14161f;
        border-radius: 16px;
        padding: 10px;
    }

    .chart-pane {
        display: none;
    }

    .chart-pane.active {
        display: block;
    }

    .ranking-table {
        width: 100%;
        border-collapse: collapse;
    }

    .ranking-table th {
        text-align: left;
        color: #555;
        padding: 15px;
        font-size: 12px;
        border-bottom: 1px solid #2d2f3b;
    }

    .song-item-row {
        cursor: pointer;
        transition: 0.2s;
        border-bottom: 1px solid rgba(255,255,255,0.02);
    }

    .song-item-row:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .rank-col {
        width: 80px;
        text-align: center;
        font-size: 24px;
        font-weight: 900;
        font-style: italic;
        color: rgba(255,255,255,0.1);
    }

    .rank-1 { color: #ffdb00 !important; text-shadow: 0 0 15px rgba(255, 219, 0, 0.5); font-size: 32px; }
    .rank-2 { color: #c0c0c0 !important; font-size: 28px; }
    .rank-3 { color: #cd7f32 !important; font-size: 26px; }

    .song-info-flex { display: flex; align-items: center; gap: 15px; padding: 10px 0; }
    .song-cover-mini { width: 50px; height: 50px; border-radius: 6px; object-fit: cover; }
    .song-name-text { font-weight: 600; color: #fff; display: block; font-size: 15px; }
    .artist-name-text { font-size: 13px; color: #888; }
    .plays-text { color: #00d1ff; font-weight: 700; font-family: 'Courier New', monospace; font-size: 16px; }

    .chart-empty {
        text-align: center;
        padding: 50px;
        color: #666;
    }
</style>

<div class="chart-container">
    <div class="chart-hero">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-crown"></i> Hot Trending
            </div>
            <h1>Bảng Xếp Hạng</h1>
            <p>Chọn Top bạn muốn, nghe điều cả thế giới đang nghe.</p>
        </div>
    </div>

    <div class="chart-tabs" role="tablist" aria-label="Bảng xếp hạng">
        <button class="chart-tab active" type="button" data-target="pane-trending">Top 10 bài hát có lượt nghe cao nhất trên Soundwave tuần này</button>
        <button class="chart-tab" type="button" data-target="pane-vn">Top Việt Nam</button>
        <button class="chart-tab" type="button" data-target="pane-usuk">Top quốc tế</button>
    </div>

    <div class="ranking-table-wrapper">
        <div class="chart-pane active" id="pane-trending">
            <table class="ranking-table">
                <thead>
                    <tr>
                        <th style="text-align: center;">HẠNG</th>
                        <th>BÀI HÁT</th>
                        <th style="text-align: right; padding-right: 20px;">LƯỢT NGHE</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($chart_trending as $index => $song)
                        @php
                            $rank = $index + 1;
                            $rankClass = ($rank <= 3) ? "rank-$rank" : "";
                            $img = $song->image_url ?: asset('image/default_cover.png');
                            $artistName = $song->artist->name ?? 'Unknown Artist';
                        @endphp
                        <tr class="song-item-row"
                            data-id="{{ $song->song_id }}"
                            data-title="{{ $song->title }}"
                            data-artist="{{ $artistName }}"
                            data-src="{{ route('song.stream', ['id' => $song->song_id]) }}"
                            data-cover="{{ $img }}">
                            <td class="rank-col {{ $rankClass }}">{{ $rank }}</td>
                            <td>
                                <div class="song-info-flex">
                                    <img src="{{ $img }}" class="song-cover-mini" alt="{{ $song->title }}">
                                    <div>
                                        <span class="song-name-text">{{ $song->title }}</span>
                                        <span class="artist-name-text">{{ $artistName }}</span>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: right; padding-right: 20px;">
                                <span class="plays-text">{{ number_format($song->plays, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="chart-empty">Chưa có dữ liệu xếp hạng.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="chart-pane" id="pane-vn">
            <table class="ranking-table">
                <thead>
                    <tr>
                        <th style="text-align: center;">HẠNG</th>
                        <th>BÀI HÁT</th>
                        <th style="text-align: right; padding-right: 20px;">LƯỢT NGHE</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($chart_vn as $index => $song)
                        @php
                            $rank = $index + 1;
                            $rankClass = ($rank <= 3) ? "rank-$rank" : "";
                            $img = $song->image_url ?: asset('image/default_cover.png');
                            $artistName = $song->artist->name ?? 'Unknown Artist';
                        @endphp
                        <tr class="song-item-row"
                            data-id="{{ $song->song_id }}"
                            data-title="{{ $song->title }}"
                            data-artist="{{ $artistName }}"
                            data-src="{{ route('song.stream', ['id' => $song->song_id]) }}"
                            data-cover="{{ $img }}">
                            <td class="rank-col {{ $rankClass }}">{{ $rank }}</td>
                            <td>
                                <div class="song-info-flex">
                                    <img src="{{ $img }}" class="song-cover-mini" alt="{{ $song->title }}">
                                    <div>
                                        <span class="song-name-text">{{ $song->title }}</span>
                                        <span class="artist-name-text">{{ $artistName }}</span>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: right; padding-right: 20px;">
                                <span class="plays-text">{{ number_format($song->plays, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="chart-empty">Chưa có dữ liệu xếp hạng.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="chart-pane" id="pane-usuk">
            <table class="ranking-table">
                <thead>
                    <tr>
                        <th style="text-align: center;">HẠNG</th>
                        <th>BÀI HÁT</th>
                        <th style="text-align: right; padding-right: 20px;">LƯỢT NGHE</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($chart_usuk as $index => $song)
                        @php
                            $rank = $index + 1;
                            $rankClass = ($rank <= 3) ? "rank-$rank" : "";
                            $img = $song->image_url ?: asset('image/default_cover.png');
                            $artistName = $song->artist->name ?? 'Unknown Artist';
                        @endphp
                        <tr class="song-item-row"
                            data-id="{{ $song->song_id }}"
                            data-title="{{ $song->title }}"
                            data-artist="{{ $artistName }}"
                            data-src="{{ route('song.stream', ['id' => $song->song_id]) }}"
                            data-cover="{{ $img }}">
                            <td class="rank-col {{ $rankClass }}">{{ $rank }}</td>
                            <td>
                                <div class="song-info-flex">
                                    <img src="{{ $img }}" class="song-cover-mini" alt="{{ $song->title }}">
                                    <div>
                                        <span class="song-name-text">{{ $song->title }}</span>
                                        <span class="artist-name-text">{{ $artistName }}</span>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: right; padding-right: 20px;">
                                <span class="plays-text">{{ number_format($song->plays, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="chart-empty">Chưa có dữ liệu xếp hạng.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.song-item-row').forEach((row) => {
        row.addEventListener('click', function () {
            if (typeof window.playSong === 'function') {
                window.playSong(this.dataset.id);
            }
        });
    });

    document.querySelectorAll('.chart-tab').forEach((tab) => {
        tab.addEventListener('click', function () {
            const targetId = this.dataset.target;

            document.querySelectorAll('.chart-tab').forEach((item) => item.classList.remove('active'));
            document.querySelectorAll('.chart-pane').forEach((pane) => pane.classList.remove('active'));

            this.classList.add('active');
            const targetPane = document.getElementById(targetId);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });
</script>
</x-client-layout>
