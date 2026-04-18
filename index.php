<?php include 'includes/header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="container-fluid d-flex align-items-center justify-content-center p-0" style="min-height: 90vh; overflow: hidden; background: linear-gradient(135deg, #fff0f5 0%, #ffd1dc 50%, #ffc0cb 100%);">
    
    <div class="potobooth-container position-relative d-flex align-items-center justify-content-center" style="width: 100%; max-width: 1200px;">

        <div class="icon-strip-pilar strip-left floating-strip animate__animated animate__fadeInLeft d-none d-xl-flex">
            <i class="fas fa-camera-retro"></i>
            <i class="fas fa-heart"></i>
            <i class="fas fa-magic"></i>
            <i class="fas fa-sparkles"></i>
        </div>

        <div class="col-md-8 col-lg-6 col-xl-5" style="z-index: 10; position: relative;">
            <div class="card card-romantis p-4 animate__animated animate__fadeIn floating shadow-lg border-0" style="border-radius: 30px; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(15px);">
                <div class="card-body text-center">
                    
                   

                    <h1 class="fw-bold text-pink animate__animated animate__pulse animate__infinite animate__slower mb-1" style="letter-spacing: 1px; font-size: 2.5rem; color: #db7093;">
                        PinkyPromise
                    </h1>
                    <h4 class="fw-light mb-4" style="color: #db7093;">Booth 🌸</h4>
                    <p class="text-muted mb-4 small">Abadikan momen manismu dalam jepretan ikonik.</p>
                    
                    <form action="ambil-foto.php" method="POST" class="animate__animated animate__fadeInUp animate__delay-1s">
                        
                        <div class="mb-4 text-start">
                            <label class="form-label fw-bold small text-secondary">
                                <i class="fas fa-heart me-2" style="color: #db7093;"></i>NAMA KAMU
                            </label>
                            <input type="text" name="nama_tamu" class="form-control form-control-lg shadow-sm border-0 rounded-pill" 
                                style="background: rgba(255, 255, 255, 0.9); padding-left: 20px;"
                                placeholder="Tulis namamu di sini..." required>
                        </div>
                        
                        <div class="mb-4 text-start">
                            <label class="form-label fw-bold small text-secondary">
                                <i class="fas fa-magic me-2" style="color: #db7093;"></i>PILIH NUANSA
                            </label>
                            <select name="filter" class="form-select form-select-lg shadow-sm border-0 rounded-pill"
                                style="background: rgba(255, 255, 255, 0.9); padding-left: 20px;">
                                <option value="soft">🌸 Soft Pink (Romantic)</option>
                                <option value="vintage">🎞️ Vintage Rose (Classic)</option>
                                <option value="bright">✨ Bright Sparkle (Glowing)</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-pink-mewah btn-lg w-100 shadow-lg rounded-pill animate__animated animate__pulse animate__infinite animate__slower">
                            MULAI SESI FOTO <i class="fas fa-camera-retro ms-2"></i>
                        </button>
                    </form>

                    <div class="mt-4">
                        <a href="galeri.php" class="text-decoration-none small fw-bold hover-underline" style="color: #db7093;">
                            <i class="fas fa-images me-1"></i> LIHAT GALERI FOTO
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="icon-strip-pilar strip-right floating-strip animate__animated animate__fadeInRight d-none d-xl-flex">
            <i class="fas fa-grin-hearts"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-film"></i>
            <i class="fas fa-smile-beam"></i>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>