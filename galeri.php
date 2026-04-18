<?php include 'includes/header.php'; ?>

<style>
    .card-romantis {
        transition: transform 0.3s ease;
        border: 2px solid #ffb6c1;
        overflow: hidden;
    }

    .card-romantis:hover {
        transform: scale(1.05);
    }

    .img-container {
        height: 450px; /* Batasi tinggi agar tidak terlalu panjang di layar */
        overflow-y: auto; /* Jika foto sangat panjang, bisa di-scroll di dalam kartu */
        background-color: #f8f9fa;
    }

    .img-strip {
        width: 100%;
        height: auto;
        display: block;
    }

    /* Custom Scrollbar untuk area foto agar tetap cantik */
    .img-container::-webkit-scrollbar {
        width: 5px;
    }
    .img-container::-webkit-scrollbar-thumb {
        background: #ffb6c1;
        border-radius: 10px;
    }
</style>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold text-pink">🌸 Koleksi Momen Manismu 🌸</h1>
        <p class="text-muted">Kenangan indah yang berhasil diabadikan dalam Pinky Strip.</p>
        <hr class="mx-auto" style="width: 100px; border: 2px solid #ffb6c1; opacity: 1;">
        <a href="ambil-foto.php" class="btn btn-pink rounded-pill px-4 shadow">
            <i class="fas fa-camera me-2"></i> Ambil Foto Lagi
        </a>
    </div>

    <div class="row">
        <?php
        $dir = "uploads/";
        
        // Cek apakah folder ada
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // Mengambil semua file .png dan diurutkan berdasarkan yang terbaru (FILEMtime)
        $images = glob($dir . "*.png");
        array_multisort(array_map('filemtime', $images), SORT_DESC, $images);

        if (count($images) > 0) {
            foreach ($images as $image) {
                // Mengambil nama file untuk identitas
                $fileName = basename($image);
                $uploadTime = date("d M Y | H:i", filemtime($image));
                ?>
                
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card card-romantis shadow-sm">
                        <div class="img-container">
                            <img src="<?php echo $image; ?>" class="img-strip" alt="Pinky Strip">
                        </div>
                        
                        <div class="card-body bg-white text-center p-3">
                            <small class="text-pink fw-bold d-block mb-2">
                                <i class="far fa-calendar-alt"></i> <?php echo $uploadTime; ?>
                            </small>
                            <a href="<?php echo $image; ?>" download class="btn btn-outline-pink btn-sm w-100 rounded-pill">
                                Simpan Foto 💖
                            </a>
                        </div>
                    </div>
                </div>

                <?php
            }
        } else {
            // Tampilan jika galeri kosong
            echo '
            <div class="col-12 text-center py-5">
                <div class="card p-5 bg-white shadow-sm border-0 rounded-20">
                    <h3 class="text-muted">Oops! Galeri masih kosong.</h3>
                    <p>Jangan biarkan harimu berlalu tanpa kenangan manis!</p>
                    <div class="fs-1">📸</div>
                </div>
            </div>';
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>