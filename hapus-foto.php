<?php
/**
 * PinkyPromise Photobooth - Delete Engine
 * File ini berfungsi sebagai mesin pemroses penghapusan file gambar dari server.
 */

// Mengecek apakah ada parameter 'file' yang dikirimkan melalui URL (metode GET)
if (isset($_GET['file'])) {
    
    /**
     * Mengambil nama file dari parameter URL.
     * Fungsi basename() sangat penting untuk keamanan guna mencegah "Path Traversal",
     * yaitu upaya user nakal yang mencoba menghapus file di luar folder 'uploads'.
     */
    $fileName = basename($_GET['file']); 
    
    // Menentukan jalur lengkap (path) lokasi file yang akan dihapus di dalam server
    $filePath = "uploads/" . $fileName;

    /**
     * Melakukan validasi apakah file yang diminta memang benar-benar ada di folder uploads.
     * Ini mencegah error sistem jika mencoba menghapus file yang sudah tidak ada.
     */
    if (file_exists($filePath)) {
        
        /**
         * Fungsi unlink() adalah fungsi bawaan PHP untuk menghapus file secara permanen dari sistem penyimpanan.
         * Jika proses penghapusan berhasil, maka blok kode di bawahnya akan dijalankan.
         */
        if (unlink($filePath)) {
            
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
 * Jika parameter 'file' tidak ada, file tidak ditemukan, atau gagal dihapus,
 * maka user akan dilempar kembali ke galeri dengan status error.
 */
header("Location: galeri.php?status=error");

// Memastikan script berhenti sepenuhnya setelah perintah redirect
exit();