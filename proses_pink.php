<?php
/**
 * PinkyPromise Photobooth - Save Process (Soft Pink)
 * File ini berfungsi sebagai backend processor untuk menerima data gambar dari kamera,
 * mengonversinya, dan menyimpannya ke dalam server dengan identitas tema Soft Pink.
 */

// Menyisipkan file koneksi database Laragon pusat untuk menyimpan data biner gambar
include 'includes/koneksi.php';

// Mengecek apakah data gambar (image_data) telah dikirimkan melalui metode POST dari form kamera
if (isset($_POST['image_data'])) {
    
    // Menangkap nama tamu dari form input, disanitasi agar aman dari SQL Injection
    $nama_user = isset($_POST['nama_tamu']) ? mysqli_real_escape_string($koneksi, $_POST['nama_tamu']) : 'User Misterius';
    
    // Mengambil string data gambar dalam format Base64 yang dikirimkan oleh sistem JavaScript
    $img = $_POST['image_data'];
    
    // Membersihkan string: Menghapus bagian header data URI "data:image/png;base64,"
    $img = str_replace('data:image/png;base64,', '', $img);
    
    // Membersihkan string: Mengganti spasi kembali menjadi karakter '+' agar encoding Base64 tetap valid
    $img = str_replace(' ', '+', $img);
    
    // Melakukan proses dekode (pengubahan) dari string teks Base64 menjadi data biner gambar asli
    $data = base64_decode($img);

    /**
     * Membuat objek sumber daya gambar (image resource) di dalam memori server 
     * menggunakan library GD PHP berdasarkan data biner yang sudah didekode.
     */
    $source = imagecreatefromstring($data);
    
    // Memastikan bahwa sumber daya gambar berhasil dibuat dan data tidak mengalami kerusakan
    if ($source !== false) {
        
        /**
         * imagealphablending: Mengaktifkan mode pencampuran warna. 
         * Sangat penting agar warna pink yang lembut dan elemen transparan menyatu secara akurat.
         */
        imagealphablending($source, true);
        
        /**
         * imagesavealpha: Menginstruksikan server untuk ikut menyimpan informasi transparansi (alpha channel).
         * This ensures the image output remains crisp and retains the correct pink alpha transparency design.
         */
        imagesavealpha($source, true);

        // =========================================================================
        // AWAL - PENYESUAIAN PENGAMBILAN DATA BINGKAI DINAMIS DARI DATABASE MASTER
        // =========================================================================
        
        // Tetapkan ID Frame untuk Soft Pink berdasarkan tabel master database Anda
        $id_frame = 1;

        // Ambil kode warna HEX secara dinamis berdasarkan id_frame dari tabel frames
        $query_frame = "SELECT kode_warna FROM frames WHERE id_frame = '$id_frame'";
        $result_frame = mysqli_query($koneksi, $query_frame);
        $data_frame = mysqli_fetch_assoc($result_frame);
        $hex_warna = isset($data_frame['kode_warna']) ? $data_frame['kode_warna'] : '#DB7093'; // Fallback jika kosong

        // Konversi kode HEX (contoh: #DB7093) menjadi format RGB desimal untuk diolah oleh GD Library
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
             * Mengalihkan halaman kembali ke galeri.php dengan membawa informasi status sukses.
             * Parameter theme=pink digunakan untuk memicu respon visual bertema pink di halaman galeri.
             */
            header("Location: galeri.php?status=success&theme=pink");
            
            // Mengakhiri seluruh proses script untuk memastikan pengalihan halaman (redirect) segera dieksekusi
            exit();
        } else {
            // Memberikan pesan error yang jelas jika query database mengalami masalah
            die("Gagal menyimpan gambar ke database Laragon: " . mysqli_error($koneksi));
        }
    }
}

/**
 * JIKA TERJADI AKSES ILEGAL:
 * Jika file ini diakses tanpa data POST (misal diakses langsung via URL), 
 * maka sistem akan otomatis melempar user kembali ke halaman beranda.
 */
header("Location: index.php");

// Menghentikan script sepenuhnya
exit();
?>