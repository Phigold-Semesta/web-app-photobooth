<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinkyPromise - Photobooth Experience ✨</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Playball&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #fdfcfb; 
        }

        /* Navbar Glassmorphism - Mewah & Proporsional */
        .navbar-pink {
            background: rgba(219, 112, 147, 0.95) !important; /* Deep Pink Elegan */
            backdrop-filter: blur(15px);
            border-bottom: 4px solid #c71585; /* Medium Violet Red */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 15px 0; /* Memberikan ruang napas agar lebih mewah */
        }
        
        .navbar-brand {
            font-family: 'Playball', cursive;
            font-size: 2.2rem; /* Sedikit diperbesar agar proporsional di sisi kiri */
            letter-spacing: 1px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        /* Container flexbox untuk memastikan posisi kanan-kiri murni */
        .header-flex-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .nav-link {
            font-weight: 500;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            margin-left: 30px; /* Jarak antar menu di sisi kanan */
            opacity: 0.9;
            position: relative;
        }

        .nav-link:hover {
            color: #fff !important;
            opacity: 1;
        }
        
        /* Underline effect untuk menu aktif */
        .active-link {
            font-weight: 700;
        }

        .active-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: #fff;
            border-radius: 10px;
        }

        /* Responsive Mobile */
        @media (max-width: 991px) {
            .nav-link {
                margin-left: 0;
                margin-top: 10px;
                padding-bottom: 5px;
            }
            .active-link::after {
                display: none; /* Hilangkan garis bawah di mobile agar tidak berantakan */
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-pink sticky-top animate__animated animate__fadeInDown">
    <div class="container">
        <div class="header-flex-container">
            
            <a class="navbar-brand text-white d-flex align-items-center" href="index.php">
                <i class="fas fa-camera-retro me-3"></i> PinkyPromise
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="navbar-nav">
                    <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
                    
                    <a class="nav-link text-white <?php echo ($current_page == 'index.php') ? 'active-link' : ''; ?>" href="index.php">
                        <i class="fas fa-home me-2"></i> Beranda
                    </a>
                    
                    <a class="nav-link text-white <?php echo ($current_page == 'galeri.php') ? 'active-link' : ''; ?>" href="galeri.php">
                        <i class="fas fa-images me-2"></i> Galeri
                    </a>
                </div>
            </div>

        </div>
    </div>
</nav>