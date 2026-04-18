<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PinkyPromise - Photobooth Romantis ✨</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Playball&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        /* Navbar Glassmorphism Effect */
        .navbar-pink {
            background: rgba(255, 182, 193, 0.8) !important;
            backdrop-filter: blur(10px);
            border-bottom: 3px solid #ff69b4;
            box-shadow: 0 4px 15px rgba(255, 105, 180, 0.1);
        }
        
        .navbar-brand {
            font-family: 'Playball', cursive; /* Font khusus logo agar lebih estetik */
            font-size: 1.8rem;
        }

        .nav-link {
            font-weight: 600;
            transition: all 0.3s ease;
            margin: 0 5px;
        }

        .nav-link:hover {
            color: #db7093 !important;
            transform: translateY(-2px);
        }
        
        .active-link {
            border-bottom: 2px solid white;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-pink mb-4 sticky-top animate__animated animate__fadeInDown">
    <div class="container">
        <a class="navbar-brand text-white" href="index.php">
            <span class="animate__animated animate__pulse animate__infinite d-inline-block">💖</span> PinkyPromise
        </a>
        
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active-link' : ''; ?>" href="index.php">
                    <i class="fas fa-home me-1"></i> Home
                </a>
                <a class="nav-link text-white <?php echo (basename($_SERVER['PHP_SELF']) == 'galeri.php') ? 'active-link' : ''; ?>" href="galeri.php">
                    <i class="fas fa-images me-1"></i> Galeri
                </a>
            </div>
        </div>
    </div>
</nav>