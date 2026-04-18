<?php
/**
 * PinkyPromise Photobooth - Delete Engine
 */

if (isset($_GET['file'])) {
    $fileName = basename($_GET['file']); // Gunakan basename untuk keamanan path traversal
    $filePath = "uploads/" . $fileName;

    // Cek apakah file benar-benar ada sebelum dihapus
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            // Berhasil hapus, balik ke galeri dengan status sukses
            header("Location: galeri.php?status=deleted");
            exit();
        }
    }
}

// Jika gagal atau akses langsung, balik ke galeri
header("Location: galeri.php?status=error");
exit();