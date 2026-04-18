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
        // Nama file menggunakan prefix Pink
        $filename = "Strip_Pink_" . date("Ymd_His") . ".png";
        $filepath = "uploads/" . $filename;

        // Mengatur agar transparansi dan warna pink tetap akurat
        imagealphablending($source, true);
        imagesavealpha($source, true);

        if (imagepng($source, $filepath)) {
            imagedestroy($source);
            // Redirect ke galeri dengan parameter sukses bertema pink
            header("Location: galeri.php?status=success&theme=pink");
            exit();
        }
    }
}

// Kembali ke beranda jika akses ilegal
header("Location: index.php");
?>