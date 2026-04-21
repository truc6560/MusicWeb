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
const sidebarLyricsTabBtn = document.getElementById("sidebarLyricsTabBtn");
const sidebarInfoTabBtn = document.getElementById("sidebarInfoTabBtn");
const sidebarInfoPanel = document.getElementById("sidebarInfoPanel");
const sidebarQuickInfoCard = document.getElementById("sidebarQuickInfoCard");
const sidebarRelatedSongsList = document.getElementById("sidebarRelatedSongsList");
const sidebarFollowArtistBtn = document.getElementById("sidebarFollowArtistBtn");

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
let queuePlaybackCursor = null;
let queueList = [];
let parsedLyrics = [];
let activeLyricIndex = -1;
let lyricsRequestToken = 0;
let currentSidebarInfo = null;
let currentSidebarPanel = 'lyrics';

function setSidebarPanel(mode, options = {}) {
    if (!lyricsBox || !sidebarInfoPanel) return;

    const immediate = !!options.immediate;
    const showLyrics = mode !== 'info';
    const nextPanel = showLyrics ? 'lyrics' : 'info';
    const showEl = showLyrics ? lyricsBox : sidebarInfoPanel;
    const hideEl = showLyrics ? sidebarInfoPanel : lyricsBox;

    if (currentSidebarPanel === nextPanel && !immediate) {
        return;
    }

    if (immediate) {
        showEl.style.display = showLyrics ? '' : 'block';
        showEl.style.opacity = '1';
        showEl.style.transform = 'translateY(0)';
        showEl.style.pointerEvents = 'auto';

        hideEl.style.display = 'none';
        hideEl.style.opacity = '0';
        hideEl.style.transform = 'translateY(8px)';
        hideEl.style.pointerEvents = 'none';
    } else {
        hideEl.style.pointerEvents = 'none';
        hideEl.animate([
            { opacity: 1, transform: 'translateY(0)' },
            { opacity: 0, transform: 'translateY(8px)' }
        ], {
            duration: 220,
            easing: 'cubic-bezier(0.2, 0.8, 0.2, 1)',
            fill: 'forwards'
        }).onfinish = () => {
            hideEl.style.display = 'none';
            hideEl.style.opacity = '0';
            hideEl.style.transform = 'translateY(8px)';
        };

        showEl.style.display = showLyrics ? '' : 'block';
        showEl.style.pointerEvents = 'auto';
        showEl.animate([
            { opacity: 0, transform: 'translateY(8px)' },
            { opacity: 1, transform: 'translateY(0)' }
        ], {
            duration: 260,
            easing: 'cubic-bezier(0.2, 0.8, 0.2, 1)',
            fill: 'forwards'
        });
    }

    if (sidebarLyricsTabBtn) {
        sidebarLyricsTabBtn.classList.toggle('is-active', showLyrics);
    }
    if (sidebarInfoTabBtn) {
        sidebarInfoTabBtn.classList.toggle('is-active', !showLyrics);
    }

    currentSidebarPanel = nextPanel;
}

function formatNumber(value) {
    const num = Number(value || 0);
    return Number.isFinite(num) ? num.toLocaleString('vi-VN') : '0';
}

function renderSidebarInfo(songInfo) {
    currentSidebarInfo = songInfo || null;
    if (!sidebarInfoPanel || !sidebarQuickInfoCard || !sidebarRelatedSongsList || !songInfo) return;

    const album = songInfo.album || 'Single';
    const genres = songInfo.genres || 'Chưa cập nhật';
    const plays = formatNumber(songInfo.plays);

    sidebarQuickInfoCard.innerHTML = `
        <div class="sidebar-info-row"><span class="sidebar-info-label">Album</span><span class="sidebar-info-value">${escapeHtml(String(album))}</span></div>
        <div class="sidebar-info-row"><span class="sidebar-info-label">Thể loại</span><span class="sidebar-info-value">${escapeHtml(String(genres))}</span></div>
        <div class="sidebar-info-row"><span class="sidebar-info-label">Lượt nghe</span><span class="sidebar-info-value">${escapeHtml(String(plays))}</span></div>
    `;

    const relatedSongs = Array.isArray(songInfo.related_songs) ? songInfo.related_songs : [];
    if (!relatedSongs.length) {
        sidebarRelatedSongsList.innerHTML = '<div style="color:#8f95af;font-size:12px;">Chưa có bài hát cùng nghệ sĩ.</div>';
    } else {
        sidebarRelatedSongsList.innerHTML = relatedSongs.map((item) => `
            <button type="button" class="sidebar-related-item" data-id="${escapeHtml(String(item.song_id || ''))}">
                <img src="${escapeHtml(String(item.cover || ''))}" alt="${escapeHtml(String(item.title || ''))}" class="sidebar-related-thumb">
                <div style="min-width:0;">
                    <div class="sidebar-related-title">${escapeHtml(String(item.title || ''))}</div>
                    <div class="sidebar-related-artist">${escapeHtml(String(item.artist || 'Unknown Artist'))}</div>
                </div>
            </button>
        `).join('');

        sidebarRelatedSongsList.querySelectorAll('.sidebar-related-item').forEach((button) => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                const songId = button.dataset.id;
                if (!songId) return;
                playSong(songId);
            });
        });
    }

    if (sidebarFollowArtistBtn) {
        const artistId = songInfo.artist_id;
        if (artistId) {
            sidebarFollowArtistBtn.style.display = 'block';
            sidebarFollowArtistBtn.dataset.id = String(artistId);
            const liked = !!songInfo.artist_liked;
            sidebarFollowArtistBtn.classList.toggle('liked', liked);
            const icon = sidebarFollowArtistBtn.querySelector('i');
            if (icon) {
                icon.className = liked ? 'fas fa-user-check' : 'fas fa-user-plus';
            }
            sidebarFollowArtistBtn.title = liked ? 'Đang theo dõi nghệ sĩ' : 'Theo dõi nghệ sĩ';
        } else {
            sidebarFollowArtistBtn.style.display = 'none';
            sidebarFollowArtistBtn.dataset.id = '';
            sidebarFollowArtistBtn.classList.remove('liked');
            sidebarFollowArtistBtn.title = '';
        }
    }
}

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
    queuePlaybackCursor = null;

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

// Lưu queue vào localStorage
function getQueueStorageKey() {
    const parsedUserId = Number.parseInt(window.currentUserId, 10);
    if (Number.isFinite(parsedUserId) && parsedUserId > 0) {
        return `playerQueue:user:${parsedUserId}`;
    }
    return "playerQueue:guest";
}

function saveQueueToLocalStorage() {
    localStorage.setItem(getQueueStorageKey(), JSON.stringify(queueList));
}

// Khôi phục queue từ localStorage
function loadQueueFromLocalStorage() {
    const queueStorageKey = getQueueStorageKey();
    let saved = localStorage.getItem(queueStorageKey);

    // Di trú dữ liệu key cũ cho khách để không mất queue sau khi cập nhật.
    if (!saved && queueStorageKey === "playerQueue:guest") {
        const legacySaved = localStorage.getItem("playerQueue");
        if (legacySaved) {
            saved = legacySaved;
            localStorage.setItem(queueStorageKey, legacySaved);
            localStorage.removeItem("playerQueue");
        }
    }

    if (saved) {
        try {
            queueList = JSON.parse(saved);
        } catch(e) { queueList = []; }
    } else {
        queueList = [];
    }
    renderQueueSidebar();
}

// Hiển thị danh sách chờ trong sidebar
function renderQueueSidebar() {
    const queueContainer = document.getElementById("queue-list");
    const queueListContainer = document.getElementById("queue-list-container");
    if (!queueContainer) return;

    if (!queueList.length) {
        if (queueListContainer) {
            queueListContainer.classList.remove("has-items");
        }
        queueContainer.innerHTML = "Chưa có bài hát nào...";
        return;
    }

    if (queueListContainer) {
        queueListContainer.classList.add("has-items");
    }

    let html = '<div style="display: flex; flex-direction: column; gap: 8px;">';
    queueList.forEach((item, idx) => {
        const coverImg = (item.cover && item.cover.trim() !== "") ? item.cover : "https://via.placeholder.com/150";
        html += `
            <div class="queue-item" data-queue-index="${idx}" style="cursor: pointer;">
                <img src="${coverImg}" alt="${escapeHtml(item.title)}" class="queue-item-img" onerror="this.src='https://via.placeholder.com/150'">
                <div class="queue-item-info">
                    <div class="queue-item-title">${escapeHtml(item.title)}</div>
                    <div class="queue-item-artist">${escapeHtml(item.artist)}</div>
                </div>
                <i class="fas fa-times queue-item-remove" data-queue-index="${idx}"></i>
            </div>
        `;
    });
    html += '</div>';
    queueContainer.innerHTML = html;

    // Gắn sự kiện click vào bài để phát
    document.querySelectorAll('.queue-item').forEach(item => {
        item.addEventListener('click', (e) => {
            // Nếu click vào nút xóa, không phát bài
            if (e.target.closest('.queue-item-remove')) return;
            
            const queueIndex = parseInt(item.getAttribute('data-queue-index'));
            if (!isNaN(queueIndex)) {
                playFromQueue(queueIndex);
            }
        });
    });

    // Gắn sự kiện xóa cho từng nút
    document.querySelectorAll('.queue-item-remove').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const idx = parseInt(btn.getAttribute('data-queue-index'));
            if (!isNaN(idx)) {
                removeFromQueue(idx);
            }
        });
    });
}

// Phát bài từ danh sách chờ theo index
function playFromQueue(queueIndex) {
    if (queueIndex < 0 || queueIndex >= queueList.length) return;

    // Phát ngay bài được chọn và loại khỏi danh sách chờ,
    // để các bài còn lại tiếp tục chờ phát sau khi bài hiện tại kết thúc.
    const selectedSong = queueList.splice(queueIndex, 1)[0];
    saveQueueToLocalStorage();
    renderQueueSidebar();

    if (!selectedSong || !selectedSong.id) return;

    const existingIndex = songList.findIndex((song) => String(song.id) === String(selectedSong.id));
    if (existingIndex === -1) {
        songList.push(selectedSong);
        currentIndex = songList.length - 1;
    } else {
        songList[existingIndex] = { ...songList[existingIndex], ...selectedSong };
        currentIndex = existingIndex;
    }

    playbackQueueSourceKey = getCurrentPageQueueKey();
    queuePlaybackCursor = null;
    loadSong(songList[currentIndex]);
}

// Thêm bài hát vào queue
async function addToQueue(songId) {
    if (!songId) return false;
    console.log('Thêm vào queue:', songId);

    // Tìm bài hát trong songList trước
    let song = songList.find(s => String(s.id) === String(songId));
    if (!song) {
        try {
            const response = await fetch(`/song/${songId}`);
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            const data = await response.json();
            song = {
                id: songId,
                title: data.title || "Không rõ",
                artist: data.artist || "Unknown",
                src: data.audio_url || `/song/${songId}/stream`,
                cover: data.cover || "https://via.placeholder.com/150"
            };
        } catch (err) {
            console.error("Lỗi lấy thông tin bài hát:", err);
            return false;
        }
    }

    const existingIndex = queueList.findIndex(item => String(item.id) === String(songId));
    let movedToFront = false;

    if (existingIndex !== -1) {
        const existingSong = queueList.splice(existingIndex, 1)[0];
        queueList.unshift({ ...existingSong, ...song });
        movedToFront = true;
    } else {
        queueList.push(song);
    }

    if (playbackQueueSourceKey === 'queue-playback' && currentSongData && currentSongData.id) {
        const currentSongQueueIndex = queueList.findIndex(item => String(item.id) === String(currentSongData.id));
        queuePlaybackCursor = currentSongQueueIndex >= 0 ? currentSongQueueIndex : queuePlaybackCursor;
    }

    console.log('Queue hiện tại:', queueList);
    saveQueueToLocalStorage();
    renderQueueSidebar();

    // Hiển thị thông báo
    if (window.showToast) {
        window.showToast(
            movedToFront
                ? `"${song.title}" đã có sẵn, đã chuyển lên đầu danh sách chờ`
                : `Đã thêm "${song.title}" vào danh sách chờ`,
            'success'
        );
    } else {
        showToast(
            movedToFront
                ? `"${song.title}" đã có sẵn, đã chuyển lên đầu danh sách chờ`
                : `Đã thêm "${song.title}" vào danh sách chờ`
        );
    }
    return true;
}

// Xóa bài khỏi queue theo index
function removeFromQueue(index) {
    if (index >= 0 && index < queueList.length) {
        const removed = queueList.splice(index, 1)[0];

        if (playbackQueueSourceKey === 'queue-playback' && Number.isInteger(queuePlaybackCursor)) {
            if (index < queuePlaybackCursor) {
                queuePlaybackCursor = Math.max(0, queuePlaybackCursor - 1);
            }
            if (!queueList.length) {
                queuePlaybackCursor = null;
            }
        }

        saveQueueToLocalStorage();
        renderQueueSidebar();
        showToast(`Đã xóa "${removed.title}" khỏi danh sách chờ`);
    }
}

function consumeFinishedQueueSong(finishedSong) {
    if (playbackQueueSourceKey !== 'queue-playback' || !finishedSong || !queueList.length) return;

    const startIndex = Number.isInteger(queuePlaybackCursor) ? Math.max(0, queuePlaybackCursor) : 0;
    let removeIndex = -1;

    for (let i = startIndex; i < queueList.length; i++) {
        if (String(queueList[i].id) === String(finishedSong.id)) {
            removeIndex = i;
            break;
        }
    }

    if (removeIndex === -1) {
        removeIndex = queueList.findIndex((item) => String(item.id) === String(finishedSong.id));
    }

    if (removeIndex === -1) return;

    queueList.splice(removeIndex, 1);
    queuePlaybackCursor = queueList.length ? removeIndex : null;
    saveQueueToLocalStorage();
    renderQueueSidebar();
}

// Lấy bài đầu tiên trong queue và xóa nó (dùng khi chuyển bài)
function getNextFromQueue() {
    if (queueList.length === 0) return null;
    const nextSong = queueList.shift();
    saveQueueToLocalStorage();
    renderQueueSidebar();
    return nextSong;
}

function playNextQueuedSong() {
    const nextSong = getNextFromQueue();
    if (!nextSong || !nextSong.id) return false;

    const existingIndex = songList.findIndex((song) => String(song.id) === String(nextSong.id));
    if (existingIndex === -1) {
        songList.push(nextSong);
        currentIndex = songList.length - 1;
    } else {
        songList[existingIndex] = { ...songList[existingIndex], ...nextSong };
        currentIndex = existingIndex;
    }

    playbackQueueSourceKey = getCurrentPageQueueKey();
    queuePlaybackCursor = null;
    loadSong(songList[currentIndex]);
    return true;
}

// Hàm hiển thị thông báo tạm thời
let toastTimeout = null;
function showToast(message) {
    let toast = document.getElementById('custom-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'custom-toast';
        toast.style.cssText = `
            position: fixed;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.8);
            color: #fff;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 14px;
            z-index: 10000;
            pointer-events: none;
            transition: opacity 0.3s;
            opacity: 0;
        `;
        document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.style.opacity = '1';
    if (toastTimeout) clearTimeout(toastTimeout);
    toastTimeout = setTimeout(() => {
        toast.style.opacity = '0';
    }, 2000);
}

// Helper escapeHtml để tránh XSS
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

function parseLrcContent(lrcText) {
    if (!lrcText || typeof lrcText !== "string") return [];

    const lines = lrcText.split(/\r?\n/);
    const parsed = [];

    for (const rawLine of lines) {
        if (!rawLine) continue;

        const line = rawLine.trim();
        if (!line) continue;

        const timestampMatches = [...line.matchAll(/\[(\d{1,2}):(\d{2})(?:\.(\d{1,3}))?\]/g)];
        if (!timestampMatches.length) continue;

        const text = line.replace(/\[(\d{1,2}):(\d{2})(?:\.(\d{1,3}))?\]/g, '').trim() || '...';

        timestampMatches.forEach((match) => {
            const minute = Number(match[1]) || 0;
            const second = Number(match[2]) || 0;
            const fractionRaw = match[3] || '0';
            const fraction = fractionRaw.length === 3
                ? Number(fractionRaw) / 1000
                : Number(fractionRaw) / 100;
            const time = minute * 60 + second + fraction;

            parsed.push({
                time,
                text
            });
        });
    }

    parsed.sort((a, b) => a.time - b.time);
    return parsed;
}

function buildLyricFileCandidates(title, artist) {
    const normalize = (value) => {
        return String(value || '')
            .replace(/[\\/:*?"<>|]/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();
    };

    const base = [
        title,
        `${artist || ''} - ${title || ''}`,
        `${title || ''} - ${artist || ''}`
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
    if (!lyricsBox) return;

    parsedLyrics = Array.isArray(lines) ? lines : [];
    activeLyricIndex = -1;

    if (!parsedLyrics.length) {
        lyricsBox.innerHTML = '<span class="lyrics-line">Chưa có lời bài hát</span>';
        return;
    }

    lyricsBox.innerHTML = parsedLyrics
        .map((line, index) => `<span class="lyrics-line" data-lyric-index="${index}" data-lyric-time="${Number(line.time || 0)}" title="Nhấn để tua đến câu này">${escapeHtml(line.text)}</span>`)
        .join('');

    updateActiveLyricLine(audio ? audio.currentTime : 0);
}

function renderPlainLyrics(lyricsText) {
    if (!lyricsBox) return;

    parsedLyrics = [];
    activeLyricIndex = -1;

    const safeText = String(lyricsText || 'Chưa có lời bài hát');
    const html = safeText
        .split(/\r?\n/)
        .filter((line) => line.trim() !== '')
        .map((line) => `<span class="lyrics-line">${escapeHtml(line)}</span>`)
        .join('');

    lyricsBox.innerHTML = html || '<span class="lyrics-line">Chưa có lời bài hát</span>';
}

function updateActiveLyricLine(currentTime) {
    if (!lyricsBox || !Array.isArray(parsedLyrics) || !parsedLyrics.length) return;

    let nextIndex = -1;
    for (let i = 0; i < parsedLyrics.length; i += 1) {
        if (currentTime >= parsedLyrics[i].time) {
            nextIndex = i;
        } else {
            break;
        }
    }

    if (nextIndex === activeLyricIndex) return;

    const oldActive = lyricsBox.querySelector('.lyrics-line.active');
    if (oldActive) {
        oldActive.classList.remove('active');
    }

    activeLyricIndex = nextIndex;
    if (activeLyricIndex < 0) return;

    const newActive = lyricsBox.querySelector(`.lyrics-line[data-lyric-index="${activeLyricIndex}"]`);
    if (!newActive) return;

    newActive.classList.add('active');
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
    const currentToken = ++lyricsRequestToken;

    setSidebarPanel('info');

    parsedLyrics = [];
    activeLyricIndex = -1;
    lyricsBox.innerHTML = '<span class="lyrics-line">Đang tải lời bài hát...</span>';

    fetch(`/song/${songId}`)
        .then(r => r.json())
        .then(async (d) => {
            if (currentToken !== lyricsRequestToken) return;

            renderSidebarInfo(d);

            const title = d && d.title ? d.title : '';
            const artist = d && d.artist ? d.artist : '';
            const fallbackLyrics = d && d.lyrics ? d.lyrics : 'Chưa có lời bài hát';

            if (d && d.lrc_url) {
                try {
                    const lrcResponse = await fetch(d.lrc_url, { cache: 'no-store' });
                    if (lrcResponse.ok) {
                        const lrcText = await lrcResponse.text();
                        if (currentToken !== lyricsRequestToken) return;

                        const timedLyrics = parseLrcContent(lrcText);
                        if (timedLyrics.length) {
                            renderTimedLyrics(timedLyrics);
                            return;
                        }
                    }
                } catch (error) {
                    // Fall back to legacy lookup below.
                }
            }

            const fileCandidates = buildLyricFileCandidates(title, artist);
            const lrcText = await fetchLrcByCandidates(fileCandidates);
            if (currentToken !== lyricsRequestToken) return;

            if (lrcText) {
                const timedLyrics = parseLrcContent(lrcText);
                if (timedLyrics.length) {
                    renderTimedLyrics(timedLyrics);
                    return;
                }
            }

            renderPlainLyrics(fallbackLyrics);
        })
        .catch(() => {
            if (currentToken !== lyricsRequestToken) return;
            renderPlainLyrics('Không thể tải lời bài hát');
        });
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
window.addToQueue = addToQueue;
window.removeFromQueue = removeFromQueue;
window.renderQueueSidebar = renderQueueSidebar;
window.getNextFromQueue = getNextFromQueue;
window.bindSongItemContextMenu = bindSongItemContextMenu;
window.playFromQueue = playFromQueue;

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
    updateActiveLyricLine(audio.currentTime);

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
        if (isShuffle) {
            isRepeat = false;
            repeatBtn.classList.toggle("active", false);
        }
        shuffleBtn.classList.toggle("active", isShuffle);
    };
}
if (repeatBtn) {
    repeatBtn.onclick = () => {
        isRepeat = !isRepeat;
        if (isRepeat) {
            isShuffle = false;
            shuffleBtn.classList.toggle("active", false);
        }
        repeatBtn.classList.toggle("active", isRepeat);
    };
}
if (nextBtn) nextBtn.onclick = () => changeSong(1);
if (prevBtn) prevBtn.onclick = () => changeSong(-1);

// CHUYỂN BÀI HÁT
function changeSong(step) {
    if (!songList.length) return;

    if (
        playbackQueueSourceKey === 'queue-playback' &&
        !isShuffle &&
        step > 0 &&
        currentIndex >= songList.length - 1
    ) {
        return;
    }

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
        // Ưu tiên danh sách chờ: bài kế tiếp luôn lấy từ queue nếu queue còn bài.
        if (playNextQueuedSong()) {
            playerPlaybackState = 'playing';
        } else {
            playerPlaybackState = 'paused';
            changeSong(1);
        }
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
        queuePlaybackCursor = null;

        if (currentSongData && currentSongData.id) {
            const syncedIndex = songList.findIndex((s) => String(s.id) === String(currentSongData.id));
            if (syncedIndex !== -1) {
                currentIndex = syncedIndex;
                currentSongData = songList[syncedIndex];
            }
        }
    }

    syncSongPlayButtons();
    bindSongItemContextMenu();
}

// Gắn right-click listener cho song items
function bindSongItemContextMenu() {
    const rows = document.querySelectorAll('.song-item-row');
    rows.forEach((row) => {
        // Xóa listener cũ nếu có (tránh duplicate)
        row.removeEventListener('contextmenu', handleSongContextMenu);
        row.addEventListener('contextmenu', handleSongContextMenu);
    });
}

function handleSongContextMenu(e) {
    e.preventDefault();
    const row = e.currentTarget;
    const songId = row.dataset.id;
    if (songId) {
        addToQueue(songId);
    }
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
    if (sidebarLyricsTabBtn) {
        sidebarLyricsTabBtn.addEventListener('click', () => setSidebarPanel('lyrics'));
    }
    if (sidebarInfoTabBtn) {
        sidebarInfoTabBtn.addEventListener('click', () => setSidebarPanel('info'));
    }
    setSidebarPanel('info', { immediate: true });

    if (lyricsBox) {
        lyricsBox.addEventListener('click', (event) => {
            const line = event.target.closest('.lyrics-line[data-lyric-time]');
            if (!line) return;

            const time = Number(line.dataset.lyricTime);
            if (!Number.isFinite(time) || time < 0) return;

            audio.currentTime = time;
        });
    }

    if (sidebarFollowArtistBtn) {
        sidebarFollowArtistBtn.addEventListener('click', () => {
            const artistId = sidebarFollowArtistBtn.dataset.id;
            if (!artistId) return;

            if (!isAuthenticatedUser()) {
                if (window.showToast) {
                    window.showToast('Vui lòng đăng nhập để theo dõi nghệ sĩ.', 'warning');
                }
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            fetch('/ajax/like-artist', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: `artist_id=${encodeURIComponent(artistId)}`,
            })
                .then((response) => response.json())
                .then((res) => {
                    if (!res || res.status !== 'success') {
                        throw new Error('Like artist failed');
                    }

                    const liked = res.action === 'liked';
                    sidebarFollowArtistBtn.classList.toggle('liked', liked);
                    const icon = sidebarFollowArtistBtn.querySelector('i');
                    if (icon) {
                        icon.className = liked ? 'fas fa-user-check' : 'fas fa-user-plus';
                    }
                    sidebarFollowArtistBtn.title = liked ? 'Đang theo dõi nghệ sĩ' : 'Theo dõi nghệ sĩ';

                    if (currentSidebarInfo) {
                        currentSidebarInfo.artist_liked = liked;
                    }

                    if (window.showToast) {
                        window.showToast(liked ? 'Đã theo dõi nghệ sĩ.' : 'Đã bỏ theo dõi nghệ sĩ.', 'success');
                    }
                })
                .catch(() => {
                    if (window.showToast) {
                        window.showToast('Không thể cập nhật theo dõi nghệ sĩ.', 'error');
                    }
                });
        });
    }

    buildSongList({ forceQueueRefresh: true, sourceKey: getCurrentPageQueueKey() });

    // Khôi phục Queue
    loadQueueFromLocalStorage();

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