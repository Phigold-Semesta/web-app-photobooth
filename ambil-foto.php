<?php 
// Menyisipkan file header yang berisi meta tag, link CSS, dan bagian atas navigasi
include 'includes/header.php'; 

// Menangkap data nama tamu dari form sebelumnya (index.php) menggunakan metode POST. 
// Jika kosong, maka defaultnya adalah 'Sweet Guest'
$nama_tamu = $_POST['nama_tamu'] ?? 'Sweet Guest';

// Menangkap pilihan filter dari form sebelumnya. Jika tidak ada, defaultnya adalah 'soft'
$filter_pilihan = $_POST['filter'] ?? 'soft';

// Inisialisasi variabel untuk menentukan ke file PHP mana data foto akan dikirimkan
$target_proses = "proses_pink.php"; 

// Logika pemilihan file target berdasarkan filter yang dipilih pengguna
if ($filter_pilihan == 'vintage') {
    $target_proses = "proses_biru.php"; // Jika filter vintage, dikirim ke proses_biru.php
} elseif ($filter_pilihan == 'mahogany') { 
    $target_proses = "proses_mahogany.php"; // Jika filter mahogany, dikirim ke proses_mahogany.php
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<style>
    /* Mengatur kontainer video agar posisinya relatif, melengkung di sudut, dan memiliki bayangan mewah */
    .video-wrapper { 
        position: relative; 
        width: 100%; 
        max-width: 640px; 
        margin: auto; 
        border-radius: 25px; 
        overflow: hidden; 
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15); 
        background: #000; 
    }

    /* Mengatur tampilan video: lebar penuh dan diputar balik (mirror effect) agar seperti cermin */
    #video { 
        width: 100%; 
        display: block; 
        transform: scaleX(-1); 
    }

    /* Overlay bingkai di atas video: diletakkan secara absolut menutupi video dengan border transparan */
    .frame-overlay { 
        position: absolute; 
        top: 0; left: 0; right: 0; bottom: 0; 
        pointer-events: none; /* Agar tidak menghalangi klik pada elemen di bawahnya */
        z-index: 10; 
        border: 20px solid; 
        transition: all 0.5s ease; /* Transisi halus saat warna border berubah */
    }

    /* Pengaturan warna bingkai dinamis menggunakan PHP berdasarkan filter yang aktif */
    <?php if($filter_pilihan == 'soft'): ?>
        .frame-overlay { border-color: rgba(255, 192, 203, 0.7); } /* Pink lembut transparan */
    <?php elseif($filter_pilihan == 'vintage'): ?>
        .frame-overlay { border-color: rgba(173, 216, 230, 0.7); } /* Biru muda transparan */
    <?php elseif($filter_pilihan == 'mahogany'): ?>
        .frame-overlay { border-color: rgba(78, 42, 30, 0.7); } /* Cokelat mahogany transparan */
    <?php endif; ?>

    /* Mengatur tampilan kotak pratinjau (preview) untuk 4 foto yang akan diambil */
    .preview-box { 
        width: 120px; 
        height: 90px; 
        background: #fdfcfb; 
        border-radius: 15px; 
        overflow: hidden; 
        border: 3px dashed #db7093; /* Garis putus-putus berwarna pink */
        display: flex; 
        align-items: center; 
        justify-content: center; 
        color: #db7093; 
        font-weight: bold; 
        transition: all 0.3s ease; 
    }

    /* Gaya tombol ambil foto: menggunakan gradasi linear dan teks tebal */
    .btn-capture { 
        background: linear-gradient(45deg, #db7093, #ffb6c1); 
        color: white; 
        border: none; 
        font-weight: bold; 
        padding: 15px 40px; 
        font-size: 1.2rem; 
        transition: all 0.3s ease; 
    }

    /* Efek hover tombol: sedikit membesar dan memberikan bayangan bercahaya */
    .btn-capture:hover { 
        transform: scale(1.05); 
        box-shadow: 0 10px 20px rgba(219, 112, 147, 0.3); 
        color: white; 
    }
</style>

<div class="container text-center mt-4 mb-5">
    <div class="card p-4 shadow-lg border-0 animate__animated animate__fadeIn" style="max-width: 850px; margin: auto; border-radius: 40px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(15px);">
        
        <h1 class="fw-bold mb-0" style="color: #db7093; font-family: 'Playball', cursive; font-size: 3rem;">PinkyPromise</h1>
        <p class="text-muted text-uppercase small ls-2 fw-bold mb-3">Professional Digital Photobooth</p>

        <div class="mb-4 animate__animated animate__fadeInDown">
            <h4 class="fw-bold" style="color: #4E2A1E;">Ready to Shine, <span style="color: #db7093;"><?= htmlspecialchars($nama_tamu) ?></span>? ✨</h4>
            <p class="small text-muted">Ambil posisi terbaikmu, kita akan mengambil 4 foto seru!</p>
        </div>
        
        <div class="video-wrapper mb-4">
            <div class="frame-overlay"></div> <video id="video" autoplay playsinline></video> <canvas id="canvas" style="display:none;"></canvas> </div>

        <div class="d-flex justify-content-center gap-3 mb-4">
            <div id="p1" class="preview-box shadow-sm"><i class="fas fa-smile"></i></div>
            <div id="p2" class="preview-box shadow-sm"><i class="fas fa-heart"></i></div>
            <div id="p3" class="preview-box shadow-sm"><i class="fas fa-star"></i></div>
            <div id="p4" class="preview-box shadow-sm"><i class="fas fa-camera"></i></div>
        </div>

        <form id="photo-form" action="<?= $target_proses ?>" method="POST">
            <input type="hidden" name="image_data" id="image_data">
            <input type="hidden" id="filter_used" name="filter_used" value="<?= $filter_pilihan ?>">
            
            <p id="status-text" class="fw-bold mb-3 animate__animated animate__pulse animate__infinite" style="color: #db7093; display:none;">Siap-siap...</p>

            <button type="button" id="snap" class="btn btn-capture shadow rounded-pill">
                AMBIL FOTO SEKARANG <i class="fas fa-magic ms-2"></i>
            </button>
        </form>
    </div>
</div>

<script src="assets/js/kamera.js?v=<?= time() ?>"></script>

<?php 
// Menyisipkan file footer untuk menutup tag HTML dan menambahkan script Bootstrap/Footer
include 'includes/footer.php'; 
?>