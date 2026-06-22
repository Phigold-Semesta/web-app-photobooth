<?php
/**
 * PinkyPromise Photobooth - Save Process (Mahogany Luxury)
 * File ini merupakan mesin pemroses di sisi server (backend) yang bertugas
 * merubah data jepretan kamera menjadi file fisik PNG dengan nuansa Mahogany yang mewah.
 */

// Menyisipkan file koneksi database Laragon pusat untuk menyimpan data biner gambar
include 'includes/koneksi.php';

// Mengecek apakah request yang masuk membawa data 'image_data' melalui metode POST
if (isset($_POST['image_data'])) {
    
    // KUNCI PERBAIKAN: Menangkap nama tamu menggunakan indeks 'nama_guest' agar selaras dengan form HTML terbaru
    $nama_user = isset($_POST['nama_guest']) ? mysqli_real_escape_string($koneksi, $_POST['nama_guest']) : 'User Misterius';
    
    // Mengambil data gambar dalam format string Base64 yang dikirimkan oleh browser
    $img = $_POST['image_data'];
    
    // Menghapus label format data "data:image/png;base64," agar hanya menyisakan kode enkripsi gambarnya
    $img = str_replace('data:image/png;base64,', '', $img);
    
    // Memperbaiki karakter spasi menjadi tanda '+' untuk memastikan data Base64 kembali utuh (tidak korup)
    $img = str_replace(' ', '+', $img);
    
    // Mengubah string Base64 yang tadinya berupa teks kembali menjadi data biner mentah sebuah gambar
    $data = base64_decode($img);

    /**
     * Membentuk objek sumber daya gambar (image resource) menggunakan library GD PHP
     * berdasarkan data biner yang sudah didekode sebelumnya.
     */
    $source = imagecreatefromstring($data);
    
    // Memvalidasi apakah proses pembuatan sumber daya gambar di memori server berhasil
    if ($source !== false) {
        
        /**
         * Konfigurasi Warna & Transparansi:
         * imagealphablending set ke 'false' agar warna mahogany yang solid tidak tercampur 
         * dengan warna background sebelumnya saat proses penyimpanan dilakukan.
         */
        imagealphablending($source, false); 
        
        /**
         * imagesavealpha set ke 'true' untuk memastikan informasi transparansi (jika ada) 
         * tetap tersimpan dengan baik di dalam file format PNG.
         */
        imagesavealpha($source, true);

        // =========================================================================
        // AWAL - PENYESUAIAN PENGAMBILAN DATA BINGKAI DINAMIS DARI DATABASE MASTER
        // =========================================================================
        
        // Tetapkan ID Frame untuk Mahogany Luxury berdasarkan tabel master database Anda
        $id_frame = 3;

        // Ambil kode warna HEX secara dinamis berdasarkan id_frame dari tabel frames
        $query_frame = "SELECT kode_warna FROM frames WHERE id_frame = '$id_frame'";
        $result_frame = mysqli_query($koneksi, $query_frame);
        $data_frame = mysqli_fetch_assoc($result_frame);
        $hex_warna = isset($data_frame['kode_warna']) ? $data_frame['kode_warna'] : '#4E2A1E'; // Fallback jika kosong

        // Konversi kode HEX (contoh: #4E2A1E) menjadi format RGB desimal untuk diolah oleh GD Library
        list($r, $g, $b) = sscanf($hex_warna, "#%02x%02x%02x");

        // Ambil dimensi lebar dan tinggi asli dari foto kamera mentah
        $width = imagesx($source);
        $height = imagesy($source);

        // KUNCI PERBAIKAN: Kanvas dibuat PAS sesuai ukuran asli gambar kamera tanpa tambahan tebal bingkai manual ke samping
        $canvas = imagecreatetruecolor($width, $height);

        // MATIKAN BLENDING SEMENTARA AGAR WARNA LATAR BELAKANG TERSIRAM SOLID MURNI DARI DATABASE
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);

        // Daftarkan komponen warna RGB hasil konversi database ke dalam objek kanvas baru
        $warna_bingkai = imagecolorallocate($canvas, $r, $g, $b);

        // Warnai seluruh permukaan latar belakang kanvas baru dengan warna bingkai database
        imagefill($canvas, 0, 0, $warna_bingkai);

        // HIDUPKAN KEMBALI BLENDING UNTUK MENEMPELKAN FOTO DENGAN SEMPURNA
        imagealphablending($canvas, true);

        // KUNCI PERBAIKAN: Tempelkan gambar tepat di posisi koordinat (0,0) tanpa pergeseran margin piksel luar agar tidak double tebal
        imagecopy($canvas, $source, 0, 0, 0, 0, $width, $height);

        // =========================================================================
        // AKHIR - PENYESUAIAN PENGAMBILAN DATA BINGKAI DINAMIS DARI DATABASE MASTER
        // =========================================================================

        /**
         * PERBAIKAN & PENYESUAIAN DIREK DATABASE:
         * Menggunakan output buffering untuk menangkap data biner gambar langsung dari memori 
         * tanpa harus membuat atau menulis file fisik ke dalam folder uploads/ lagi.
         */
        ob_start(); // Membuka buffer memori internal system
        imagepng($canvas); // SEKARANG MENGALIRKAN DATA GAMBAR DARI $canvas YANG UKURANNYA PAS PROPORSIONAL
        $gambar_biner = ob_get_clean(); // Mengambil isi biner gambar dari buffer lalu membersihkannya
        
        // Mengamankan data biner mentah gambar agar siap dimasukkan ke dalam query SQL
        $gambar_aman = mysqli_real_escape_string($koneksi, $gambar_biner);

        // =========================================================================
        // STRATEGI KUNCI PENYEMPURNAAN RE-RELASIONAL DATABASE (DOUBLE INSERT)
        // =========================================================================
        
        // TAHAP 1: Daftarkan entri identitas ke tabel master 'guests' terlebih dahulu agar tidak kosong
        $query_guest = "INSERT INTO guests (nama_guest) VALUES ('$nama_user')";
        
        if (mysqli_query($koneksi, $query_guest)) {
            // Tangkap id_guest yang baru saja digenerate otomatis oleh sistem database
            $id_guest_baru = mysqli_insert_id($koneksi);
            
            /**
             * TAHAP 2: SOLUSI PERBAIKAN EROR DI SINI!
             * Menghapus kolom 'nama_user' karena kolom tersebut tidak ada di struktur tabel photos phpMyAdmin Anda.
             * Data diinput presisi mengikuti field asli yaitu: id_guest, id_frame, dan file_gambar.
             */
            $query_photo = "INSERT INTO photos (id_guest, id_frame, file_gambar) 
                            VALUES ('$id_guest_baru', '$id_frame', '$gambar_aman')";

            // Memastikan query sql berhasil dieksekusi ke database Laragon
            if (mysqli_query($koneksi, $query_photo)) {
                
                // Menghapus resource gambar dari memori RAM server agar menghemat resource laptop bos
                imagedestroy($source);
                imagedestroy($canvas);
                
                /**
                 * KUNCI PENYESUAIAN REDIRECT:
                 * Menambahkan parameter nama_guest pada pengalihan URL halaman galeri agar estafet 
                 * nama tamu tetap terjaga sampai akhir sesi pemotretan.
                 */
                header("Location: galeri.php?status=success&theme=mahogany&nama_guest=" . urlencode($nama_user));
                
                // Menghentikan eksekusi script PHP agar proses pengalihan (redirect) tidak terganggu
                exit();
            } else {
                // Jika query gagal, pastikan RAM dibersihkan sebelum mematikan sistem
                imagedestroy($source);
                imagedestroy($canvas);
                
                // Memberikan pesan error yang jelas jika query database mengalami masalah
                die("Gagal menyimpan gambar ke tabel photos: " . mysqli_error($koneksi));
            }
        } else {
            // Jika proses pendaftaran tamu gagal, bersihkan memori RAM server
            imagedestroy($source);
            imagedestroy($canvas);
            die("Gagal mendaftarkan nama tamu ke tabel guests: " . mysqli_error($koneksi));
        }
        // =========================================================================
    }
}

/**
 * Jika script diakses secara tidak sah atau tanpa mengirim data gambar,
 * maka otomatis akan dilempar kembali ke halaman beranda (index.php).
 */
header("Location: index.php");

// Memastikan script berhenti sepenuhnya
exit();
?>