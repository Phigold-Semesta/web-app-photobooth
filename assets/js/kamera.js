/**
 * PinkyPromise Photobooth - Final Photo Engine 
 * Fixed: Brand Consistency & Theme Recognition
 */

const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const snap = document.getElementById('snap');
const photoForm = document.getElementById('photo-form');
const imageDataInput = document.getElementById('image_data');
const statusText = document.getElementById('status-text');

let photos = []; 

// 1. Akses Kamera
navigator.mediaDevices.getUserMedia({ video: true, audio: false })
    .then(stream => { video.srcObject = stream; })
    .catch(err => { 
        console.error(err);
        alert("Kamera tidak bisa diakses!"); 
    });

// 2. Fungsi Utama Sesi Foto
snap.addEventListener('click', async () => {
    // Ambil filter saat tombol diklik agar akurat
    const filterInput = document.getElementById('filter_used');
    const filterUsed = filterInput ? filterInput.value.trim() : 'soft';
    
    snap.disabled = true; 
    photos = []; 
    
    // Looping Pengambilan Foto
    for (let i = 1; i <= 4; i++) {
        statusText.innerText = `Siap-siap... Foto ke-${i}`;
        await new Promise(r => setTimeout(r, 2000));
        statusText.innerText = `3... 2... 1... CEKREK! 📸`;
        
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        
        // Mirroring Fix (Agar hasil tidak terbalik)
        context.save();
        context.scale(-1, 1);
        context.drawImage(video, -canvas.width, 0, canvas.width, canvas.height);
        context.restore();
        
        const dataURL = canvas.toDataURL('image/png');
        photos.push(dataURL);
        
        // Preview di kotak kecil
        const pBox = document.getElementById(`p${i}`);
        if(pBox) pBox.innerHTML = `<img src="${dataURL}" width="100%" class="rounded">`;
        
        await new Promise(r => setTimeout(r, 1000));
    }

    statusText.innerText = "Menyatukan momen indahmu... ✨";

    // 3. Proses Pembuatan Strip (Canvas Gabungan)
    const stripCanvas = document.createElement('canvas');
    const ctx = stripCanvas.getContext('2d');
    const imgW = video.videoWidth;
    const imgH = video.videoHeight;
    const padding = 40; 
    const gap = 25;

    stripCanvas.width = imgW + (padding * 2);
    stripCanvas.height = (imgH * 4) + (gap * 3) + (padding * 2) + 120;

    // --- LOGIKA WARNA & BRANDING ---
    let bgColor, textColor, brandText;

    switch (filterUsed) {
        case 'mahogany':
        case 'bright':
            bgColor = "#4E2A1E";    // Mahogany
            textColor = "#FFFFFF";  // Putih
            brandText = "✨ Mahogany Luxury Strip ✨";
            break;
        case 'vintage':
            bgColor = "#ADD8E6";    // Biru Muda
            textColor = "#4682B4";  // Biru Steel
            brandText = "📸 Blue Vintage Strip 📸";
            break;
        case 'soft':
        default:
            bgColor = "#FFB6C1";    // Pink Rose
            textColor = "#DB7093";  // Pink Tua
            brandText = "💖 PinkyPromise Strip 💖";
            break;
    }

    // Gambar Background Strip
    ctx.fillStyle = bgColor; 
    ctx.fillRect(0, 0, stripCanvas.width, stripCanvas.height);

    // Tempelkan 4 foto
    for (let i = 0; i < photos.length; i++) {
        const img = new Image();
        img.src = photos[i];
        await new Promise(r => img.onload = r);
        
        const posY = padding + (i * (imgH + gap));
        ctx.drawImage(img, padding, posY, imgW, imgH);
    }

    // 4. Tambahkan Label Brand di Area Footer
    ctx.fillStyle = textColor;
    ctx.font = "bold 35px Arial";
    ctx.textAlign = "center";
    ctx.fillText(brandText, stripCanvas.width / 2, stripCanvas.height - 50);

    // 5. Kirim ke PHP
    imageDataInput.value = stripCanvas.toDataURL('image/png');
    photoForm.submit();
});