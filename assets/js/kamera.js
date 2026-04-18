/**
 * PinkyPromise Photobooth - Final Photo Engine 
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
    .catch(err => { alert("Ups! Kamera tidak bisa diakses."); });

// 2. Sesi Foto
snap.addEventListener('click', async () => {
    const filterInput = document.getElementById('filter_used');
    const filterUsed = filterInput ? filterInput.value.trim() : 'soft';
    
    snap.disabled = true; 
    snap.innerHTML = 'PROSES... <i class="fas fa-spinner fa-spin ms-2"></i>';
    photos = []; 
    
    for (let i = 1; i <= 4; i++) {
        statusText.style.display = "block";
        statusText.innerText = `Foto ke-${i} dalam hitungan mundur...`;
        await new Promise(r => setTimeout(r, 2000));
        statusText.innerText = `3... 2... 1... POSE! 📸`;
        
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        
        context.save();
        context.scale(-1, 1);
        context.drawImage(video, -canvas.width, 0, canvas.width, canvas.height);
        context.restore();
        
        const dataURL = canvas.toDataURL('image/png');
        photos.push(dataURL);
        
        const pBox = document.getElementById(`p${i}`);
        if(pBox) pBox.innerHTML = `<img src="${dataURL}" width="100%" class="rounded animate__animated animate__zoomIn">`;
        await new Promise(r => setTimeout(r, 1000));
    }

    statusText.innerText = "Menyusun memori indahmu... ✨";

    // 3. Pembuatan Photo Strip
    const stripCanvas = document.createElement('canvas');
    const ctx = stripCanvas.getContext('2d');
    const imgW = video.videoWidth;
    const imgH = video.videoHeight;
    const padding = 40; 
    const gap = 30;

    stripCanvas.width = imgW + (padding * 2);
    stripCanvas.height = (imgH * 4) + (gap * 3) + (padding * 2) + 130;

    // --- LOGIKA WARNA TEMA ---
    let bgColor, textColor, brandText;
    if (filterUsed === 'mahogany') {
        bgColor = "#4E2A1E"; 
        textColor = "#FFFFFF";
        brandText = "✨ PinkyPromise Mahogany Strip ✨";
    } else if (filterUsed === 'vintage') {
        bgColor = "#ADD8E6"; 
        textColor = "#4682B4";
        brandText = "📸 PinkyPromise Vintage Blue 📸";
    } else {
        bgColor = "#FFB6C1"; 
        textColor = "#DB7093";
        brandText = "💖 PinkyPromise Sweet Pink 💖";
    }

    // TAHAP 1: Cat Background Dulu
    ctx.fillStyle = bgColor; 
    ctx.fillRect(0, 0, stripCanvas.width, stripCanvas.height);

    // TAHAP 2: Tempel Foto
    for (let i = 0; i < photos.length; i++) {
        const img = new Image();
        img.src = photos[i];
        await new Promise(r => img.onload = r);
        const posY = padding + (i * (imgH + gap));
        
        ctx.strokeStyle = "white";
        ctx.lineWidth = 2;
        ctx.strokeRect(padding, posY, imgW, imgH);
        ctx.drawImage(img, padding, posY, imgW, imgH);
    }

    // TAHAP 3: Branding
    ctx.fillStyle = textColor;
    ctx.font = "bold 38px Arial"; 
    ctx.textAlign = "center";
    ctx.fillText(brandText, stripCanvas.width / 2, stripCanvas.height - 55);

    imageDataInput.value = stripCanvas.toDataURL('image/png');
    photoForm.submit();
});