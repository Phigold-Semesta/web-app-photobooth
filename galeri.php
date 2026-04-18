<?php include 'includes/header.php'; ?>

<style>
    /* Styling Kartu Galeri */
    .card-romantis {
        transition: all 0.3s ease;
        border: 2px solid #ffb6c1;
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
        position: relative;
    }

    .card-romantis:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(219, 112, 147, 0.2);
    }

    .img-container {
        height: 450px; 
        overflow-y: auto;
        background-color: #f8f9fa;
    }

    .img-strip {
        width: 100%;
        height: auto;
        display: block;
    }

    /* Custom Scrollbar */
    .img-container::-webkit-scrollbar { width: 5px; }
    .img-container::-webkit-scrollbar-thumb { background: #ffb6c1; border-radius: 10px; }

    /* Custom Dropdown & Button Styling */
    .btn-pink { background: #db7093; color: white; border-radius: 50px; border: none; }
    .btn-pink:hover { background: #c25e80; color: white; }
    
    .btn-outline-pink { border: 2px solid #db7093; color: #db7093; font-weight: bold; }
    .btn-outline-pink:hover { background: #db7093; color: white; }

    /* REVISI: Animasi Tombol Hapus yang Lebih Kalem & Mewah */
    .btn-delete {
        background: rgba(255, 255, 255, 0.9);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.2);
        width: 38px;
        height: 38px;
        border-radius: 50%;
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 10;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .btn-delete:hover {
        background: #dc3545;
        color: white;
        transform: scale(1.15); /* Hanya membesar sedikit, tidak berputar */
        box-shadow: 0 6px 15px rgba(220, 53, 69, 0.3);
    }
</style>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold" style="color: #db7093; font-family: 'Playball', cursive; font-size: 3rem;">🌸 Koleksi Momen Manismu 🌸</h1>
        <p class="text-muted">Kenangan indah yang berhasil diabadikan dalam Pinky Strip.</p>
        <hr class="mx-auto" style="width: 100px; border: 2px solid #ffb6c1; opacity: 1;">
        
        <div class="dropdown">
            <button class="btn btn-pink dropdown-toggle px-4 py-2 shadow-sm fw-bold" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-camera me-2"></i> Ambil Foto Lagi
            </button>
            <ul class="dropdown-menu dropdown-menu-center animate__animated animate__fadeIn">
                <li><h6 class="dropdown-header">Pilih Suasana Baru:</h6></li>
                <li>
                    <form action="ambil-foto.php" method="POST">
                        <input type="hidden" name="filter" value="soft">
                        <button type="submit" class="dropdown-item fw-bold text-pink" style="color:#db7093;">💖 Sweet Pink</button>
                    </form>
                </li>
                <li>
                    <form action="ambil-foto.php" method="POST">
                        <input type="hidden" name="filter" value="vintage">
                        <button type="submit" class="dropdown-item fw-bold text-primary">📸 Vintage Blue</button>
                    </form>
                </li>
                <li>
                    <form action="ambil-foto.php" method="POST">
                        <input type="hidden" name="filter" value="mahogany">
                        <button type="submit" class="dropdown-item fw-bold" style="color:#4e2a1e;">✨ Mahogany Luxury</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <?php
        $dir = "uploads/";
        if (!is_dir($dir)) { mkdir($dir, 0777, true); }

        $images = glob($dir . "*.png");
        if ($images) {
            array_multisort(array_map('filemtime', $images), SORT_DESC, $images);

            foreach ($images as $image) {
                $fileName = basename($image);
                $uploadTime = date("d M Y | H:i", filemtime($image));
                ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4 animate__animated animate__zoomIn">
                    <div class="card card-romantis shadow-sm">
                        
                        <a href="hapus-foto.php?file=<?= $fileName ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Hapus foto kenangan ini? Tindakan ini tidak bisa dibatalkan.')" 
                           title="Hapus Foto">
                            <i class="fas fa-trash-alt"></i>
                        </a>

                        <div class="img-container">
                            <img src="<?php echo $image; ?>" class="img-strip" alt="Pinky Strip">
                        </div>
                        
                        <div class="card-body bg-white text-center p-3">
                            <small class="text-pink fw-bold d-block mb-2" style="color: #db7093;">
                                <i class="far fa-calendar-alt"></i> <?php echo $uploadTime; ?>
                            </small>
                            <a href="<?php echo $image; ?>" download class="btn btn-outline-pink btn-sm w-100 rounded-pill fw-bold">
                                Simpan Foto 💖
                            </a>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '
            <div class="col-12 text-center py-5">
                <div class="card p-5 bg-white shadow-sm border-0" style="border-radius: 30px;">
                    <h3 class="text-muted">Oops! Galeri masih kosong.</h3>
                    <p>Jangan biarkan harimu berlalu tanpa kenangan manis!</p>
                    <div class="display-1">📸</div>
                </div>
            </div>';
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>