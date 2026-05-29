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
        $tema = "Blue Vintage";

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
             * Redirect atau mengarahkan user kembali ke halaman galeri dengan status sukses.
             * Parameter theme=blue dikirimkan agar galeri bisa memberikan respon visual bertema biru.
             */
            header("Location: galeri.php?status=success&theme=blue");
            
            // Menghentikan eksekusi script agar proses pengalihan halaman berjalan lancar
            exit();
        } else {
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