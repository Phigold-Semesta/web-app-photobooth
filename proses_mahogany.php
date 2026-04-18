<?php
/**
 * PinkyPromise Photobooth - Save Process (Mahogany Luxury)
 */

if (isset($_POST['image_data'])) {
    $img = $_POST['image_data'];
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);

    $source = imagecreatefromstring($data);
    if ($source !== false) {
        // Nama file menggunakan prefix Mahogany (Sesuai value di index.php)
        $filename = "Strip_Mahogany_" . date("Ymd_His") . ".png";
        $filepath = "uploads/" . $filename;

        // Penting: Mahogany butuh akurasi warna yang berani (Bold)
        imagealphablending($source, true);
        imagesavealpha($source, true);

        if (imagepng($source, $filepath)) {
            imagedestroy($source);
            // Redirect ke galeri dengan tema mahogany
            header("Location: galeri.php?status=success&theme=mahogany");
            exit();
        }
    }
}

header("Location: index.php");
?>