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
        $filename = "Strip_Mahogany_" . date("Ymd_His") . ".png";
        $filepath = "uploads/" . $filename;

        // Pastikan transparansi tidak mengganggu warna mahogany yang solid
        imagealphablending($source, false); 
        imagesavealpha($source, true);

        if (imagepng($source, $filepath)) {
            imagedestroy($source);
            header("Location: galeri.php?status=success&theme=mahogany");
            exit();
        }
    }
}
header("Location: index.php");
?>