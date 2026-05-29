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
         * Ini memastikan hasil foto tetap jernih dan memiliki kualitas warna pink yang sesuai desain.
         */
        imagesavealpha($source, true);

        /**
         * PERBAIKAN & PENYESUAIAN DIREK DATABASE:
         * Menggunakan output buffering untuk menangkap data biner gambar langsung dari memori 
         * tanpa harus membuat atau menulis file fisik ke dalam folder uploads/ lagi.
         */
        ob_start(); // Membuka buffer memori internal system
        imagepng($source); // Mengalirkan data gambar PNG ke dalam buffer memori
        $gambar_biner = ob_get_clean(); // Mengambil isi biner gambar dari buffer lalu membersihkannya
        
        // Mengamankan data biner mentah gambar agar siap dimasukkan ke dalam query SQL
        $gambar_aman = mysqli_real_escape_string($koneksi, $gambar_biner);
        
        // Menentukan nama tema yang digunakan secara konsisten
        $tema = "Soft Pink";

        /**
         * Query INSERT digunakan untuk menjebloskan data user beserta fisik gambar murni (LONGBLOB) 
         * secara langsung ke tabel 'photos' di database db_photobooth Laragon.
         */
        $query = "INSERT INTO photos (nama_user, file_gambar, tema) VALUES ('$nama_user', '$gambar_aman', '$tema')";

        // Memastikan query sql berhasil dieksekusi ke database Laragon
        if (mysqli_query($koneksi, $query)) {
            
            // Menghapus resource gambar dari memori RAM server agar menghemat resource laptop bos
            imagedestroy($source);
            
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