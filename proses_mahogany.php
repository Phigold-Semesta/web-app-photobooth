<?php
/**
 * PinkyPromise Photobooth - Save Process (Mahogany Luxury)
 * File ini merupakan mesin pemroses di sisi server (backend) yang bertugas
 * merubah data jepretan kamera menjadi file fisik PNG dengan nuansa Mahogany yang mewah.
 */

// Mengecek apakah request yang masuk membawa data 'image_data' melalui metode POST
if (isset($_POST['image_data'])) {
    
    // Mengambil data gambar dalam format string Base64 yang dikirimkan oleh browser
    $img = $_POST['image_data'];
    
    // Menghapus label format data "data:image/png;base64," agar hanya menyisakan kode enkripsi gambarnya
    $img = str_replace('data:image/png;base64,', '', $img);
    
    // Memperbaiki karakter spasi menjadi tanda '+' untuk memastikan data Base64 kembali utuh (tidak korup)
    $img = str_replace(' ', '+', $img);
    
    // Mengubah string Base64 yang tadinya berupa teks kembali menjadi data biner mentah sebuah gambar
    $data = base64_decode($img);

    /**
     * Membentuk objek sumber daya gambar (image resource) menggunakan library GD PHP
     * berdasarkan data biner yang sudah didekode sebelumnya.
     */
    $source = imagecreatefromstring($data);
    
    // Memvalidasi apakah proses pembuatan sumber daya gambar di memori server berhasil
    if ($source !== false) {
        
        // Membuat nama file unik dengan prefix 'Strip_Mahogany_' dan diikuti timestamp waktu saat ini
        $filename = "Strip_Mahogany_" . date("Ymd_His") . ".png";
        
        // Menentukan direktori tujuan penyimpanan di folder 'uploads/'
        $filepath = "uploads/" . $filename;

        /**
         * Konfigurasi Warna & Transparansi:
         * imagealphablending set ke 'false' agar warna mahogany yang solid tidak tercampur 
         * dengan warna background sebelumnya saat proses penyimpanan dilakukan.
         */
        imagealphablending($source, false); 
        
        /**
         * imagesavealpha set ke 'true' untuk memastikan informasi transparansi (jika ada) 
         * tetap tersimpan dengan baik di dalam file format PNG.
         */
        imagesavealpha($source, true);

        /**
         * Mengeksekusi penyimpanan gambar dari memori server ke dalam folder fisik (harddisk).
         * Jika proses tulis file berhasil, maka blok kode sukses akan dijalankan.
         */
        if (imagepng($source, $filepath)) {
            
            
            /**
             * Mengarahkan user kembali ke galeri dengan status sukses dan memberikan 
             * parameter tema 'mahogany' untuk pengaturan tampilan feedback di galeri.
             */
            header("Location: galeri.php?status=success&theme=mahogany");
            
            // Menghentikan eksekusi script PHP agar proses pengalihan (redirect) tidak terganggu
            exit();
        }
    }
}

/**
 * Jika script diakses secara tidak sah atau tanpa mengirim data gambar,
 * maka otomatis akan dilempar kembali ke halaman beranda (index.php).
 */
header("Location: index.php");

// Memastikan script berhenti sepenuhnya
exit();
?>