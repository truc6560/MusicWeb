//AUDIO
const audio = document.getElementById("audioPlayer");
if (!audio) {
    console.error("Không tìm thấy audioPlayer");
}

//UI
// Các nút điều khiển player
const playBtn = document.getElementById("playBtnToggle");
const playIcon = document.getElementById("playIcon");
const nextBtn = document.getElementById("nextBtn");
const prevBtn = document.getElementById("prevBtn");
const shuffleBtn = document.getElementById("shuffleBtn");
const repeatBtn = document.getElementById("repeatBtn");

// Thanh tiến trình
const progress = document.getElementById("progress");
const progressBar = document.getElementById("progressBar");

// Thông tin bài hát đang phát (footer)
const nowTitle = document.getElementById("now-title");
const nowArtist = document.getElementById("now-artist");
const nowCover = document.getElementById("nowCover");
const nowCoverLink = document.getElementById("nowCoverLink");

// Panel bên phải (lyrics + info)
const rightCover = document.getElementById("rightCover");
const rightTitle = document.getElementById("rightTitle");
const rightArtist = document.getElementById("rightArtist");
const lyricsBox = document.getElementById("lyricsBox");

// Thời gian
const durationText = document.getElementById("duration");
const currentTimeText = document.getElementById("currentTime");

//NÚT LIKE
// Nút yêu thích bài hát
const likeBtn = document.querySelector(".player-like-btn"); 
const likeIcon = likeBtn ? likeBtn.querySelector("i") : null;
function isAuthenticatedUser() {
    return window.isAuthenticated === true;
}

//ÂM LƯỢNG 
//Thanh điều chỉnh âm lượng
const volumeSlider = document.querySelector(".volume-slider");
const volumeFill = document.querySelector(".volume-fill");
const volumeIcon = document.getElementById("volumeIcon");

//TRẠNG THÁI
let songList = [];
let currentIndex = 0;
let isShuffle = false;
let isRepeat = false;
let currentSongData = null;
let playerPlaybackState = 'paused';
let restorePlaybackInProgress = false;
let lastSyncedPlayingSongId = null;
let playbackQueueSourceKey = null;

function getCurrentPageQueueKey() {
    return `${window.location.pathname}${window.location.search}`;
}

function collectSongListFromDom() {
    const rows = Array.from(document.querySelectorAll(".song-item-row"));
    const list = rows
        .map((row) => ({
            id: row.dataset.id,
            title: row.dataset.title,
            artist: row.dataset.artist,
            src: row.dataset.src,
            cover: row.dataset.cover
        }))
        .filter((song) => song.id && song.src);

    return { rows, list };
}

function applyQueueFromDom(sourceKey) {
    const { list } = collectSongListFromDom();
    songList = list;
    playbackQueueSourceKey = sourceKey || getCurrentPageQueueKey();

    if (currentSongData && currentSongData.id) {
        const syncedIndex = songList.findIndex((s) => String(s.id) === String(currentSongData.id));
        if (syncedIndex !== -1) {
            currentIndex = syncedIndex;
            currentSongData = songList[syncedIndex];
        }
    }
}

// Biến theo dõi thời gian nghe 
let listenSeconds = 0;
let loggedHistory = false;
let increasedPlay = false; 

function resetListenTracking() {
    listenSeconds = 0;
    loggedHistory = false;
    increasedPlay = false;
}

function incrementPlayForSong(songId, csrfToken) {
    if (!songId) return Promise.resolve(false);

    return fetch("/ajax/increment-view", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            "X-CSRF-TOKEN": csrfToken || ''
        },
        body: `song_id=${encodeURIComponent(songId)}`
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Increment failed");
            }
            return response.json();
        })
        .then((data) => {
            if (!data || data.status !== "success") {
                throw new Error("Increment not successful");
            }
            return true;
        });
}

function storeGuestHistory(song) {
    if (!song || !song.id) return;

    const historyKey = "guest_listen_history";
    const existing = JSON.parse(localStorage.getItem(historyKey) || "[]");
    const safeExisting = Array.isArray(existing) ? existing : [];

    const item = {
        id: song.id,
        title: song.title || "",
        artist: song.artist || "Unknown Artist",
        src: song.src || "",
        cover: song.cover || "",
        listened_at: new Date().toISOString()
    };

    const filtered = safeExisting.filter((entry) => String(entry.id) !== String(song.id));
    filtered.unshift(item);

    localStorage.setItem(historyKey, JSON.stringify(filtered.slice(0, 50)));
}

function updateNowCoverLink(songId) {
    if (!nowCoverLink || !songId || !window.songDetailsBaseUrl) return;
    nowCoverLink.href = `${window.songDetailsBaseUrl}/${songId}/chitiet`;
}

function getSongPlayButtonHtml(isPlaying) {
    return isPlaying
        ? '<span class="song-play-wave" aria-hidden="true"><span></span><span></span><span></span></span>'
        : '<i class="fas fa-play" style="font-size: 11px;"></i>';
}

function syncSongPlayButtons() {
    const activeSongId = currentSongData && currentSongData.id ? String(currentSongData.id) : null;
    const isPlaying = activeSongId && playerPlaybackState === 'playing';

    const syncSearchButtons = () => {
        if (typeof window.syncPlaylistSearchPlayButtons === 'function') {
            window.syncPlaylistSearchPlayButtons();
        }
    };

    if (lastSyncedPlayingSongId && lastSyncedPlayingSongId !== activeSongId) {
        const previousRow = document.querySelector(`.song-item-row[data-id="${lastSyncedPlayingSongId}"]`);
        const previousButton = previousRow ? previousRow.querySelector('.song-play-btn') : null;
        if (previousButton) {
            previousButton.classList.remove('is-playing');
            previousButton.innerHTML = getSongPlayButtonHtml(false);
        }
    }

    if (!isPlaying) {
        if (lastSyncedPlayingSongId) {
            const previousRow = document.querySelector(`.song-item-row[data-id="${lastSyncedPlayingSongId}"]`);
            const previousButton = previousRow ? previousRow.querySelector('.song-play-btn') : null;
            if (previousButton) {
                previousButton.classList.remove('is-playing');
                previousButton.innerHTML = getSongPlayButtonHtml(false);
            }
        }
        lastSyncedPlayingSongId = null;
        syncSearchButtons();
        return;
    }

    const activeRow = document.querySelector(`.song-item-row[data-id="${activeSongId}"]`);
    const activeButton = activeRow ? activeRow.querySelector('.song-play-btn') : null;
    if (!activeButton) return;

    activeButton.classList.add('is-playing');
    activeButton.innerHTML = getSongPlayButtonHtml(true);
    lastSyncedPlayingSongId = activeSongId;
    syncSearchButtons();
}

//LOAD BÀI HÁT
function loadSong(song, autoPlay = true) {
    if (!song || !song.src) return;

    const isSameSong = currentSongData && String(currentSongData.id) === String(song.id);
    if (isSameSong) {
        // Khi user bấm lại đúng bài đang phát từ danh sách, coi như bắt đầu một lượt nghe mới.
        if (autoPlay) {
            audio.currentTime = 0;
            resetListenTracking();
        }

        const duration = Number(audio.duration) || 0;
        const isAtSongStart = audio.currentTime <= 1;
        const isAtSongEnd = duration > 0 && audio.currentTime >= (duration - 1);

        if (isAtSongStart || isAtSongEnd || audio.ended) {
            audio.currentTime = 0;
            resetListenTracking();
        }

        currentSongData = song;
        updateRowHighlight(song.id);
        playerPlaybackState = autoPlay ? 'playing' : 'paused';
        syncSongPlayButtons();
        savePlayerState(!audio.paused);

        if (autoPlay && audio.paused) {
            audio.play().catch(err => console.log("Autoplay blocked:", err));
            if (playIcon) playIcon.classList.replace("fa-play", "fa-pause");
        }
        return;
    }

    currentSongData = song;

    audio.src = song.src;
    audio.load();

    //SỬA LỖI ẢNH BÌA (Ảnh dự phòng nếu thiếu)
    //Ảnh mặc định nếu không có cover
    const defaultImage = "https://via.placeholder.com/150"; 
    const coverImage = (song.cover && song.cover.trim() !== "") ? song.cover : defaultImage;

    // Cập nhật UI player
    if (nowTitle) nowTitle.textContent = song.title;
    if (nowArtist) nowArtist.textContent = song.artist;
    if (nowCover) nowCover.src = coverImage;
    updateNowCoverLink(song.id);

    // Panel phải
    if (rightCover) rightCover.src = coverImage;
    if (rightTitle) rightTitle.textContent = song.title;
    if (rightArtist) rightArtist.textContent = song.artist;

    // Kiểm tra trạng thái like
    checkLikeStatus(song.id);

    // Load lyrics
    loadLyrics(song.id);
    // Highlight bài đang phát
    updateRowHighlight(song.id);
    playerPlaybackState = autoPlay ? 'playing' : 'paused';
    syncSongPlayButtons();

    // Reset biến đếm
    resetListenTracking();

    // Lưu trạng thái
    savePlayerState(autoPlay);

    // Tự động phát
    if (autoPlay) {
        audio.play().catch(err => console.log("Autoplay blocked:", err));
        if (playIcon) playIcon.classList.replace("fa-play", "fa-pause");
    }
}

//HÀM PHỤ: LƯU TRẠNG THÁI PLAYER
function savePlayerState(isPlaying = false) {
    const currentSong = currentSongData || songList[currentIndex];
    if (!currentSong) return;

    const state = {
        ...currentSong,
        currentTime: audio.currentTime,
        isPlaying: isPlaying,
        loggedHistory: loggedHistory,
        increasedPlay: increasedPlay
    };
    localStorage.setItem("currentSong", JSON.stringify(state));
}

function persistPlayerState() {
    if (!audio) return;
    savePlayerState(!audio.paused);
}

function getCurrentSongData() {
    return currentSongData || (songList[currentIndex] || null);
}

//LẤY LỜI BÀI HÁT
function loadLyrics(songId) {
    if (!lyricsBox) return;
    lyricsBox.innerHTML = "Đang tải lời bài hát...";
    fetch(`/song/${songId}`)
        .then(r => r.json())
        .then(d => {
            const lyrics = (d && d.lyrics) ? d.lyrics : "Chưa có lời bài hát";
            lyricsBox.innerHTML = lyrics.replace(/\n/g, "<br>");
        })
        .catch(() => lyricsBox.innerHTML = "Không thể tải lời bài hát");
}

function playSong(songId) {
    if (!songId) return;

    // Người dùng bấm phát từ trang hiện tại -> đổi queue sang danh sách của trang đó.
    buildSongList({ forceQueueRefresh: true, sourceKey: getCurrentPageQueueKey() });

    fetch(`/song/${songId}`)
        .then(r => r.json())
        .then(song => {
            if (!song || !song.audio_url) return;

            const normalizedSong = {
                id: song.song_id,
                title: song.title,
                artist: song.artist,
                src: song.audio_url,
                cover: song.cover
            };

            const existingIndex = songList.findIndex(s => String(s.id) === String(normalizedSong.id));
            if (existingIndex === -1) {
                songList.push(normalizedSong);
                currentIndex = songList.length - 1;
            } else {
                currentIndex = existingIndex;
                songList[currentIndex] = normalizedSong;
            }

            loadSong(songList[currentIndex]);
        })
        .catch(err => console.error("Không thể phát bài hát:", err));
}

window.playSong = playSong;
window.loadSong = loadSong;
window.updateRowHighlight = updateRowHighlight;
window.buildSongList = buildSongList;
window.persistPlayerState = persistPlayerState;
window.getCurrentSongData = getCurrentSongData;

//FORMAT THỜI GIAN
function formatTime(sec) {
    if (isNaN(sec)) return "0:00";
    const m = Math.floor(sec / 60);
    const s = Math.floor(sec % 60).toString().padStart(2, "0");
    return `${m}:${s}`;
}

//NÚT PLAY HOẶC PAUSE
if (playBtn) {
    playBtn.onclick = () => {
        if (audio.paused) {
            audio.play();
            playIcon.classList.replace("fa-play", "fa-pause");
            playerPlaybackState = 'playing';
            savePlayerState(true);
        } else {
            audio.pause();
            playIcon.classList.replace("fa-pause", "fa-play");
            playerPlaybackState = 'paused';
            savePlayerState(false);
        }
    };
}
// Đồng bộ icon khi audio tự play/pause
audio.onplay = () => {
    restorePlaybackInProgress = false;
    playerPlaybackState = 'playing';
    if (playIcon) playIcon.classList.replace("fa-play", "fa-pause");
    syncSongPlayButtons();
};
audio.onpause = () => {
    if (restorePlaybackInProgress && playerPlaybackState === 'playing') {
        return;
    }

    playerPlaybackState = 'paused';
    if (playIcon) playIcon.classList.replace("fa-pause", "fa-play");
    syncSongPlayButtons();
};

//THANH PROGRESS
audio.ontimeupdate = () => {
    if (!audio.duration) return;

    // Cập nhật thanh tiến trình
    if (progressBar) {
        progressBar.style.width = (audio.currentTime / audio.duration * 100) + "%";
    }
    // Hiển thị thời gian
    if (currentTimeText) currentTimeText.textContent = formatTime(audio.currentTime);
    if (durationText) durationText.textContent = formatTime(audio.duration);

    // LOGIC LỊCH SỬ & LƯỢT NGHE
    listenSeconds = Math.floor(audio.currentTime);
    const currentSong = currentSongData || songList[currentIndex];
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    
    if (currentSong && isAuthenticatedUser()) {
        // Sau 10s → lưu lịch sử nghe
        if (listenSeconds >= 10 && !loggedHistory) {
            loggedHistory = true;
            savePlayerState(audio.paused ? false : true); // Lưu lại biến loggedHistory vào storage
            fetch("/ajax/record-history", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: `song_id=${encodeURIComponent(currentSong.id)}`
            });
        }
    }

    // Sau 30s → tăng lượt nghe (áp dụng cho cả guest và user đăng nhập)
    if (currentSong && listenSeconds >= 30 && !increasedPlay) {
        increasedPlay = true;
        savePlayerState(audio.paused ? false : true);

        incrementPlayForSong(currentSong.id, csrfToken)
            .catch(() => {
                // Nếu lỗi mạng/server, mở lại cờ để có thể thử tăng lại trong lần ontimeupdate sau.
                increasedPlay = false;
                savePlayerState(audio.paused ? false : true);
            });
    }

    if (currentSong && !isAuthenticatedUser() && listenSeconds >= 10 && !loggedHistory) {
        loggedHistory = true;
        savePlayerState(audio.paused ? false : true);
        storeGuestHistory(currentSong);
    }
    
    // Lưu trạng thái mỗi 5s (để reload không mất)
    if (listenSeconds % 5 === 0) {
        savePlayerState(!audio.paused);
    } 
};

// CLICK VÀO THANH PROGRESS ĐỂ TUA
if (progress) {
    progress.onclick = e => {
        audio.currentTime = (e.offsetX / progress.clientWidth) * audio.duration;
    };
}

// NEXT/PREV/SHUFFLE
if (shuffleBtn) {
    shuffleBtn.onclick = () => {
        isShuffle = !isShuffle;
        shuffleBtn.classList.toggle("active", isShuffle);
    };
}
if (repeatBtn) {
    repeatBtn.onclick = () => {
        isRepeat = !isRepeat;
        repeatBtn.classList.toggle("active", isRepeat);
    };
}
if (nextBtn) nextBtn.onclick = () => changeSong(1);
if (prevBtn) prevBtn.onclick = () => changeSong(-1);

// CHUYỂN BÀI HÁT
function changeSong(step) {
    if (!songList.length) return;
    if (isShuffle) {
        let i;
        do { i = Math.floor(Math.random() * songList.length); } while (i === currentIndex && songList.length > 1);
        currentIndex = i;
    } else {
        currentIndex = (currentIndex + step + songList.length) % songList.length;
    }
    loadSong(songList[currentIndex]);
}

// KHI BÀI HÁT KẾT THÚC
audio.onended = () => {
    const currentSong = currentSongData || songList[currentIndex];
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Fallback cho bài ngắn (<30s): khi phát hết bài mà chưa cộng lượt nghe thì cộng 1 lần.
    if (currentSong && !increasedPlay) {
        increasedPlay = true;
        savePlayerState(true);
        incrementPlayForSong(currentSong.id, csrfToken).catch(() => {
            increasedPlay = false;
            savePlayerState(true);
        });
    }

    if (isRepeat) {
        audio.currentTime = 0;
        resetListenTracking();
        savePlayerState(true);
        playerPlaybackState = 'playing';
        audio.play();
    } else {
        playerPlaybackState = 'paused';
        changeSong(1);
    }
    syncSongPlayButtons();
};

// LOGIC LIKE (TIM)
// Hàm cập nhật giao diện tim
function updateLikeIcon(isLiked) {
    if (!likeIcon) return;
    if (isLiked) {
        likeIcon.className = "fas fa-heart"; // Tim đặc
        likeIcon.style.color = "#ff007a";    // Màu hồng
    } else {
        likeIcon.className = "far fa-heart"; // Tim rỗng
        likeIcon.style.color = "var(--text-sub)"; // Màu xám
    }
}

// Hàm kiểm tra bài hát đã like chưa (gọi khi load bài)
function checkLikeStatus(songId) {
    if (!songId || !likeIcon) return;

    if (!isAuthenticatedUser()) {
        updateLikeIcon(false);
        return;
    }

    // Reset về trạng thái chưa like
    updateLikeIcon(false);

    fetch("/ajax/like-song/status?song_id=" + encodeURIComponent(songId), {
        headers: { "Accept": "application/json" }
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            updateLikeIcon(data.liked);
        }
    })
    .catch(err => console.error("Lỗi check like:", err));
}


// Click nút tim
if (likeBtn && likeIcon) {
    likeBtn.onclick = () => {
        const currentSong = currentSongData || songList[currentIndex];
        if (!currentSong) return;

        if (!isAuthenticatedUser()) {
            alert('Vui lòng đăng nhập để thêm bài hát vào danh sách yêu thích.');
            updateLikeIcon(false);
            return;
        }

        // UI mượt: đổi ngay lập tức (optimistic UI)
        const isCurrentlyLiked = likeIcon.classList.contains("fas");
        updateLikeIcon(!isCurrentlyLiked);

        fetch("/ajax/like-song", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: `song_id=${encodeURIComponent(currentSong.id)}`
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                const liked = (data.action === 'liked');
                updateLikeIcon(liked);
            } else {
                // Nếu lỗi → trả về trạng thái cũ
                updateLikeIcon(isCurrentlyLiked);
                alert(data.message);
            }
        })
        .catch(err => {
            console.error(err);
            updateLikeIcon(isCurrentlyLiked);
        });
    };
}

// LOGIC VOLUME (ÂM LƯỢNG)
// Xử lý thanh trượt volume
if (volumeSlider) {
    volumeSlider.onclick = (e) => {
        const sliderWidth = volumeSlider.clientWidth;
        const clickX = e.offsetX;
        let percent = clickX / sliderWidth;
        
        // Giới hạn 0 -> 1
        if (percent < 0) percent = 0;
        if (percent > 1) percent = 1;

        audio.volume = percent;
    };
}

// Tự động cập nhật UI khi âm lượng thay đổi
audio.onvolumechange = () => {

    // Cập nhật thanh volume
    if (volumeFill) {
        volumeFill.style.width = (audio.volume * 100) + "%";
    }

    // Đổi icon loa
    if (volumeIcon) {
        volumeIcon.className = "fas";

        if (audio.muted || audio.volume === 0) {
            volumeIcon.classList.add("fa-volume-mute");
        } else if (audio.volume < 0.5) {
            volumeIcon.classList.add("fa-volume-down");
        } else {
            volumeIcon.classList.add("fa-volume-up");
        }
    }

    // Lưu âm lượng để lần sau nhớ
    localStorage.setItem("playerVolume", audio.volume);
};

// Click icon loa để Mute/Unmute
if (volumeIcon) {
    volumeIcon.onclick = () => {
        if (audio.volume > 0) {
            audio.dataset.prevVolume = audio.volume; // Lưu mức cũ
            audio.volume = 0;
        } else {
            audio.volume = audio.dataset.prevVolume || 1; // Khôi phục
        }
    };
}

/* KHỞI TẠO & KHÔI PHỤC */
// Build danh sách bài hát từ HTML
function buildSongList(options = {}) {
    const sourceKey = options.sourceKey || getCurrentPageQueueKey();
    const forceQueueRefresh = !!options.forceQueueRefresh;
    const shouldRefreshQueue = forceQueueRefresh || !currentSongData || !playbackQueueSourceKey || playbackQueueSourceKey === sourceKey;

    const { rows, list } = collectSongListFromDom();

    rows.forEach((row) => {
        const playButton = row.querySelector(".song-play-btn");
        if (!playButton) return;

        playButton.onclick = (event) => {
            event.preventDefault();
            event.stopPropagation();

            const clickSourceKey = getCurrentPageQueueKey();
            applyQueueFromDom(clickSourceKey);

            const clickedSongId = row.dataset.id;
            const clickedIndex = songList.findIndex((song) => String(song.id) === String(clickedSongId));
            if (clickedIndex === -1) return;

            currentIndex = clickedIndex;
            loadSong(songList[currentIndex]);
        };
    });

    if (shouldRefreshQueue) {
        songList = list;
        playbackQueueSourceKey = sourceKey;

        if (currentSongData && currentSongData.id) {
            const syncedIndex = songList.findIndex((s) => String(s.id) === String(currentSongData.id));
            if (syncedIndex !== -1) {
                currentIndex = syncedIndex;
                currentSongData = songList[syncedIndex];
            }
        }
    }

    syncSongPlayButtons();
}

// Highlight bài đang phát
function updateRowHighlight(songId) {
    document.querySelectorAll(".song-item-row").forEach(r => r.classList.remove("active-playing"));
    const row = document.querySelector(`.song-item-row[data-id="${songId}"]`);
    if (row) row.classList.add("active-playing");
    syncSongPlayButtons();
}

function getCurrentPlaybackSnapshot() {
    return {
        songId: currentSongData && currentSongData.id ? String(currentSongData.id) : null,
        state: playerPlaybackState,
    };
}

document.addEventListener("DOMContentLoaded", () => {
    buildSongList({ forceQueueRefresh: true, sourceKey: getCurrentPageQueueKey() });

    // Khôi phục Volume
    const savedVol = localStorage.getItem("playerVolume");
    if (savedVol !== null) audio.volume = parseFloat(savedVol);

    // Khôi phục Bài hát đang phát
    const saved = JSON.parse(localStorage.getItem("currentSong"));
    if (!saved || !saved.src) return;

    currentSongData = {
        id: saved.id,
        title: saved.title,
        artist: saved.artist,
        src: saved.src,
        cover: saved.cover
    };

    currentIndex = songList.findIndex(s => s.id == saved.id);
    if (currentIndex === -1) {
        currentIndex = 0;
    } else {
        currentSongData = songList[currentIndex];
    }

    audio.src = saved.src;
    const savedCurrentTime = Number(saved.currentTime) || 0;

    const resumeAfterMetadata = () => {
        const hasDuration = Number.isFinite(audio.duration) && audio.duration > 0;
        if (savedCurrentTime > 0 && hasDuration) {
            audio.currentTime = Math.min(savedCurrentTime, audio.duration - 0.25);
        }

        if (saved.isPlaying) {
            restorePlaybackInProgress = true;
            playerPlaybackState = 'playing';
            syncSongPlayButtons();
            const playPromise = audio.play();
            if (playPromise !== undefined) {
                playPromise.catch(() => {
                    restorePlaybackInProgress = false;
                    playerPlaybackState = 'paused';
                    syncSongPlayButtons();
                    console.log("Trình duyệt chặn autoplay");
                });
            }
        } else {
            restorePlaybackInProgress = false;
            playerPlaybackState = 'paused';
            syncSongPlayButtons();
        }
    };

    if (audio.readyState >= 1) {
        resumeAfterMetadata();
    } else {
        audio.addEventListener("loadedmetadata", resumeAfterMetadata, { once: true });
    }

    // Khôi phục trạng thái lịch sử
    loggedHistory = saved.loggedHistory || false;
    increasedPlay = saved.increasedPlay || false;

    // Ảnh fallback nếu thiếu
    const defaultImage = "https://via.placeholder.com/150"; 
    const coverImage = (saved.cover && saved.cover.trim() !== "") ? saved.cover : defaultImage;

    // Update UI
    if (nowTitle) nowTitle.textContent = saved.title;
    if (nowArtist) nowArtist.textContent = saved.artist;
    if (nowCover) nowCover.src = coverImage;
    updateNowCoverLink(saved.id);
    if (rightCover) rightCover.src = coverImage;
    if (rightTitle) rightTitle.textContent = saved.title;
    if (rightArtist) rightArtist.textContent = saved.artist;

    // Hiện Player bar
    const playerBar = document.querySelector('.player-bar');
    if (playerBar) playerBar.style.display = 'flex';

    updateRowHighlight(saved.id);
    syncSongPlayButtons();
    if (saved.id) {
        loadLyrics(saved.id);
        checkLikeStatus(saved.id); // <-- QUAN TRỌNG: Check lại tim khi F5
    }

    // Nếu trước đó đang pause thì chỉ khôi phục vị trí, không tự phát
});

window.getCurrentPlaybackSnapshot = getCurrentPlaybackSnapshot;
window.syncSongPlayButtons = syncSongPlayButtons;

window.addEventListener("pagehide", persistPlayerState);
document.addEventListener("visibilitychange", () => {
    if (document.hidden) {
        persistPlayerState();
    }
});