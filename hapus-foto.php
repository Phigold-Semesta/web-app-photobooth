<?php
/**
 * PinkyPromise Photobooth - Delete Engine
 * File ini berfungsi sebagai mesin pemroses penghapusan file gambar dari server.
 */

// Menyisipkan file koneksi database Laragon pusat untuk memproses penghapusan data
include 'includes/koneksi.php';

// Mengecek apakah ada parameter 'id' yang dikirimkan melalui URL (metode GET)
if (isset($_GET['id'])) {
    
    /**
     * Mengambil ID data dari parameter URL.
     * Fungsi mysqli_real_escape_string() sangat penting untuk keamanan guna mencegah "SQL Injection",
     * yaitu upaya user nakal yang mencoba merusak atau memanipulasi struktur query database.
     */
    $idUser = mysqli_real_escape_string($koneksi, $_GET['id']); 
    
    /**
     * Melakukan validasi mengecek apakah data yang diminta memang benar-benar ada di database.
     * Ini mencegah error sistem jika mencoba menghapus data yang sudah tidak ada.
     */
    $checkQuery = "SELECT id_user FROM photos WHERE id_user = '$idUser'";
    $checkResult = mysqli_query($koneksi, $checkQuery);

    if ($checkResult && mysqli_num_rows($checkResult) > 0) {
        
        /**
         * Perintah DELETE FROM adalah fungsi bawaan SQL untuk menghapus baris data secara permanen dari sistem penyimpanan tabel database.
         * Jika proses penghapusan berhasil, maka blok kode di bawahnya akan dijalankan.
         */
        $queryDelete = "DELETE FROM photos WHERE id_user = '$idUser'";
        
        if (mysqli_query($koneksi, $queryDelete)) {
            
            /**
             * Jika berhasil dihapus, arahkan kembali (redirect) user ke halaman galeri.php.
             * Disertai parameter status=deleted agar halaman galeri bisa menampilkan notifikasi sukses.
             */
            header("Location: galeri.php?status=deleted");
            
            // Menghentikan seluruh eksekusi script agar proses redirect berjalan sempurna
            exit();
        }
    }
}

/**
 * JIKA TERJADI KEGAGALAN:
 * Jika parameter 'id' tidak ada, data tidak ditemukan, atau gagal dihapus,
 * maka user akan dilempar kembali ke galeri dengan status error.
 */
header("Location: galeri.php?status=error");

// Memastikan script berhenti sepenuhnya setelah perintah redirect
exit();
?>