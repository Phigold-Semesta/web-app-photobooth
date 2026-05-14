<!DOCTYPE html> <html lang="id"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>PinkyPromise - Photobooth Experience ✨</title> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Playball&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    

    <style> /* Awal blok CSS internal untuk gaya spesifik halaman ini */
        
        /* Mengatur font utama dan warna latar belakang dasar untuk seluruh halaman body */
        body { 
            font-family: 'Poppins', sans-serif; /* Menggunakan font Poppins sebagai font utama */
            background-color: #fdfcfb; /* Memberikan warna latar belakang putih gading yang sangat lembut */
        }

        /* Navbar Glassmorphism: Memberikan efek transparan seperti kaca buram pada bar navigasi */
        .navbar-pink {
            background: rgba(219, 112, 147, 0.95) !important; /* Warna Deep Pink dengan transparansi 95% menggunakan nilai RGBA */
            backdrop-filter: blur(15px); /* Memberikan efek buram (blur) pada elemen di belakang navbar saat digulir */
            border-bottom: 4px solid #c71585; /* Memberikan garis bawah tebal berwarna pink tua sebagai aksen */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15); /* Memberikan bayangan halus di bawah navbar agar terlihat memiliki dimensi */
            padding: 15px 0; /* Memberikan ruang udara/jarak vertikal di dalam navbar agar terlihat lebih lega */
        }
        
        /* Mengatur estetika teks Logo Brand di sisi kiri navbar */
        .navbar-brand {
            font-family: 'Playball', cursive; /* Menggunakan font Playball agar logo terlihat seperti tulisan tangan mewah */
            font-size: 2.2rem; /* Mengatur ukuran font logo menjadi besar (2.2 unit rem) */
            letter-spacing: 1px; /* Memberikan sedikit jarak antar huruf agar tidak terlalu rapat */
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2); /* Memberikan bayangan pada teks logo agar lebih kontras dan elegan */
            transition: all 0.3s ease; /* Memberikan durasi transisi halus saat terjadi perubahan status (seperti hover) */
        }

        /* Efek interaktif saat kursor menyentuh logo (hover) */
        .navbar-brand:hover {
            transform: scale(1.05); /* Logo akan membesar 5% secara halus saat disentuh kursor */
        }

        /* Container Flexbox: Teknik untuk memastikan Logo di kiri dan Menu di kanan terpisah secara otomatis */
        .header-flex-container {
            display: flex; /* Mengaktifkan mode Flexbox pada container */
            justify-content: space-between; /* Membagi ruang agar konten tersebar ke ujung kiri dan kanan */
            align-items: center; /* Memastikan logo dan menu sejajar secara vertikal di tengah */
            width: 100%; /* Memastikan container menggunakan lebar penuh */
        }

        /* Mengatur gaya teks pada setiap item menu navigasi */
        .nav-link {
            font-weight: 500; /* Mengatur ketebalan font menjadi sedang (medium) */
            font-size: 1.1rem; /* Mengatur ukuran teks menu */
            transition: all 0.3s ease; /* Memberikan efek transisi halus saat menu disorot */
            margin-left: 30px; /* Memberikan jarak antar menu secara horizontal pada layar besar */
            opacity: 0.9; /* Membuat menu sedikit agak transparan secara default */
            position: relative; /* Dasar posisi untuk elemen garis bawah (::after) nanti */
        }

        /* Mengubah tampilan menu saat kursor diarahkan ke teks menu */
        .nav-link:hover {
            color: #fff !important; /* Memaksa warna teks menjadi putih pekat */
            opacity: 1; /* Menghilangkan efek transparansi saat disorot agar terlihat lebih terang */
        }
        
        /* Gaya khusus untuk menandakan menu/halaman mana yang sedang aktif dibuka */
        .active-link {
            font-weight: 700; /* Membuat teks menu yang aktif menjadi tebal (Bold) */
        }

        /* Membuat garis bawah putih solid secara otomatis di bawah menu yang sedang aktif */
        .active-link::after {
            content: ''; /* Syarat wajib memunculkan elemen pseudo */
            position: absolute; /* Mengatur posisi bebas di dalam area menu terkait */
            bottom: -5px; /* Menempatkan garis 5 pixel di bawah teks menu */
            left: 0; /* Menempelkan garis di sisi kiri menu */
            width: 100%; /* Panjang garis mengikuti lebar teks menu secara penuh */
            height: 3px; /* Ketebalan garis bawah penanda */
            background-color: #fff; /* Memberikan warna putih pada garis penanda */
            border-radius: 10px; /* Membuat ujung garis menjadi tumpul/bulat */
        }

        /* Media Query: Kumpulan instruksi CSS khusus untuk perangkat layar kecil/HP (max 991px) */
        @media (max-width: 991px) {
            .nav-link {
                margin-left: 0; /* Menghilangkan jarak kiri karena menu disusun vertikal di HP */
                margin-top: 10px; /* Memberikan jarak antar menu secara vertikal */
                padding-bottom: 5px; /* Memberikan ruang di bawah teks menu */
            }
            .active-link::after {
                display: none; /* Menghilangkan garis bawah di HP agar tampilan menu lebih bersih */
            }
        }
    </style> </head> <body> <nav class="navbar navbar-expand-lg navbar-dark navbar-pink sticky-top animate__animated animate__fadeInDown">
    <div class="container"> <div class="header-flex-container"> <a class="navbar-brand text-white d-flex align-items-center" href="index.php">
                <i class="fas fa-camera-retro me-3"></i> PinkyPromise </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span> </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="navbar-nav"> <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
                    
                    <a class="nav-link text-white <?php echo ($current_page == 'index.php') ? 'active-link' : ''; ?>" href="index.php">
                        <i class="fas fa-home me-2"></i> Beranda </a>
                    
                    <a class="nav-link text-white <?php echo ($current_page == 'galeri.php') ? 'active-link' : ''; ?>" href="galeri.php">
                        <i class="fas fa-images me-2"></i> Galeri </a>
                </div>
            </div>

        </div> 
    </div>
 </nav> 

