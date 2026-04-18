<?php 
include 'includes/header.php'; 

// Menangkap data dari halaman depan
$nama_tamu = $_POST['nama_tamu'] ?? 'Sweet Guest';
$filter_pilihan = $_POST['filter'] ?? 'soft';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<style>
    .video-wrapper {
        position: relative;
        width: 100%;
        max-width: 640px;
        margin: auto;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    }

    #video {
        width: 100%;
        display: block;
        transform: scaleX(-1);
    }

    /* BINGKAI PREVIEW DINAMIS */
    .frame-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        pointer-events: none;
        z-index: 10;
        border: 25px solid; 
        transition: all 0.5s ease;
    }

    /* LOGIKA WARNA SESUAI INSTRUKSI BOS */
    <?php if($filter_pilihan == 'soft'): ?>
        .frame-overlay { border-color: #FFC0CB; } /* Pink Rose */
        #video { filter: saturate(1.2) brightness(1.1); }
    <?php elseif($filter_pilihan == 'vintage'): ?>
        .frame-overlay { border-color: #ADD8E6; } /* Biru Muda */
        #video { filter: sepia(0.4) contrast(1.1); }
    <?php elseif($filter_pilihan == 'bright'): ?>
        .frame-overlay { border-color: #4E2A1E; } /* Cokelat Mahogany */
        #video { filter: brightness(1.1) contrast(1.1); }
    <?php endif; ?>

    .preview-box {
        width: 120px; height: 90px;
        background: #eee;
        border-radius: 10px;
        overflow: hidden;
        border: 3px solid #fff;
        display: flex; align-items: center; justify-content: center;
        color: #db7093; font-weight: bold;
    }
    
    .btn-pink {
        background: linear-gradient(45deg, #db7093, #ffb6c1);
        color: white; border: none; font-weight: bold;
    }
</style>

<div class="container text-center mt-4 mb-5">
    <div class="card card-romantis p-4 shadow-lg border-0 animate__animated animate__fadeIn" style="max-width: 800px; margin: auto; border-radius: 30px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px);">
        
        <h2 class="fw-bold" style="color: #db7093;">Pinky Strip Session 🌸</h2>
        <p id="status-text" class="text-muted fw-bold">Halo <span style="color: #db7093;"><?= htmlspecialchars($nama_tamu) ?></span>!</p>
        
        <div class="video-wrapper mb-4">
            <div class="frame-overlay"></div>
            <video id="video" autoplay playsinline></video>
            <canvas id="canvas" style="display:none;"></canvas>
            <div id="countdown" class="position-absolute top-50 start-50 translate-middle fw-bold display-1 text-white" style="z-index: 20; text-shadow: 2px 2px 10px rgba(0,0,0,0.5); display: none;">3</div>
        </div>

        <div class="d-flex justify-content-center gap-3 mb-4">
            <div id="p1" class="preview-box shadow-sm">1</div>
            <div id="p2" class="preview-box shadow-sm">2</div>
            <div id="p3" class="preview-box shadow-sm">3</div>
            <div id="p4" class="preview-box shadow-sm">4</div>
        </div>

        <form id="photo-form" action="proses.php" method="POST">
            <input type="hidden" name="image_data" id="image_data">
            <input type="hidden" name="filter_used" value="<?= $filter_pilihan ?>">
            <input type="hidden" name="nama_tamu" value="<?= htmlspecialchars($nama_tamu) ?>">
            
            <button type="button" id="snap" class="btn btn-pink btn-lg px-5 shadow rounded-pill">
                MULAI SESI FOTO <i class="fas fa-camera-retro ms-2"></i>
            </button>
        </form>
    </div>
</div>

<script src="assets/js/kamera.js"></script>
<?php include 'includes/footer.php'; ?>