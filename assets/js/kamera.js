const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const snap = document.getElementById('snap');
const photoForm = document.getElementById('photo-form');
const imageDataInput = document.getElementById('image_data');
const statusText = document.getElementById('status-text');

let photos = []; 

// 1. Izin Kamera
navigator.mediaDevices.getUserMedia({ video: true, audio: false })
    .then(stream => { video.srcObject = stream; })
    .catch(err => { alert("Kamera tidak bisa diakses!"); });

// 2. Fungsi Ambil 4 Foto
snap.addEventListener('click', async () => {
    snap.disabled = true; // Matikan tombol agar tidak double klik
    photos = []; // Reset array
    
    for (let i = 1; i <= 4; i++) {
        // Hitung mundur sederhana
        statusText.innerText = `Siap-siap... Foto ke-${i}`;
        await new Promise(r => setTimeout(r, 2000)); // Jeda 2 detik
        
        statusText.innerText = `3... 2... 1... CEKREK! 📸`;
        
        // Ambil gambar dari video
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        const dataURL = canvas.toDataURL('image/png');
        photos.push(dataURL);
        
        // Tampilkan hasil sementara di kotak preview
        document.getElementById(`p${i}`).innerHTML = `<img src="${dataURL}" width="100%" class="rounded">`;
        await new Promise(r => setTimeout(r, 1000));
    }

    statusText.innerText = "Sedang menyatukan momen manismu... ✨";

    // 3. Gabungkan 4 Foto ke dalam satu Strip Vertikal
    const stripCanvas = document.createElement('canvas');
    const ctx = stripCanvas.getContext('2d');
    
    const imgW = video.videoWidth;
    const imgH = video.videoHeight;
    const padding = 40; 
    const gap = 20;

    stripCanvas.width = imgW + (padding * 2);
    stripCanvas.height = (imgH * 4) + (gap * 3) + (padding * 2) + 120; // Extra space untuk teks bawah

    // Background Kertas Pink
    ctx.fillStyle = "#FFB6C1"; 
    ctx.fillRect(0, 0, stripCanvas.width, stripCanvas.height);

    // Tempelkan 4 foto satu per satu
    for (let i = 0; i < photos.length; i++) {
        const img = new Image();
        img.src = photos[i];
        await new Promise(r => img.onload = r);
        
        const posY = padding + (i * (imgH + gap));
        ctx.drawImage(img, padding, posY, imgW, imgH);
    }

    // Tambahkan Brand di bawah
    ctx.fillStyle = "#DB7093";
    ctx.font = "bold 35px Arial";
    ctx.textAlign = "center";
    ctx.fillText("💖 PinkyPromise Strip 💖", stripCanvas.width / 2, stripCanvas.height - 50);

    // Kirim hasil akhir
    imageDataInput.value = stripCanvas.toDataURL('image/png');
    photoForm.submit();
});