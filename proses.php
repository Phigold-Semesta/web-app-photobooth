<?php
/**
 * SOWAN V2 - Anti-Pink Photo Engine
 * Memotong 4 bagian foto secara terpisah untuk membuang border pink lama.
 */

if (isset($_POST['image_data'])) {
    $img = $_POST['image_data'];
    $filter = $_POST['filter_used'] ?? 'soft';

    // 1. Dekode data Base64
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);

    // 2. TENTUKAN WARNA TEMA MURNI
    if ($filter == 'soft') {
        $r = 255; $g = 192; $b = 203; // Pink Rose (#FFC0CB)
    } elseif ($filter == 'vintage') {
        $r = 173; $g = 216; $b = 230; // Biru Muda (#ADD8E6)
    } elseif ($filter == 'bright') {
        $r = 78; $g = 42; $b = 30;    // Mahogany (#4E2A1E)
    } else {
        $r = 255; $g = 192; $b = 203;
    }

    $source = imagecreatefromstring($data);
    $origW = imagesx($source);
    $origH = imagesy($source);

    // 3. LOGIKA HARD CROP (Buang Garis Pink di Antara Foto)
    $singleHeight = $origH / 4;
    $cX = 65; // Potong kiri-kanan (Membuang sisa border pink kamera)
    $cY = 35; // Potong atas-bawah tiap segmen (Membuang garis pink horizontal)
    
    $cleanW = $origW - ($cX * 2);
    $cleanH = $singleHeight - ($cY * 2);

    // 4. BUAT KANVAS BARU DENGAN WARNA TEMA PENUH
    $pad = 40;  // Jarak pinggir strip
    $gap = 20;  // Jarak pemisah antar foto (Akan sewarna dengan tema)
    $finalW = $cleanW + ($pad * 2);
    $finalH = ($cleanH * 4) + ($gap * 3) + ($pad * 2) + 100;

    $finalImg = imagecreatetruecolor($finalW, $finalH);
    $themeColor = imagecolorallocate($finalImg, $r, $g, $b);
    
    // ISI SELURUH KANVAS DENGAN WARNA TEMA (Ini yang bikin pink hilang)
    imagefill($finalImg, 0, 0, $themeColor);

    // 5. SUSUN ULANG FOTO SATU PER SATU
    for ($i = 0; $i < 4; $i++) {
        $srcY = ($i * $singleHeight) + $cY;
        $destY = $pad + ($i * ($cleanH + $gap));
        
        imagecopyresampled(
            $finalImg, $source, 
            $pad, $destY, // Posisi di kanvas baru
            $cX, $srcY,    // Posisi potong di foto lama
            $cleanW, $cleanH, 
            $cleanW, $cleanH  
        );
    }

    // 6. LABEL BAWAH (Warna teks cerdas)
    // Jika Mahogany (gelap), teks putih. Selain itu abu gelap.
    $textCol = ($filter == 'bright') ? imagecolorallocate($finalImg, 255, 255, 255) : imagecolorallocate($finalImg, 60, 60, 60);
    
    $label = "💖 PinkyPromise Strip 💖";
    $fSize = 5;
    $tX = ($finalW / 2) - (strlen($label) * imagefontwidth($fSize) / 2);
    $tY = $finalH - 65;
    imagestring($finalImg, $fSize, $tX, $tY, $label, $textCol);

    // 7. SIMPAN KE FOLDER UPLOADS
    $folderPath = "uploads/";
    if (!file_exists($folderPath)) { mkdir($folderPath, 0777, true); }
    
    $fileName = 'Strip_' . $filter . '_' . date("Ymd_His") . '.png';
    $file = $folderPath . $fileName;

    if (imagepng($finalImg, $file)) {
        imagedestroy($source);
        imagedestroy($finalImg);
        header("Location: galeri.php");
        exit();
    }
}
?>