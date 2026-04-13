<?php

namespace App\Services;

use App\Models\Song;
use Illuminate\Database\Eloquent\Collection;

class ChartRankingService
{
    public function topTrending(int $limit = 10): Collection
    {
        return $this->baseQuery()
            ->limit($limit)
            ->get()
            ->map(fn (Song $song) => $this->normalizeSong($song));
    }

    public function topVietnam(int $limit = 10): Collection
    {
        return $this->baseQuery()
            ->whereHas('artist', function ($query) {
                $query->where('country', 'like', '%Viet%')
                    ->orWhere('country', 'VN');
            })
            ->limit($limit)
            ->get()
            ->map(fn (Song $song) => $this->normalizeSong($song));
    }

    public function topInternational(int $limit = 10): Collection
    {
        return $this->baseQuery()
            ->whereHas('artist', function ($query) {
                $query->where('country', 'not like', '%Viet%')
                    ->where('country', '!=', 'VN');
            })
            ->limit($limit)
            ->get()
            ->map(fn (Song $song) => $this->normalizeSong($song));
    }

    private function normalizeSong(Song $song): Song
    {
        $song->artist_name = $song->artist?->name ?? 'Unknown Artist';
        $historyCount = (int) ($song->listen_histories_count ?? 0);
        $plays = (int) ($song->plays ?? 0);
        $song->listen_count = max($historyCount, $plays);

        return $song;
    }

    private function baseQuery()
    {
        return Song::query()
            ->with('artist')
            ->withCount('listenHistories')
            ->orderByDesc('listen_histories_count')
            ->orderByDesc('plays');
    }
}
