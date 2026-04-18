<?php include 'includes/header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<style>
    /* Background dinamis yang mewah & estetik */
    .main-bg {
        min-height: 90vh;
        background: linear-gradient(135deg, #fdfcfb 0%, #e2d1c3 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    /* Tombol Pink Khas PinkyPromise */
    .btn-pink-mewah {
        background: linear-gradient(45deg, #db7093, #ffb6c1);
        color: white;
        border: none;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-pink-mewah:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(219, 112, 147, 0.4);
        color: white;
    }

    /* Pilar Ikon Samping */
    .icon-strip-pilar i {
        display: block;
        font-size: 2rem;
        margin: 30px 0;
        color: #db7093;
        opacity: 0.6;
    }

    /* Animasi Mengambang pada Card */
    .floating {
        animation: floating 3s ease-in-out infinite;
    }

    @keyframes floating {
        0% { transform: translate(0, 0px); }
        50% { transform: translate(0, -15px); }
        100% { transform: translate(0, 0px); }
    }

    /* Custom Input Style */
    .custom-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(219, 112, 147, 0.25);
        background: #ffffff !important;
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
                    
                    <form action="ambil-foto.php" method="POST" class="animate__animated animate__fadeInUp">
                        
                        <div class="mb-4 text-start">
                            <label class="form-label fw-bold small text-secondary">
                                <i class="fas fa-smile me-2" style="color: #db7093;"></i>SIAPA NAMAMU?
                            </label>
                            <input type="text" name="nama_tamu" class="form-control form-control-lg shadow-sm border-0 rounded-pill custom-input" 
                                style="background: #f8f9fa; padding-left: 20px;"
                                placeholder="Tulis namamu di sini..." required>
                        </div>
                        
                        <div class="mb-4 text-start">
                            <label class="form-label fw-bold small text-secondary">
                                <i class="fas fa-wand-magic-sparkles me-2" style="color: #db7093;"></i>PILIH NUANSA FAVORIT
                            </label>
                            <select name="filter" class="form-select form-select-lg shadow-sm border-0 rounded-pill custom-input"
                                style="background: #f8f9fa; padding-left: 20px; cursor: pointer;">
                                <option value="soft">🌸 Soft Pink (Romantic)</option>
                                <option value="vintage">🧊 Blue Vintage (Cool & Calm)</option>
                                <option value="mahogany">🪵 Mahogany Luxury (Bold & Classy)</option>
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

<?php include 'includes/footer.php'; ?>