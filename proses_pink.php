<?php
/**
 * PinkyPromise Photobooth - Save Process (Soft Pink)
 */

if (isset($_POST['image_data'])) {
    $img = $_POST['image_data'];
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);

    $source = imagecreatefromstring($data);
    if ($source !== false) {
        // Nama file menggunakan prefix Pink untuk mempermudah manajemen galeri
        $filename = "Strip_Pink_" . date("Ymd_His") . ".png";
        $filepath = "uploads/" . $filename;

        // Pertahankan kualitas transparansi dan warna pink yang lembut
        imagealphablending($source, true);
        imagesavealpha($source, true);

        if (imagepng($source, $filepath)) {
            imagedestroy($source);
            // Redirect ke galeri dengan status sukses dan tema pink
            header("Location: galeri.php?status=success&theme=pink");
            exit();
        }
    }
}

// Jika gagal atau akses langsung, kembali ke beranda photobooth
header("Location: index.php");
?>