<?php
/**
 * PinkyPromise Photobooth - Save Process (Blue Vintage)
 * File ini berfungsi untuk mengolah data gambar mentah dari kamera dan menyimpannya sebagai file PNG.
 */

// Mengecek apakah ada data gambar (image_data) yang dikirimkan melalui metode POST dari halaman kamera
if (isset($_POST['image_data'])) {
    
    // Mengambil data string Base64 yang dikirimkan oleh sistem kamera JavaScript
    $img = $_POST['image_data'];
    
    // Menghilangkan header "data:image/png;base64," agar hanya menyisakan kode enkripsi gambarnya saja
    $img = str_replace('data:image/png;base64,', '', $img);
    
    // Mengganti karakter spasi dengan simbol '+' yang mungkin rusak saat proses pengiriman data URL
    $img = str_replace(' ', '+', $img);
    
    // Melakukan dekoding Base64 untuk mengubah string teks kembali menjadi data biner (format gambar asli)
    $data = base64_decode($img);

    /**
     * Membuat sumber daya gambar (image resource) baru di memori server dari string data biner yang sudah didekode.
     * Fungsi imagecreatefromstring() secara otomatis mendeteksi format gambar tersebut.
     */
    $source = imagecreatefromstring($data);
    
    // Memastikan bahwa proses pembuatan sumber daya gambar berhasil dan tidak ada data yang korup
    if ($source !== false) {
        
        /**
         * Menentukan nama file secara otomatis dengan awalan 'Strip_Blue_' diikuti oleh tanggal dan waktu pengambilan.
         * Format: TahunBulanTanggal_JamMenitDetik (Contoh: Strip_Blue_20260514_150000.png)
         */
        $filename = "Strip_Blue_" . date("Ymd_His") . ".png";
        
        // Menentukan lokasi folder penyimpanan yaitu di dalam folder 'uploads/'
        $filepath = "uploads/" . $filename;

        /**
         * imagealphablending: Mengatur mode pencampuran warna agar elemen transparan dapat digabungkan dengan benar.
         * Ini penting agar filter vintage yang memiliki transparansi tidak terlihat rusak.
         */
        imagealphablending($source, true);
        
        /**
         * imagesavealpha: Menginstruksikan server agar menyimpan informasi transparansi (alpha channel) ke dalam file PNG.
         * Hal ini menjaga kualitas warna biru vintage agar tetap jernih dan tidak pudar.
         */
        imagesavealpha($source, true);

        /**
         * Fungsi imagepng() digunakan untuk menulis/menyimpan sumber daya gambar dari memori ke dalam file fisik di folder uploads.
         * Jika berhasil disimpan, maka blok kode di bawahnya akan dijalankan.
         */
        if (imagepng($source, $filepath)) {
                
            /**
             * Redirect atau mengarahkan user kembali ke halaman galeri dengan status sukses.
             * Parameter theme=blue dikirimkan agar galeri bisa memberikan respon visual bertema biru.
             */
            header("Location: galeri.php?status=success&theme=blue");
            
            // Menghentikan eksekusi script agar proses pengalihan halaman berjalan lancar
            exit();
        }
    }
}

/**
 * Jika akses dilakukan secara langsung tanpa data POST atau terjadi kegagalan sistem,
 * maka user akan dikembalikan ke halaman utama (index.php).
 */
header("Location: index.php");

// Mengakhiri proses script PHP
exit();
?>