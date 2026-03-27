/* ================== AUDIO ================== */
const audio = document.getElementById("audioPlayer");

/* ================== UI ================== */
const playBtn = document.getElementById("playBtnToggle");
const playIcon = document.getElementById("playIcon");
const nextBtn = document.getElementById("nextBtn");
const prevBtn = document.getElementById("prevBtn");
const shuffleBtn = document.getElementById("shuffleBtn");
const repeatBtn = document.getElementById("repeatBtn");

const progress = document.getElementById("progress");
const progressBar = document.getElementById("progressBar");

const nowTitle = document.getElementById("now-title");
const nowArtist = document.getElementById("now-artist");
const nowCover = document.getElementById("nowCover");

const rightCover = document.getElementById("rightCover");
const rightTitle = document.getElementById("rightTitle");
const rightArtist = document.getElementById("rightArtist");
const lyricsBox = document.getElementById("lyricsBox");

const durationText = document.getElementById("duration");
const currentTimeText = document.getElementById("currentTime");

/* --- NÚT LIKE (TIM) --- */
const likeBtn = document.querySelector(".player-like-btn"); 
const likeIcon = likeBtn ? likeBtn.querySelector("i") : null;

/* --- ÂM LƯỢNG (VOLUME) --- */
const volumeSlider = document.querySelector(".volume-slider");
const volumeFill = document.querySelector(".volume-fill");
const volumeIcon = document.getElementById("volumeIcon");

/* ================== STATE ================== */
let songList = [];
let currentIndex = 0;
let isShuffle = false;
let isRepeat = false;

/* Biến kiểm soát lịch sử/lượt nghe */
let listenSeconds = 0;
let loggedHistory = false;
let increasedPlay = false; 

/* ================== LOAD SONG ================== */
function loadSong(song, autoPlay = true) {
    if (!song || !song.src) return;

    audio.src = song.src;
    audio.load();

    // --- 3. SỬA LỖI ẢNH BÌA (Ảnh dự phòng nếu thiếu) ---
    // Nếu không có ảnh, dùng ảnh placeholder hoặc logo mặc định
    const defaultImage = "https://via.placeholder.com/150"; 
    const coverImage = (song.cover && song.cover.trim() !== "") ? song.cover : defaultImage;

    // Footer Player
    if (nowTitle) nowTitle.textContent = song.title;
    if (nowArtist) nowArtist.textContent = song.artist;
    if (nowCover) nowCover.src = coverImage;

    // Right Panel
    if (rightCover) rightCover.src = coverImage;
    if (rightTitle) rightTitle.textContent = song.title;
    if (rightArtist) rightArtist.textContent = song.artist;

    // --- 1. TỰ ĐỘNG KIỂM TRA TRẠNG THÁI LIKE ---
    checkLikeStatus(song.id);

    // Lyrics & Highlight
    loadLyrics(song.id);
    updateRowHighlight(song.id);

    /* ===== RESET BIẾN ĐẾM ===== */
    listenSeconds = 0;
    loggedHistory = false;
    increasedPlay = false;

    // Save state
    savePlayerState(autoPlay);

    if (autoPlay) {
        audio.play().catch(err => console.log("Autoplay blocked:", err));
        if (playIcon) playIcon.classList.replace("fa-play", "fa-pause");
    }
}

/* ================== HELPER: SAVE STATE ================== */
function savePlayerState(isPlaying = false) {
    const currentSong = songList[currentIndex];
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

/* ================== LYRICS ================== */
function loadLyrics(songId) {
    if (!lyricsBox) return;
    lyricsBox.innerHTML = "Đang tải lời bài hát...";
    fetch(`my_music.php?ajax_lyrics=1&song_id=${songId}`)
        .then(r => r.json())
        .then(d => {
            lyricsBox.innerHTML = d.status === "success" 
                ? d.lyrics.replace(/\n/g, "<br>") 
                : "Chưa có lời bài hát";
        })
        .catch(() => lyricsBox.innerHTML = "Không thể tải lời bài hát");
}

/* ================== FORMAT TIME ================== */
function formatTime(sec) {
    if (isNaN(sec)) return "0:00";
    const m = Math.floor(sec / 60);
    const s = Math.floor(sec % 60).toString().padStart(2, "0");
    return `${m}:${s}`;
}

/* ================== CONTROLS: PLAY/PAUSE ================== */
if (playBtn) {
    playBtn.onclick = () => {
        if (audio.paused) {
            audio.play();
            playIcon.classList.replace("fa-play", "fa-pause");
            savePlayerState(true);
        } else {
            audio.pause();
            playIcon.classList.replace("fa-pause", "fa-play");
            savePlayerState(false);
        }
    };
}
// Đồng bộ icon khi audio tự play/pause (ví dụ do hết bài)
audio.onplay = () => playIcon.classList.replace("fa-play", "fa-pause");
audio.onpause = () => playIcon.classList.replace("fa-pause", "fa-play");

/* ================== CONTROLS: PROGRESS ================== */
audio.ontimeupdate = () => {
    if (!audio.duration) return;

    // Update thanh chạy
    if (progressBar) progressBar.style.width = (audio.currentTime / audio.duration * 100) + "%";
    if (currentTimeText) currentTimeText.textContent = formatTime(audio.currentTime);
    if (durationText) durationText.textContent = formatTime(audio.duration);

    // ===== LOGIC LỊCH SỬ & LƯỢT NGHE =====
    listenSeconds = Math.floor(audio.currentTime);
    const currentSong = songList[currentIndex];
    
    if (currentSong) {
        // 10s: Lưu lịch sử
        if (listenSeconds >= 10 && !loggedHistory) {
            loggedHistory = true;
            savePlayerState(audio.paused ? false : true); // Lưu lại biến loggedHistory vào storage
            fetch("ajax_interaction.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `action=log_history&song_id=${currentSong.id}`
            });
        }
        // 30s: Tăng view
        if (listenSeconds >= 30 && !increasedPlay) {
            increasedPlay = true;
            savePlayerState(audio.paused ? false : true);
            fetch("ajax_interaction.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `action=increase_play&song_id=${currentSong.id}`
            });
        }
    }
    
    // Lưu vị trí thời gian liên tục (để F5 không mất)
    if(listenSeconds % 5 === 0) savePlayerState(!audio.paused); 
};

if (progress) {
    progress.onclick = e => {
        audio.currentTime = (e.offsetX / progress.clientWidth) * audio.duration;
    };
}

/* ================== CONTROLS: NEXT/PREV/SHUFFLE ================== */
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

audio.onended = () => {
    if (isRepeat) {
        audio.currentTime = 0;
        audio.play();
    } else {
        changeSong(1);
    }
};

/* ================== 1. LOGIC LIKE (TIM) ================== */
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
    if (!songId) return;
    // Reset trước khi check
    updateLikeIcon(false);

    fetch("ajax_interaction.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `action=check_like&song_id=${songId}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            updateLikeIcon(data.liked);
        }
    })
    .catch(err => console.error("Lỗi check like:", err));
}

// Sự kiện click nút tim
if (likeBtn) {
    likeBtn.onclick = () => {
        const currentSong = songList[currentIndex];
        if (!currentSong) return;

        // Optimistic UI: Đổi màu luôn cho mượt
        const isCurrentlyLiked = likeIcon.classList.contains("fas");
        updateLikeIcon(!isCurrentlyLiked);

        fetch("ajax_interaction.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `action=toggle_like&song_id=${currentSong.id}`
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                const liked = (data.action === 'liked');
                updateLikeIcon(liked);
            } else {
                // Lỗi thì revert lại
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

/* ================== 2. LOGIC VOLUME (ÂM LƯỢNG) ================== */
// Xử lý thanh trượt volume
if (volumeSlider) {
    volumeSlider.onclick = (e) => {
        const sliderWidth = volumeSlider.clientWidth;
        const clickX = e.offsetX;
        let percent = clickX / sliderWidth;
        
        // Giới hạn 0 - 1
        if (percent < 0) percent = 0;
        if (percent > 1) percent = 1;

        audio.volume = percent;
    };
}

// Tự động cập nhật UI khi âm lượng thay đổi
audio.onvolumechange = () => {
    // 1. Chỉnh độ dài thanh màu
    if (volumeFill) {
        volumeFill.style.width = (audio.volume * 100) + "%";
    }

    // 2. Đổi icon loa
    if (volumeIcon) {
        volumeIcon.className = "fas"; // Reset class base
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

/* ================== INIT & RESTORE ================== */
function buildSongList() {
    songList = [];
    document.querySelectorAll(".song-item-row").forEach((row, index) => {
        const song = {
            id: row.dataset.id,
            title: row.dataset.title,
            artist: row.dataset.artist,
            src: row.dataset.src,
            cover: row.dataset.cover
        };
        songList.push(song);
        row.addEventListener("click", () => {
            currentIndex = index;
            loadSong(song);
        });
    });
}

function updateRowHighlight(songId) {
    document.querySelectorAll(".song-item-row").forEach(r => r.classList.remove("active-playing"));
    const row = document.querySelector(`.song-item-row[data-id="${songId}"]`);
    if (row) row.classList.add("active-playing");
}

document.addEventListener("DOMContentLoaded", () => {
    buildSongList();

    // Khôi phục Volume
    const savedVol = localStorage.getItem("playerVolume");
    if (savedVol !== null) audio.volume = parseFloat(savedVol);

    // Khôi phục Bài hát
    const saved = JSON.parse(localStorage.getItem("currentSong"));
    if (!saved || !saved.src) return;

    currentIndex = songList.findIndex(s => s.id == saved.id);
    if (currentIndex === -1) currentIndex = 0;

    audio.src = saved.src;
    audio.currentTime = saved.currentTime || 0;

    // Khôi phục biến đếm
    loggedHistory = saved.loggedHistory || false;
    increasedPlay = saved.increasedPlay || false;

    // Hiển thị thông tin (dùng cơ chế fallback ảnh bìa)
    const defaultImage = "https://via.placeholder.com/150"; 
    const coverImage = (saved.cover && saved.cover.trim() !== "") ? saved.cover : defaultImage;

    if (nowTitle) nowTitle.textContent = saved.title;
    if (nowArtist) nowArtist.textContent = saved.artist;
    if (nowCover) nowCover.src = coverImage;
    if (rightCover) rightCover.src = coverImage;
    if (rightTitle) rightTitle.textContent = saved.title;
    if (rightArtist) rightArtist.textContent = saved.artist;

    // Hiện Player bar
    const playerBar = document.querySelector('.player-bar');
    if (playerBar) playerBar.style.display = 'flex';

    updateRowHighlight(saved.id);
    if (saved.id) {
        loadLyrics(saved.id);
        checkLikeStatus(saved.id); // <-- QUAN TRỌNG: Check lại tim khi F5
    }

    if (saved.isPlaying) {
        const playPromise = audio.play();
        if (playPromise !== undefined) {
            playPromise.catch(error => console.log("Auto-play prevented"));
        }
    }
});