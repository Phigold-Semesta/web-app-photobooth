<?php
if (isset($_POST['image_data'])) {
    $img = $_POST['image_data'];
    $img = str_replace('data:image/png;base64,', '', $img);
    $data = base64_decode(str_replace(' ', '+', $img));

    $source = imagecreatefromstring($data);
    if ($source !== false) {
        $filename = "Strip_Mahogany_" . date("Ymd_His") . ".png"; // Sesuaikan nama
        $filepath = "uploads/" . $filename;
        imagesavealpha($source, true);
        if (imagepng($source, $filepath)) {
            imagedestroy($source);
            header("Location: galeri.php?status=success&theme=mahogany"); // Sesuaikan tema
            exit();
        }
    }
}
header("Location: index.php");
?>