/**
 * PinkyPromise Photobooth - Final Photo Engine 
 * Script ini mengelola seluruh alur pengambilan foto, manipulasi kanvas, 
 * hingga pengiriman data ke server.
 */

// Mengambil referensi elemen-elemen HTML yang dibutuhkan
const video = document.getElementById('video'); // Elemen untuk menampilkan stream kamera
const canvas = document.getElementById('canvas'); // Kanvas tersembunyi untuk menangkap frame foto
const snap = document.getElementById('snap'); // Tombol untuk memulai sesi foto
const photoForm = document.getElementById('photo-form'); // Form untuk mengirim data ke PHP
const imageDataInput = document.getElementById('image_data'); // Input hidden untuk menampung Base64 gambar
const statusText = document.getElementById('status-text'); // Label teks untuk panduan tamu

let photos = []; // Array untuk menyimpan hasil jepretan (4 foto) sementara di memori browser

// -----------------------------------------------------------
// 1. AKSES PERANGKAT KERAS (KAMERA)
// -----------------------------------------------------------
// Menggunakan API navigator.mediaDevices untuk meminta izin akses kamera pengguna.
navigator.mediaDevices.getUserMedia({ video: true, audio: false })
    .then(stream => { 
        // Jika diizinkan, masukkan aliran video ke elemen <video>
        video.srcObject = stream; 
    })
    .catch(err => { 
        // Jika gagal (karena diblokir atau tidak ada kamera), tampilkan peringatan
        alert("Ups! Kamera tidak bisa diakses."); 
    });

// -----------------------------------------------------------
// 2. SESI PENGAMBILAN FOTO (AUTOMATED PHOTO SESSION)
// -----------------------------------------------------------
// Event listener saat tombol 'Mulai Foto' diklik.
snap.addEventListener('click', async () => {
    // Mengecek filter apa yang dipilih tamu untuk menentukan warna tema nantinya
    const filterInput = document.getElementById('filter_used');
    const filterUsed = filterInput ? filterInput.value.trim() : 'soft';
    
    // Menonaktifkan tombol agar tidak diklik dua kali saat proses berjalan
    snap.disabled = true; 
    snap.innerHTML = 'PROSES... <i class="fas fa-spinner fa-spin ms-2"></i>';
    
    photos = []; // Mengosongkan array foto sebelum memulai sesi baru
    
    // Perulangan untuk mengambil 4 foto secara otomatis
    for (let i = 1; i <= 4; i++) {
        statusText.style.display = "block"; // Menampilkan teks instruksi
        statusText.innerText = `Foto ke-${i} dalam hitungan mundur...`;
        
        // Jeda waktu 2 detik antar sesi hitung mundur
        await new Promise(r => setTimeout(r, 2000));
        statusText.innerText = `3... 2... 1... POSE! 📸`;
        
        // Menyesuaikan ukuran kanvas dengan resolusi asli video kamera
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        
        // --- LOGIKA MIRRORING (PENCERMINAN) ---
        // Karena kamera depan web biasanya terbalik, kita balik secara horizontal
        context.save(); // Simpan status kanvas asli
        context.scale(-1, 1); // Balik sumbu X (kiri jadi kanan)
        // Gambar frame dari video ke kanvas (posisi X ditarik ke negatif agar muncul kembali)
        context.drawImage(video, -canvas.width, 0, canvas.width, canvas.height);
        context.restore(); // Kembalikan status kanvas agar tidak mempengaruhi gambar selanjutnya
        
        // Mengonversi hasil kanvas menjadi format string Base64 (PNG)
        const dataURL = canvas.toDataURL('image/png');
        photos.push(dataURL); // Masukkan ke dalam array penampung
        
        // Menampilkan preview instan pada kotak kecil (p1, p2, p3, p4) di UI
        const pBox = document.getElementById(`p${i}`);
        if(pBox) pBox.innerHTML = `<img src="${dataURL}" width="100%" class="rounded animate__animated animate__zoomIn">`;
        
        // Jeda 1 detik sebelum lanjut ke foto berikutnya agar tamu bisa melihat previewnya
        await new Promise(r => setTimeout(r, 1000));
    }

    statusText.innerText = "Menyusun memori indahmu... ✨";

    // -----------------------------------------------------------
    // 3. PEMBUATAN PHOTO STRIP (COMPOSITION ENGINE)
    // -----------------------------------------------------------
    // Membuat elemen kanvas baru di memori (tidak tampil di layar) untuk menyusun strip foto
    const stripCanvas = document.createElement('canvas');
    const ctx = stripCanvas.getContext('2d');
    
    const imgW = video.videoWidth; // Lebar foto asli
    const imgH = video.videoHeight; // Tinggi foto asli
    const padding = 40; // Margin luar strip (jarak dari tepi kertas)
    const gap = 30; // Jarak antar foto secara vertikal

    // Menghitung dimensi total strip (Lebar = Foto + Padding kiri-kanan)
    stripCanvas.width = imgW + (padding * 2);
    // Menghitung tinggi total (4 Foto + 3 Jarak antar foto + Padding atas-bawah + Area Branding di bawah)
    stripCanvas.height = (imgH * 4) + (gap * 3) + (padding * 2) + 130;

    // --- LOGIKA PENENTUAN WARNA TEMA (DYNAMIC BRANDING) ---
    // Menyesuaikan warna latar belakang dan teks berdasarkan pilihan filter sebelumnya
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

    // TAHAP 1: MEWARNAI LATAR BELAKANG (BACKGROUND RENDERING)
    // Mengisi seluruh area kanvas dengan warna tema yang sudah ditentukan
    ctx.fillStyle = bgColor; 
    ctx.fillRect(0, 0, stripCanvas.width, stripCanvas.height);

    // TAHAP 2: MENYUSUN FOTO SECARA VERTIKAL (IMAGE COMPOSITION)
    // Menempelkan ke-4 foto hasil jepretan ke dalam satu kanvas panjang
    for (let i = 0; i < photos.length; i++) {
        const img = new Image(); // Objek gambar baru
        img.src = photos[i]; // Memuat data Base64 hasil jepretan
        
        // Menunggu gambar selesai dimuat sebelum ditempel agar tidak korup
        await new Promise(r => img.onload = r);
        
        // Kalkulasi posisi Y: Menghitung di koordinat vertikal mana foto ditempel
        const posY = padding + (i * (imgH + gap));
        
        // Memberikan border putih tipis di sekeliling foto agar terlihat rapi
        ctx.strokeStyle = "white";
        ctx.lineWidth = 2;
        ctx.strokeRect(padding, posY, imgW, imgH);
        
        // Menggambar foto ke dalam strip kanvas sesuai posisi yang sudah dihitung
        ctx.drawImage(img, padding, posY, imgW, imgH);
    }

    // TAHAP 3: BRANDING & FINALISASI (WATERMARKING)
    // Menambahkan teks identitas di bagian paling bawah strip foto
    ctx.fillStyle = textColor;
    ctx.font = "bold 38px Arial"; 
    ctx.textAlign = "center";
    // Menaruh teks di tengah-tengah lebar kanvas
    ctx.fillText(brandText, stripCanvas.width / 2, stripCanvas.height - 55);

    // MENGIRIM DATA KE SERVER
    // Mengubah hasil akhir komposisi kanvas menjadi satu string Base64 yang panjang
    imageDataInput.value = stripCanvas.toDataURL('image/png');
    
    // Secara otomatis mengirimkan form ke file PHP pemroses (proses_pink.php dan lainnya)
    photoForm.submit();
});