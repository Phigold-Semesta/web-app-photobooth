/**
 * PinkyPromise Photobooth - Final Photo Engine 
 * Experience: Fun, Modern, and Luxurious
 */

const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const snap = document.getElementById('snap');
const photoForm = document.getElementById('photo-form');
const imageDataInput = document.getElementById('image_data');
const statusText = document.getElementById('status-text');

let photos = []; 

// 1. Akses Kamera Pengunjung
navigator.mediaDevices.getUserMedia({ video: true, audio: false })
    .then(stream => { 
        video.srcObject = stream; 
        // Munculkan status setelah kamera aktif
        if(statusText) {
            statusText.style.display = "block";
            statusText.innerText = "Kamera sudah siap! Yuk, ambil posisi terbaikmu ✨";
        }
    })
    .catch(err => { 
        console.error(err);
        alert("Ups! Kamera tidak bisa diakses. Pastikan izin kamera sudah diberikan ya!"); 
    });

// 2. Fungsi Utama Sesi Foto (The Magic Happens Here)
snap.addEventListener('click', async () => {
    // Ambil nuansa filter yang dipilih pengunjung
    const filterInput = document.getElementById('filter_used');
    const filterUsed = filterInput ? filterInput.value.trim() : 'soft';
    
    // Disable tombol agar tidak klik dua kali saat proses
    snap.disabled = true; 
    snap.innerHTML = 'PROSES FOTO... <i class="fas fa-spinner fa-spin ms-2"></i>';
    photos = []; 
    
    // Looping Pengambilan 4 Foto Seru
    for (let i = 1; i <= 4; i++) {
        // Teks instruksi yang lebih asik (Bukan gaya SOWAN)
        statusText.innerText = `Siap-siap ya... Foto ke-${i} dalam hitungan mundur!`;
        statusText.classList.add('animate__pulse');
        
        await new Promise(r => setTimeout(r, 2000));
        statusText.innerText = `3... 2... 1... POSE! 📸`;
        
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        
        // Mirroring Fix (Agar pengunjung merasa seperti bercermin)
        context.save();
        context.scale(-1, 1);
        context.drawImage(video, -canvas.width, 0, canvas.width, canvas.height);
        context.restore();
        
        const dataURL = canvas.toDataURL('image/png');
        photos.push(dataURL);
        
        // Update Preview di Slot Kecil
        const pBox = document.getElementById(`p${i}`);
        if(pBox) {
            pBox.innerHTML = `<img src="${dataURL}" width="100%" class="rounded animate__animated animate__zoomIn">`;
            pBox.style.border = "3px solid #db7093";
        }
        
        await new Promise(r => setTimeout(r, 1000));
    }

    statusText.innerText = "Momen indahmu sedang disatukan... Tunggu sebentar ya! ✨";

    // 3. Proses Pembuatan Photo Strip (Canvas Gabungan Mewah)
    const stripCanvas = document.createElement('canvas');
    const ctx = stripCanvas.getContext('2d');
    const imgW = video.videoWidth;
    const imgH = video.videoHeight;
    const padding = 40; 
    const gap = 25;

    // Menghitung tinggi canvas agar muat 4 foto + area branding di bawah
    stripCanvas.width = imgW + (padding * 2);
    stripCanvas.height = (imgH * 4) + (gap * 3) + (padding * 2) + 120;

    // --- LOGIKA WARNA & BRANDING PHOTOBOOTH ---
    let bgColor, textColor, brandText;

    switch (filterUsed) {
        case 'mahogany':
            bgColor = "#4E2A1E";    // Cokelat Mahogany Mewah
            textColor = "#FFFFFF";  // Putih Bersih
            brandText = "✨ PinkyPromise Mahogany Strip ✨";
            break;
        case 'vintage':
            bgColor = "#ADD8E6";    // Blue Vintage Adem
            textColor = "#4682B4";  // Blue Steel
            brandText = "📸 PinkyPromise Vintage Blue 📸";
            break;
        case 'soft':
        default:
            bgColor = "#FFB6C1";    // Pink Rose Khas
            textColor = "#DB7093";  // Pink Tua Mewah
            brandText = "💖 PinkyPromise Sweet Memories 💖";
            break;
    }

    // Gambar Background Strip sesuai tema pilihan pengunjung
    ctx.fillStyle = bgColor; 
    ctx.fillRect(0, 0, stripCanvas.width, stripCanvas.height);

    // Susun 4 foto secara vertikal
    for (let i = 0; i < photos.length; i++) {
        const img = new Image();
        img.src = photos[i];
        await new Promise(r => img.onload = r);
        
        const posY = padding + (i * (imgH + gap));
        ctx.drawImage(img, padding, posY, imgW, imgH);
    }

    // 4. Tambahkan Identitas PinkyPromise di Footer Strip
    ctx.fillStyle = textColor;
    ctx.font = "bold 38px Arial"; // Ukuran font sedikit diperbesar agar mewah
    ctx.textAlign = "center";
    ctx.fillText(brandText, stripCanvas.width / 2, stripCanvas.height - 50);

    // 5. Finalize: Kirim Data ke Server
    imageDataInput.value = stripCanvas.toDataURL('image/png');
    photoForm.submit();
});