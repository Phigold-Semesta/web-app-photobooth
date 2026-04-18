<?php 
include 'includes/header.php'; 

// Mengambil data dari index.php
$nama_tamu = $_POST['nama_tamu'] ?? 'Sweet Guest';
$filter_pilihan = $_POST['filter'] ?? 'soft';

// LOGIKA PENENTU TARGET PROSES (Sesuai Value di index.php)
$target_proses = "proses_pink.php"; // Default
if ($filter_pilihan == 'vintage') {
    $target_proses = "proses_biru.php";
} elseif ($filter_pilihan == 'mahogany') { // Perbaikan: menggunakan 'mahogany' bukan 'bright'
    $target_proses = "proses_mahogany.php";
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<style>
    .video-wrapper { position: relative; width: 100%; max-width: 640px; margin: auto; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2); }
    #video { width: 100%; display: block; transform: scaleX(-1); }
    .frame-overlay { position: absolute; top: 0; left: 0; right: 0; bottom: 0; pointer-events: none; z-index: 10; border: 25px solid; transition: all 0.5s ease; }

    /* BINGKAI PREVIEW DINAMIS SESUAI TEMA */
    <?php if($filter_pilihan == 'soft'): ?>
        .frame-overlay { border-color: #FFC0CB; } 
    <?php elseif($filter_pilihan == 'vintage'): ?>
        .frame-overlay { border-color: #ADD8E6; } 
    <?php elseif($filter_pilihan == 'mahogany'): ?>
        .frame-overlay { border-color: #4E2A1E; } 
    <?php endif; ?>

    .preview-box { width: 120px; height: 90px; background: #eee; border-radius: 10px; overflow: hidden; border: 3px solid #fff; display: flex; align-items: center; justify-content: center; color: #db7093; font-weight: bold; }
    .btn-pink { background: linear-gradient(45deg, #db7093, #ffb6c1); color: white; border: none; font-weight: bold; }
</style>

<div class="container text-center mt-4 mb-5">
    <div class="card p-4 shadow-lg border-0 animate__animated animate__fadeIn" style="max-width: 800px; margin: auto; border-radius: 30px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
        <h2 class="fw-bold" style="color: #db7093; font-family: 'Playball', cursive;">PinkyPromise Photobooth 📸</h2>
        <p id="status-text" class="text-muted fw-bold">Halo <span style="color: #db7093;"><?= htmlspecialchars($nama_tamu) ?></span>!</p>
        
        <div class="video-wrapper mb-4">
            <div class="frame-overlay"></div>
            <video id="video" autoplay playsinline></video>
            <canvas id="canvas" style="display:none;"></canvas>
        </div>

        <div class="d-flex justify-content-center gap-3 mb-4">
            <div id="p1" class="preview-box shadow-sm">1</div>
            <div id="p2" class="preview-box shadow-sm">2</div>
            <div id="p3" class="preview-box shadow-sm">3</div>
            <div id="p4" class="preview-box shadow-sm">4</div>
        </div>

        <form id="photo-form" action="<?= $target_proses ?>" method="POST">
            <input type="hidden" name="image_data" id="image_data">
            <input type="hidden" id="filter_used" name="filter_used" value="<?= $filter_pilihan ?>">
            <input type="hidden" name="nama_tamu" value="<?= htmlspecialchars($nama_tamu) ?>">
            
            <button type="button" id="snap" class="btn btn-pink btn-lg px-5 shadow rounded-pill">
                MULAI SESI FOTO <i class="fas fa-camera-retro ms-2"></i>
            </button>
        </form>
    </div>
</div>

<script src="assets/js/kamera.js"></script>
<?php include 'includes/footer.php'; ?>