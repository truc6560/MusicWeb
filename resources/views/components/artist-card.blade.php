@props(['artist'])

<a class="artist-card" href="{{ route('artists.show', $artist->artist_id) }}">
    <img src='{{ $artist->image_url ?: asset('image/default_artist.png') }}' 
         class='artist-img' 
         onerror="this.src='{{ asset('image/default_artist.png') }}'">
    
    <div class='artist-name'>{{ $artist->name }}</div>
    <div class='artist-country'>{{ $artist->country }}</div>
    
    <button class='btn-follow {{ $artist->is_followed ? 'active' : '' }}' 
            id='btn-{{ $artist->artist_id }}' 
            onclick="toggleFollow(event, {{ $artist->artist_id }})">
        {!! $artist->is_followed ? '<i class="fas fa-check"></i> Đang theo dõi' : '<i class="fas fa-plus"></i> Theo dõi' !!}
    </button>
</a>