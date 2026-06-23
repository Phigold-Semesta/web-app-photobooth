<?php 
// Menyisipkan file header yang berisi struktur pembuka HTML, meta tag, dan navigasi atas
include 'includes/koneksi.php'; // Menyertakan koneksi agar bisa memanggil tabel frames secara otomatis
include 'includes/header.php'; 
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<style>
    /* KONFIGURASI BACKGROUND UTAMA: Mengatur area konten agar setinggi layar, 
       menggunakan gradasi warna krem ke pink lembut, dan memosisikan konten tepat di tengah */
    .main-bg {
        min-height: 90vh; /* Tinggi minimal 90% dari tinggi layar browser */
        background: linear-gradient(135deg, #fdfcfb 0%, rgba(255, 255, 255, 0.95) 100%); /* Warna gradasi diagonal */
        display: flex; /* Mengaktifkan mode flexbox untuk perataan */
        align-items: center; /* Memosisikan konten secara vertikal di tengah */
        justify-content: center; /* Memosisikan konten secara horizontal di tengah */
        overflow: hidden; /* Mencegah munculnya scrollbar jika ada animasi yang keluar batas */
    }

    /* GAYA TOMBOL PINK KHAS PINKYPROMISE: Memberikan warna gradasi pink khas brand dan efek transisi halus */
    .btn-pink-mewah {
        background: linear-gradient(45deg, #db7093, #ffb6c1); /* Gradasi pink gelap ke terang */
        color: white; /* Warna teks putih */
        border: none; /* Menghilangkan garis tepi bawaan tombol */
        font-weight: bold; /* Menebalkan teks tombol */
        transition: all 0.3s ease; /* Durasi transisi 0.3 detik untuk semua perubahan gaya */
    }

    /* EFEK HOVER TOMBOL: Memberikan efek kartu naik dan bayangan bercahaya saat tombol disentuh kursor */
    .btn-pink-mewah:hover {
        transform: translateY(-3px); /* Menggeser tombol ke atas sebanyak 3 pixel */
        box-shadow: 0 10px 20px rgba(219, 112, 147, 0.4); /* Memberikan bayangan pink lembut */
        color: white;
    }

    /* PILAR IKON SAMPING: Mengatur tata letak ikon dekoratif yang berada di sisi kiri dan kanan layar */
    .icon-strip-pilar i {
        display: block; /* Membuat ikon berjejer secara vertikal ke bawah */
        font-size: 2rem; /* Ukuran ikon 2x lipat ukuran standar */
        margin: 30px 0; /* Memberikan jarak antar ikon sebesar 30 pixel */
        color: #db7093; /* Warna ikon pink khas PinkyPromise */
        opacity: 0.6; /* Membuat ikon sedikit transparan agar tidak mengganggu fokus utama */
    }

    /* ANIMASI MENGAMBANG (FLOATING): Memberikan efek gerakan naik-turun secara halus dan terus-menerus */
    .floating {
        animation: floating 3s ease-in-out infinite; /* Menjalankan keyframe 'floating' selama 3 detik berulang kali */
    }

    /* LOGIKA KEYFRAME ANIMASI MENGAMBANG */
    @keyframes floating {
        0% { transform: translate(0, 0px); } /* Posisi awal normal */
        50% { transform: translate(0, -15px); } /* Posisi di detik ke-1.5, naik ke atas 15 pixel */
        100% { transform: translate(0, 0px); } /* Kembali ke posisi semula */
    }

    /* KUSTOMISASI INPUT: Memberikan efek bayangan pink yang halus saat kotak input difokuskan/diklik */
    .custom-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(219, 112, 147, 0.25); /* Efek ring bercahaya pink */
        background: #ffffff !important; /* Memastikan latar belakang input tetap putih solid saat diklik */
    }
</style>

<div class="container-fluid main-bg p-0">
    
    <div class="potobooth-container position-relative d-flex align-items-center justify-content-center" style="width: 100%; max-width: 1200px;">

        <div class="icon-strip-pilar strip-left d-none d-xl-flex flex-column position-absolute start-0">
            <i class="fas fa-camera-retro animate__animated animate__fadeInLeft"></i>
            <i class="fas fa-gem animate__animated animate__fadeInLeft animate__delay-1s"></i>
            <i class="fas fa-magic animate__animated animate__fadeInLeft animate__delay-2s"></i>
            <i class="fas fa-palette animate__animated animate__fadeInLeft animate__delay-3s"></i>
        </div>

        <div class="col-md-8 col-lg-6 col-xl-5" style="z-index: 10; position: relative;">
            <div class="card p-4 animate__animated animate__fadeIn floating shadow-lg border-0" style="border-radius: 40px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(15px);">
                <div class="card-body text-center">
                    
                    <h1 class="fw-bold animate__animated animate__pulse animate__infinite animate__slower mb-1" 
                        style="letter-spacing: 1px; font-size: 3rem; color: #db7093; font-family: 'Playball', cursive;">
                        PinkyPromise
                    </h1>
                    <h4 class="fw-light mb-4" style="color: #4E2A1E;">Digital Photobooth 📸</h4>
                    <p class="text-muted mb-4">Abadikan momen serumu dengan sentuhan filter estetik hari ini!</p>
                    
                    <form action="ambil-foto.php" method="GET" class="animate__animated animate__fadeInUp">
                        
                        <div class="mb-4 text-start">
                            <label class="form-label fw-bold small text-secondary">
                                <i class="fas fa-smile me-2" style="color: #db7093;"></i>SIAPA NAMAMU?
                            </label>
                            <input type="text" name="nama_guest" class="form-control form-control-lg shadow-sm border-0 rounded-pill custom-input" 
                                style="background: #f8f9fa; padding-left: 20px;"
                                placeholder="Tulis namamu di sini..." required>
                        </div>
                        
                        <div class="mb-4 text-start">
                            <label class="form-label fw-bold small text-secondary">
                                <i class="fas fa-wand-magic-sparkles me-2" style="color: #db7093;"></i>PILIH NUANSA FAVORIT
                            </label>
                            
                            <select name="tema" class="form-select form-select-lg shadow-sm border-0 rounded-pill custom-input"
                                style="background: #f8f9fa; padding-left: 20px; cursor: pointer;" required>
                                <option value="" disabled selected>-- Pilih Tema Otomatis --</option>
                                <?php
                                // Mengambil data master bingkai/tema langsung dari tabel database 'frames' secara dinamis
                                $query_get_frames = "SELECT * FROM frames ORDER BY id_frame ASC";
                                $result_frames = mysqli_query($koneksi, $query_get_frames);

                                if ($result_frames && mysqli_num_rows($result_frames) > 0) {
                                    while ($row = mysqli_fetch_assoc($result_frames)) {
                                        // Menentukan value parameter string berdasarkan id_frame agar selaras dengan file proses backend
                                        $slug_tema = 'pink';
                                        $emoji = '🌸';
                                        
                                        if ($row['id_frame'] == 2) {
                                            $slug_tema = 'blue';
                                            $emoji = '🧊';
                                        } elseif ($row['id_frame'] == 3) {
                                            $slug_tema = 'mahogany';
                                            $emoji = '🪵';
                                        }

                                        // Menampilkan opsi secara otomatis berdasarkan records baris database master
                                        echo '<option value="' . $slug_tema . '">' . $emoji . ' ' . htmlspecialchars($row['nama_frame']) . '</option>';
                                    }
                                } else {
                                    // Fallback cadangan jika tabel master database dalam keadaan kosong
                                    echo '<option value="pink">🌸 Soft Pink (Romantic)</option>';
                                    echo '<option value="blue">🧊 Blue Vintage (Cool & Calm)</option>';
                                    echo '<option value="mahogany">🪵 Mahogany Luxury (Bold & Classy)</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-pink-mewah btn-lg w-100 shadow-lg rounded-pill py-3">
                            AYO MULAI FOTO <i class="fas fa-bolt-lightning ms-2"></i>
                        </button>
                    </form>

                    <div class="mt-4">
                        <a href="galeri.php" class="text-decoration-none small fw-bold" style="color: #db7093;">
                            <i class="fas fa-heart-pulse me-1"></i> LIHAT KOLEKSI FOTO SERU
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="icon-strip-pilar strip-right d-none d-xl-flex flex-column position-absolute end-0">
            <i class="fas fa-grin-stars animate__animated animate__fadeInRight"></i>
            <i class="fas fa-crown animate__animated animate__fadeInRight animate__delay-1s"></i>
            <i class="fas fa-film animate__animated animate__fadeInRight animate__delay-2s"></i>
            <i class="fas fa-heart animate__animated animate__fadeInRight animate__delay-3s"></i>
        </div>

    </div>
</div>

<?php 
// Menyisipkan file footer untuk menutup tag body dan menyisipkan script JavaScript penutup
include 'includes/footer.php'; 
?>