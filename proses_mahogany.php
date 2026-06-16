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
    
    // Menangkap nama tamu dari form input, disanitasi agar aman dari SQL Injection
    $nama_user = isset($_POST['nama_tamu']) ? mysqli_real_escape_string($koneksi, $_POST['nama_tamu']) : 'User Misterius';
    
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

        /**
         * Query INSERT digunakan untuk menjebloskan data user beserta fisik gambar murni (LONGBLOB) 
         * secara langsung ke tabel 'photos' di database db_photobooth Laragon.
         * Perubahan Kolom: Kolom 'tema' lama diganti menjadi foreign key 'id_frame'.
         */
        $query = "INSERT INTO photos (nama_user, file_gambar, id_frame) VALUES ('$nama_user', '$gambar_aman', '$id_frame')";

        // Memastikan query sql berhasil dieksekusi ke database Laragon
        if (mysqli_query($koneksi, $query)) {
            
            // Menghapus resource gambar dari memori RAM server agar menghemat resource laptop bos
            imagedestroy($source);
            imagedestroy($canvas);
            
            /**
             * Mengarahkan user kembali ke galeri dengan status sukses dan memberikan 
             * parameter tema 'mahogany' untuk pengaturan tampilan feedback di galeri.
             */
            header("Location: galeri.php?status=success&theme=mahogany");
            
            // Menghentikan eksekusi script PHP agar proses pengalihan (redirect) tidak terganggu
            exit();
        } else {
            // Jika query gagal, pastikan RAM dibersihkan sebelum mematikan sistem
            imagedestroy($source);
            imagedestroy($canvas);
            
            // Memberikan pesan error yang jelas jika query database mengalami masalah
            die("Gagal menyimpan gambar ke database Laragon: " . mysqli_error($koneksi));
        }
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