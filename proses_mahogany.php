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
        $tema = "Mahogany Luxury";

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
             * Mengarahkan user kembali ke galeri dengan status sukses dan memberikan 
             * parameter tema 'mahogany' untuk pengaturan tampilan feedback di galeri.
             */
            header("Location: galeri.php?status=success&theme=mahogany");
            
            // Menghentikan eksekusi script PHP agar proses pengalihan (redirect) tidak terganggu
            exit();
        } else {
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