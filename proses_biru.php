<?php
/**
 * PinkyPromise Photobooth - Save Process (Blue Vintage)
 */

if (isset($_POST['image_data'])) {
    $img = $_POST['image_data'];
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);

    $source = imagecreatefromstring($data);
    if ($source !== false) {
        // Nama file menggunakan prefix Blue
        $filename = "Strip_Blue_" . date("Ymd_His") . ".png";
        $filepath = "uploads/" . $filename;

        // Penting: Memastikan warna biru vintage tidak pudar saat di-save
        imagealphablending($source, true);
        imagesavealpha($source, true);

        if (imagepng($source, $filepath)) {
            imagedestroy($source);
            // Redirect ke galeri dengan parameter sukses bertema blue
            header("Location: galeri.php?status=success&theme=blue");
            exit();
        }
    }
}

header("Location: index.php");
?>