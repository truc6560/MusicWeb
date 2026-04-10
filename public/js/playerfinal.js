//AUDIO
const audio = document.getElementById("audioPlayer");
if (!audio) {
    console.error("Không tìm thấy audioPlayer");
}
const isLoggedIn = document.body?.dataset?.auth === "1";

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
let isSwitchingSong = false;
let queuedSongIndex = null;

// Biến theo dõi thời gian nghe 
let listenSeconds = 0;
let loggedHistory = false;
let increasedPlay = false; 

//LOAD BÀI HÁT
function loadSong(song, autoPlay = true) {
    if (!song) return;

    if (!song.src && song.id) {
        song.src = `/song/${song.id}/stream`;
    }
    if (!song.src) return;

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

    // Panel phải
    if (rightCover) rightCover.src = coverImage;
    if (rightTitle) rightTitle.textContent = song.title;
    if (rightArtist) rightArtist.textContent = song.artist;


    // Load lyrics
    loadLyrics(song.id);
    // Highlight bài đang phát
    updateRowHighlight(song.id);

    // Kiểm tra trạng thái like
    checkLikeStatus(song.id);

    // Reset biến đếm
    listenSeconds = 0;
    loggedHistory = false;
    increasedPlay = false;

    // Lưu trạng thái
    savePlayerState(autoPlay);

    // Tự động phát
    if (autoPlay) {
        const playPromise = audio.play();
        if (playPromise !== undefined) {
            playPromise.catch(err => {
                console.log("Autoplay blocked:", err);
                // Nếu bị chặn, giữ icon play
                if (playIcon) playIcon.classList.replace("fa-pause", "fa-play");
            });
        }
        if (playIcon) playIcon.classList.replace("fa-play", "fa-pause");
    }
}

//HÀM PHỤ: LƯU TRẠNG THÁI PLAYER
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

//LẤY LỜI BÀI HÁT
function loadLyrics(songId) {
    if (!lyricsBox) return;
    lyricsBox.innerHTML = "Đang tải lời bài hát...";
    fetch(`/song/${songId}/laylyrics`)
        .then(r => r.json())
        .then(d => {
            lyricsBox.innerHTML = d.status === "success" 
                ? d.lyrics.replace(/\n/g, "<br>") 
                : "Chưa có lời bài hát";
        })
        .catch(() => lyricsBox.innerHTML = "Không thể tải lời bài hát");
}

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
            savePlayerState(true);
        } else {
            audio.pause();
            playIcon.classList.replace("fa-pause", "fa-play");
            savePlayerState(false);
        }
    };
}
// Đồng bộ icon khi audio tự play/pause
audio.onplay = () => playIcon.classList.replace("fa-play", "fa-pause");
audio.onpause = () => playIcon.classList.replace("fa-pause", "fa-play");

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
    const currentSong = songList[currentIndex];
    
    if (currentSong) {
        // Sau 10s → lưu lịch sử nghe
        if (isLoggedIn && listenSeconds >= 10 && !loggedHistory) {
            loggedHistory = true;
            savePlayerState(audio.paused ? false : true); // Lưu lại biến loggedHistory vào storage
            fetch("/ajax/record-history", {
                method: "POST",
                headers: { 
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content 
                },
                body: `song_id=${currentSong.id}`
            });
        }
        // Sau 30s → tăng lượt nghe
        if (isLoggedIn && listenSeconds >= 30 && !increasedPlay) {
            increasedPlay = true;
            savePlayerState(audio.paused ? false : true);

            fetch("/ajax/increment-view", {
                method: "POST",
                headers: { 
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content 
                },
                body: `song_id=${currentSong.id}`
            });
        }
    }
    
    // Lưu trạng thái mỗi 5s (để reload không mất)
    let lastSavedSecond = -1;
    if (listenSeconds % 5 === 0 && listenSeconds !== lastSavedSecond) {
        lastSavedSecond = listenSeconds;
        savePlayerState(!audio.paused);
    }
};

// CLICK VÀO THANH PROGRESS ĐỂ TUA
if (progress) {
    progress.onclick = e => {
        if (!audio.duration || isNaN(audio.duration)) return;

        const rect = progress.getBoundingClientRect();
        const clickX = e.clientX - rect.left;
        let ratio = clickX / rect.width;
        if (ratio < 0) ratio = 0;
        if (ratio > 1) ratio = 1;

        audio.currentTime = ratio * audio.duration;

        if (progressBar) {
            progressBar.style.width = (ratio * 100) + "%";
        }
        if (currentTimeText) {
            currentTimeText.textContent = formatTime(audio.currentTime);
        }
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
        // Bấm replay: phát lại ngay từ đầu bài hiện tại và bật chế độ lặp.
        if (!isRepeat) {
            isRepeat = true;
            repeatBtn.classList.add("active");
            if (audio.src) {
                audio.currentTime = 0;
                const p = audio.play();
                if (p !== undefined) {
                    p.catch(err => console.log("Replay blocked:", err));
                }
            }
            return;
        }

        // Bấm lần nữa để tắt repeat.
        isRepeat = false;
        repeatBtn.classList.remove("active");
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
    playSongAtIndex(currentIndex);
}

async function fetchSongAndPlay(songId, index) {
    try {
        const response = await fetch(`/song/${songId}`);
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const data = await response.json();
        if (data && data.audio_url) {
            songList[index].src = data.audio_url;
            songList[index].title = data.title || songList[index].title;
            songList[index].artist = data.artist || songList[index].artist;
            songList[index].cover = data.cover || songList[index].cover;
            loadSong(songList[index]);
        } else {
            console.error("Không lấy được đường dẫn bài hát");
        }
    } catch (err) {
        console.error("Lỗi fetch song:", err);
    }
}

function playSongAtIndex(index) {
    if (index < 0 || index >= songList.length) return;
    if (isSwitchingSong) {
        queuedSongIndex = index;
        return;
    }

    currentIndex = index;
    const song = songList[currentIndex];

    if (song && !song.src && song.id) {
        song.src = `/song/${song.id}/stream`;
    }

    if (song && song.src) {
        loadSong(song);
        return;
    }

    isSwitchingSong = true;
    queuedSongIndex = null;
    fetchSongAndPlay(song.id, currentIndex).finally(() => {
        isSwitchingSong = false;

        // Nếu người dùng đã bấm tiếp trong lúc đang fetch, phát yêu cầu mới nhất.
        if (queuedSongIndex !== null && queuedSongIndex !== currentIndex) {
            const nextIndex = queuedSongIndex;
            queuedSongIndex = null;
            playSongAtIndex(nextIndex);
        }
    });
}

// KHI BÀI HÁT KẾT THÚC
audio.onended = () => {
    if (isRepeat) {
        audio.currentTime = 0;
        audio.play();
    } else {
        changeSong(1);
    }
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

function checkLikeStatus(songId) {
    if (!songId || !likeIcon || !isLoggedIn) return;
    updateLikeIcon(false);
    try {
        fetch(`/ajax/like-song/status?song_id=${encodeURIComponent(songId)}`)
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                updateLikeIcon(data.liked === true || data.action === 'liked');
            }
        })
        .catch(err => console.error("Lỗi check like:", err));
    } catch (err) {
        console.error("Lỗi khởi tạo request like:", err);
    }
}

// Click nút tim
if (likeBtn && likeIcon) {
    likeBtn.onclick = () => {
        if (!isLoggedIn) {
            alert("Bạn cần đăng nhập để thêm bài hát vào yêu thích.");
            return;
        }
        const currentSong = songList[currentIndex];
        if (!currentSong) return;

        // UI mượt: đổi ngay lập tức (optimistic UI)
        const isCurrentlyLiked = likeIcon.classList.contains("fas");
        updateLikeIcon(!isCurrentlyLiked);

        fetch("/ajax/like-song", {
            method: "POST",
            headers: { 
                "Content-Type": "application/x-www-form-urlencoded",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content 
            },
            body: `song_id=${currentSong.id}`
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
function buildSongList() {
    songList = [];
    // Hỗ trợ cả .song-item-row và .chart-row
    const rows = document.querySelectorAll(".song-item-row, .chart-row");
    rows.forEach(row => {
        let songId = row.dataset.id;
        if (!songId) {
            const playBtn = row.querySelector(".play-btn");
            if (playBtn) songId = playBtn.getAttribute("data-id");
        }
        if (!songId) return;

        let title = row.dataset.title || "";
        let artist = row.dataset.artist || "";
        let cover = row.dataset.cover || "";
        let src = row.dataset.src || `/song/${songId}/stream`;

        // Fallback: lấy từ nội dung text nếu thiếu data attribute
        if (!title) {
            const titleDiv = row.querySelector("div[style*='font-weight: bold']");
            if (titleDiv) title = titleDiv.innerText;
        }
        if (!artist) {
            const artistDiv = row.querySelector("div[style*='color: #888']");
            if (artistDiv) artist = artistDiv.innerText;
        }
        if (!cover) {
            const img = row.querySelector("img");
            if (img) cover = img.src;
        }

        songList.push({
            id: songId,
            title: title || "Không rõ",
            artist: artist || "Unknown",
            src: src,
            cover: cover || "https://via.placeholder.com/150"
        });
    });

    // Event delegation cho click vào row (không click vào nút play)
    document.addEventListener("click", (e) => {
        const row = e.target.closest(".song-item-row, .chart-row");
        if (!row) return;
        if (e.target.closest(".play-btn")) return;
        const songId = row.dataset.id || row.querySelector(".play-btn")?.getAttribute("data-id");
        if (!songId) return;
        const index = songList.findIndex(s => s.id == songId);
        if (index !== -1) {
            playSongAtIndex(index);
        }
    });

    // Gắn sự kiện riêng cho nút play
    document.querySelectorAll(".play-btn").forEach(btn => {
        if (btn.hasAttribute("data-listener")) return;
        btn.setAttribute("data-listener", "true");
        btn.addEventListener("click", (e) => {
            e.stopPropagation();
            let songId = btn.getAttribute("data-id");
            if (!songId) return;
            const index = songList.findIndex(s => s.id == songId);
            if (index !== -1) {
                playSongAtIndex(index);
            }
        });
    });
}

// Highlight bài đang phát
function updateRowHighlight(songId) {
    document.querySelectorAll(".song-item-row, .chart-row").forEach(r => r.classList.remove("active-playing"));
    const row = document.querySelector(`.song-item-row[data-id="${songId}"], .chart-row[data-id="${songId}"]`);
    if (row) row.classList.add("active-playing");
}

document.addEventListener("DOMContentLoaded", () => {
    buildSongList();

    // Khôi phục Volume
    const savedVol = localStorage.getItem("playerVolume");
    if (savedVol !== null) audio.volume = parseFloat(savedVol);

    // Khôi phục Bài hát đang phát
    const saved = JSON.parse(localStorage.getItem("currentSong"));
    if (!saved || !saved.src) return;

    // Nếu songList rỗng thì không thể khôi phục
    if (songList.length === 0) return;

    currentIndex = songList.findIndex(s => s.id == saved.id);
    if (currentIndex === -1) currentIndex = 0;

    audio.src = saved.src;
    audio.currentTime = saved.currentTime || 0;

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

    // Tự play nếu trước đó đang phát
    if (saved.isPlaying) {
        const playPromise = audio.play();
        if (playPromise !== undefined) {
            playPromise.catch(error => console.log("Trình duyệt chặn autoplay"));
        }
    }
});