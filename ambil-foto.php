<?php 
// Menyisipkan file header yang berisi meta tag, link CSS, dan bagian atas navigasi
include 'includes/header.php'; 

// Menangkap data nama tamu dari form sebelumnya (index.php) menggunakan metode POST. 
$nama_tamu = $_POST['nama_tamu'] ?? 'Sweet Guest';

// Menangkap pilihan filter dari form sebelumnya.
$filter_pilihan = $_POST['filter'] ?? 'soft';

// Inisialisasi variabel target proses
$target_proses = "proses_pink.php"; 

if ($filter_pilihan == 'vintage') {
    $target_proses = "proses_biru.php";
} elseif ($filter_pilihan == 'mahogany') { 
    $target_proses = "proses_mahogany.php";
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<style>
    /* 1. PERBAIKAN CARD UTAMA (KOTAK PUTIH): Mengunci lebar agar pas dengan kamera */
    .main-card {
        /* KUNCI: fit-content membuat kotak putih selalu memeluk kamera di dalamnya */
        width: fit-content; 
        min-width: 300px;
        max-width: 95vw; 
        margin: auto;
        border-radius: 30px; 
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border: none;
        /* Padding: Jarak antara konten (kamera) dengan tepi kotak putih */
        padding: 3vh 3vw !important; 
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* 2. PERBAIKAN VIDEO WRAPPER: Menggunakan VH agar tinggi kotak konsisten */
    .video-wrapper { 
        position: relative; 
        /* Menggunakan VH agar tidak memotong badan saat di-zoom out atau layar kecil */
        height: 50vh; 
        aspect-ratio: 16 / 9; 
        margin: 1.5vh auto; 
        border-radius: 20px; 
        overflow: hidden; 
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1); 
        background: #000;
        /* Kotak Putih Kecil di pinggir video */
        border: 0.8vh solid #fff; 
    }

    #video { 
        width: 100%; 
        height: 100%; 
        object-fit: cover; 
        transform: scaleX(-1); 
    }

    /* 3. BINGKAI FILTER */
    .frame-overlay { 
        position: absolute; 
        top: 0; left: 0; right: 0; bottom: 0; 
        pointer-events: none; 
        z-index: 10; 
        border: 2.2vh solid; 
        transition: all 0.5s ease; 
    }

    /* Warna bingkai dinamis */
    <?php if($filter_pilihan == 'soft'): ?>
        .frame-overlay { border-color: rgba(255, 192, 203, 0.6); }
    <?php elseif($filter_pilihan == 'vintage'): ?>
        .frame-overlay { border-color: rgba(173, 216, 230, 0.6); }
    <?php elseif($filter_pilihan == 'mahogany'): ?>
        .frame-overlay { border-color: rgba(78, 42, 30, 0.6); }
    <?php endif; ?>

    /* 4. PREVIEW BOX: Proporsional terhadap VH */
    .preview-container {
        display: flex;
        justify-content: center;
        gap: 1vh;
        margin-bottom: 2vh;
        width: 100%;
    }

    .preview-box { 
        height: 8vh; 
        aspect-ratio: 4 / 3;
        background: #fdfcfb; 
        border-radius: 12px; 
        border: 2px dashed #db7093; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        color: #db7093; 
    }

    /* 5. TYPOGRAPHY */
    .brand-title {
        font-family: 'Playball', cursive;
        color: #db7093;
        font-size: clamp(1.5rem, 6vh, 3.5rem); 
    }

    /* 6. TOMBOL CAPTURE */
    .btn-capture { 
        width: 100%;
        max-width: 300px;
        background: linear-gradient(45deg, #db7093, #ffb6c1); 
        color: white; 
        border: none; 
        font-weight: bold; 
        padding: 1.5vh 2rem; 
        border-radius: 50px;
        font-size: clamp(0.8rem, 2vh, 1.2rem);
        transition: all 0.3s ease;
    }

    /* Media Query khusus laptop layar pendek */
    @media (max-height: 550px) {
        .video-wrapper { height: 40vh; }
        .brand-title { font-size: 1.5rem; }
    }
</style>

<div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 100vh; padding: 20px 0;">
    <div class="card main-card shadow-lg animate__animated animate__fadeIn">
        
        <div class="text-center w-100">
            <h1 class="brand-title mb-0">PinkyPromise</h1>
            <p class="text-muted text-uppercase small fw-bold mb-2" style="letter-spacing: 2px;">Professional Digital Photobooth</p>

            <div class="mb-2">
                <h5 class="fw-bold" style="color: #4E2A1E;">Ready to Shine, <span style="color: #db7093;"><?= htmlspecialchars($nama_tamu) ?></span>? ✨</h5>
            </div>
            
            <div class="video-wrapper">
                <div class="frame-overlay"></div> 
                <video id="video" autoplay playsinline></video> 
                <canvas id="canvas" style="display:none;"></canvas> 
            </div>

            <div class="preview-container">
                <div id="p1" class="preview-box shadow-sm"><i class="fas fa-smile"></i></div>
                <div id="p2" class="preview-box shadow-sm"><i class="fas fa-heart"></i></div>
                <div id="p3" class="preview-box shadow-sm"><i class="fas fa-star"></i></div>
                <div id="p4" class="preview-box shadow-sm"><i class="fas fa-camera"></i></div>
            </div>

            <form id="photo-form" action="<?= $target_proses ?>" method="POST">
                <input type="hidden" name="image_data" id="image_data">
                <input type="hidden" id="filter_used" name="filter_used" value="<?= $filter_pilihan ?>">
                
                <p id="status-text" class="fw-bold mb-2 animate__animated animate__pulse animate__infinite" style="color: #db7093; display:none;">Siap-siap...</p>

                <button type="button" id="snap" class="btn btn-capture shadow">
                    AMBIL FOTO SEKARANG <i class="fas fa-magic ms-2"></i>
                </button>
            </form>
        </div>

    </div>
</div>

<script src="assets/js/kamera.js?v=<?= time() ?>"></script>

<?php 
include 'includes/footer.php'; 
?>