<?php
/**
 * PinkyPromise Photobooth - Save Process (Blue Vintage)
 * File ini berfungsi untuk mengolah data gambar mentah dari kamera dan menyimpannya sebagai file PNG.
 */

// Menyisipkan file koneksi database Laragon pusat untuk menyimpan data biner gambar
include 'includes/koneksi.php';

// Mengecek apakah ada data gambar (image_data) yang dikirimkan melalui metode POST dari halaman kamera
if (isset($_POST['image_data'])) {
    
    // Menangkap nama tamu dari form input, disanitasi agar aman dari SQL Injection
    $nama_user = isset($_POST['nama_tamu']) ? mysqli_real_escape_string($koneksi, $_POST['nama_tamu']) : 'User Misterius';
    
    // Mengambil data string Base64 yang dikirimkan oleh sistem kamera JavaScript
    $img = $_POST['image_data'];
    
    // Menghilangkan header "data:image/png;base64," agar hanya menyisakan kode enkripsi gambarnya saja
    $img = str_replace('data:image/png;base64,', '', $img);
    
    // Mengganti karakter spasi dengan simbol '+' yang mungkin rusak saat proses pengiriman data URL
    $img = str_replace(' ', '+', $img);
    
    // Melakukan dekoding Base64 untuk mengubah string teks kembali menjadi data biner (format gambar asli)
    $data = base64_decode($img);

    /**
     * Membuat sumber daya gambar (image resource) baru di memori server dari string data biner yang sudah didekode.
     * Fungsi imagecreatefromstring() secara otomatis mendeteksi format gambar tersebut.
     */
    $source = imagecreatefromstring($data);
    
    // Memastikan bahwa proses pembuatan sumber daya gambar berhasil dan tidak ada data yang korup
    if ($source !== false) {
        
        /**
         * imagealphablending: Mengatur mode pencampuran warna agar elemen transparan dapat digabungkan dengan benar.
         * Ini penting agar filter vintage yang memiliki transparansi tidak terlihat rusak.
         */
        imagealphablending($source, true);
        
        /**
         * imagesavealpha: Menginstruksikan server agar menyimpan informasi transparansi (alpha channel) ke dalam file PNG.
         * Hal ini menjaga kualitas warna biru vintage agar tetap jernih dan tidak pudar.
         */
        imagesavealpha($source, true);

        // =========================================================================
        // AWAL - PENYESUAIAN PENGAMBILAN DATA BINGKAI DINAMIS DARI DATABASE MASTER
        // =========================================================================
        
        // Tetapkan ID Frame untuk Blue Vintage berdasarkan tabel master database Anda
        $id_frame = 2;

        // Ambil kode warna HEX secara dinamis berdasarkan id_frame dari tabel frames
        $query_frame = "SELECT kode_warna FROM frames WHERE id_frame = '$id_frame'";
        $result_frame = mysqli_query($koneksi, $query_frame);
        $data_frame = mysqli_fetch_assoc($result_frame);
        
        // KUNCI: Fallback ke Biru Muda murni (#ADD8E6) jika kosong, dijamin bebas dari unsur biru tua!
        $hex_warna = isset($data_frame['kode_warna']) && !empty($data_frame['kode_warna']) ? $data_frame['kode_warna'] : '#ADD8E6'; 

        // Konversi kode HEX (contoh: #ADD8E6) menjadi format RGB desimal untuk diolah oleh GD Library
        list($r, $g, $b) = sscanf($hex_warna, "#%02x%02x%02x");

        // Ambil dimensi dimensi lebar dan tinggi asli dari foto kamera mentah
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
             * Redirect atau mengarahkan user kembali ke halaman galeri dengan status sukses.
             * Parameter theme=blue dikirimkan agar galeri bisa memberikan respon visual bertema biru.
             */
            header("Location: galeri.php?status=success&theme=blue");
            
            // Menghentikan eksekusi script agar proses pengalihan halaman berjalan lancar
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
 * Jika akses dilakukan secara langsung tanpa data POST atau terjadi kegagalan sistem,
 * maka user akan dikembalikan ke halaman utama (index.php).
 */
header("Location: index.php");

// Mengakhiri proses script PHP
exit();
?>