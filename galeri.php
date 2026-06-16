<?php 
// Menyisipkan file header untuk bagian navigasi atas dan meta data
include 'includes/header.php'; 

// 1. Menyisipkan file koneksi database Laragon pusat
include 'includes/koneksi.php'; 
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Styling Kartu Galeri: Mengatur tampilan kartu foto agar terlihat romantis dan elegan */
    .card-romantis {
        transition: all 0.3s ease; /* Memberikan efek transisi halus saat kartu berinteraksi */
        border: 2px solid #ffb6c1; /* Memberikan garis tepi berwarna pink muda */
        border-radius: 20px; /* Membuat sudut kartu melengkung halus */
        overflow: hidden; /* Memastikan konten di dalam tidak keluar dari area lengkungan kartu */
        background: #fff; /* Warna latar belakang kartu putih bersih */
        position: relative; /* Dasar posisi untuk meletakkan tombol hapus secara absolut */
    }

    /* Efek saat kursor berada di atas kartu: kartu naik sedikit ke atas dan muncul bayangan lembut */
    .card-romantis:hover {
        transform: translateY(-10px); 
        box-shadow: 0 15px 30px rgba(219, 112, 147, 0.2); 
    }

    /* Wadah Gambar: Mengatur tinggi tetap dan memberikan fungsi scroll jika gambar terlalu panjang (strip) */
    .img-container {
        height: 450px; 
        overflow-y: auto; /* Memungkinkan scroll vertikal untuk melihat seluruh strip foto */
        background-color: #f8f9fa; /* Warna latar belakang abu-abu sangat muda */
    }

    /* Pengaturan Gambar Strip: Memastikan gambar memenuhi lebar wadah secara otomatis */
    .img-strip {
        width: 100%;
        height: auto;
        display: block;
    }

    /* Kustomisasi Bar Scroll: Mengubah tampilan scroll agar serasi dengan tema pink */
    .img-container::-webkit-scrollbar { width: 5px; }
    .img-container::-webkit-scrollbar-thumb { background: #ffb6c1; border-radius: 10px; }

    /* Tombol Pink Utama: Digunakan untuk tombol "Ambil Foto Lagi" */
    .btn-pink { background: #db7093; color: white; border-radius: 50px; border: none; }
    .btn-pink:hover { background: #c25e80; color: white; }
    
    /* Tombol Outline Pink: Digunakan untuk tombol "Simpan Foto" */
    .btn-outline-pink { border: 2px solid #db7093; color: #db7093; font-weight: bold; }
    .btn-outline-pink:hover { background: #db7093; color: white; }

    /* Tombol Hapus: Lingkaran kecil di pojok kanan atas foto dengan efek transparan */
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
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    /* Efek Hover Tombol Hapus: Warna menjadi merah solid dan membesar sedikit */
    .btn-delete:hover {
        background: #dc3545;
        color: white;
        transform: scale(1.15);
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
                <li><form action="ambil-foto.php" method="POST"><input type="hidden" name="filter" value="soft"><button type="submit" class="dropdown-item fw-bold text-pink">💖 Sweet Pink</button></form></li>
                <li><form action="ambil-foto.php" method="POST"><input type="hidden" name="filter" value="vintage"><button type="submit" class="dropdown-item fw-bold text-primary">📸 Vintage Blue</button></form></li>
                <li><form action="ambil-foto.php" method="POST"><input type="hidden" name="filter" value="mahogany"><button type="submit" class="dropdown-item fw-bold" style="color:#4e2a1e;">✨ Mahogany Luxury</button></form></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <?php
        /**
         * PERBAIKAN & PENYESUAIAN QUERY RELASIONAL (INNER JOIN):
         * Menghubungkan tabel photos dengan tabel frames berdasarkan foreign key id_frame.
         * Mengambil nama_frame dari tabel master frames untuk dijadikan nama tema dinamis.
         */
        $query  = "SELECT p.id_user, p.nama_user, p.file_gambar, p.created_at, f.nama_frame 
                   FROM photos p 
                   INNER JOIN frames f ON p.id_frame = f.id_frame 
                   ORDER BY p.id_user DESC";
        $result = mysqli_query($koneksi, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            
            // Melakukan perulangan untuk setiap baris data gambar yang ditemukan di database
            while ($row = mysqli_fetch_assoc($result)) {
                
                $idUser     = $row['id_user'];
                $namaUser   = $row['nama_user'];
                $temaFoto   = $row['nama_frame']; // Sekarang mengambil data nama_frame hasil INNER JOIN
                
                // Mengonversi waktu simpan database ke format tanggal yang mudah dibaca bos
                $uploadTime = date("d M Y | H:i", strtotime($row['created_at'])); 
                
                // --- STRATEGI MERENDER GAMBAR DARI BINER LONGBLOB KE BASE64 DATA URI ---
                $binerGambar  = $row['file_gambar'];
                $base64Gambar = base64_encode($binerGambar);
                $srcGambar    = 'data:image/png;base64,' . $base64Gambar;
                // ----------------------------------------------------------------------
                
                // Membuat nama file unduhan dinamis berdasarkan nama user dan tema
                $downloadName = "PinkyPromise_" . str_replace(' ', '_', $namaUser) . "_" . date("Ymd_His", strtotime($row['created_at'])) . ".png";
                ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4 animate__animated animate__zoomIn">
                    <div class="card card-romantis shadow-sm">
                        
                        <div class="btn-delete" onclick="konfirmasiHapus('<?= $idUser ?>')" title="Hapus Foto">
                            <i class="fas fa-trash-alt"></i>
                        </div>

                        <div class="img-container">
                            <img src="<?php echo $srcGambar; ?>" class="img-strip" alt="Pinky Strip">
                        </div>
                        
                        <div class="card-body bg-white text-center p-3">
                            <h6 class="fw-bold text-dark mb-1 text-truncate" title="<?php echo htmlspecialchars($namaUser); ?>">
                                <i class="fas fa-user me-1" style="color: #db7093;"></i> <?php echo htmlspecialchars($namaUser); ?>
                            </h6>
                            
                            <span class="badge mb-2 text-muted" style="font-size: 0.75rem; border: 1px solid #eee;">
                                <?php echo htmlspecialchars($temaFoto); ?>
                            </span>

                            <small class="text-pink fw-bold d-block mb-2" style="color: #db7093;">
                                <i class="far fa-calendar-alt"></i> <?php echo $uploadTime; ?>
                            </small>
                            
                            <a href="<?php echo $srcGambar; ?>" download="<?php echo $downloadName; ?>" class="btn btn-outline-pink btn-sm w-100 rounded-pill fw-bold">
                                Simpan Foto 💖
                            </a>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            // Tampilan jika tabel database masih kosong murni
            echo '<div class="col-12 text-center py-5"><h3>Oops! Galeri masih kosong.</h3></div>';
        }
        
        // Menutup koneksi database yang sudah dibuka
        mysqli_close($koneksi);
        ?>
    </div>
</div>

<script>
    /**
     * Fungsi konfirmasiHapus: Menampilkan popup peringatan sebelum benar-benar menghapus data
     */
    function konfirmasiHapus(idUser) {
        Swal.fire({
            title: 'Hapus Foto?',
            text: "Kenangan ini akan hilang selamanya dari database, bos!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#db7093', // Warna tombol konfirmasi disesuaikan dengan tema pink
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus saja!',
            cancelButtonText: 'Batal',
            borderRadius: '20px'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mengarahkan browser ke file penghapus dengan parameter id_user dari database
                window.location.href = 'hapus-foto.php?id=' + idUser;
            }
        })
    }

    /**
     * Logika Feedback: Mengecek apakah ada parameter 'status=deleted' di URL setelah redirect dari hapus-foto.php
     */
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'deleted') {
        Swal.fire({
            title: 'Terhapus!',
            text: 'Foto telah berhasil dihapus dari database.',
            icon: 'success',
            confirmButtonColor: '#db7093'
        });
        // Membersihkan URL agar pesan sukses tidak muncul berulang kali saat halaman di-refresh
        window.history.replaceState({}, document.title, window.location.pathname);
    }
</script>

<?php 
// Menyisipkan file footer untuk menutup tag HTML dan menyisipkan script Bootstrap
include 'includes/footer.php'; 
?>