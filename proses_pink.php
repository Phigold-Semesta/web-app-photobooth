<?php
/**
 * PinkyPromise Photobooth - Save Process (Soft Pink)
 * File ini berfungsi sebagai backend processor untuk menerima data gambar dari kamera,
 * mengonversinya, dan menyimpannya ke dalam server dengan identitas tema Soft Pink.
 */

// Mengecek apakah data gambar (image_data) telah dikirimkan melalui metode POST dari form kamera
if (isset($_POST['image_data'])) {
    
    // Mengambil string data gambar dalam format Base64 yang dikirimkan oleh sistem JavaScript
    $img = $_POST['image_data'];
    
    // Membersihkan string: Menghapus bagian header data URI "data:image/png;base64,"
    $img = str_replace('data:image/png;base64,', '', $img);
    
    // Membersihkan string: Mengganti spasi kembali menjadi karakter '+' agar encoding Base64 tetap valid
    $img = str_replace(' ', '+', $img);
    
    // Melakukan proses dekode (pengubahan) dari string teks Base64 menjadi data biner gambar asli
    $data = base64_decode($img);

    /**
     * Membuat objek sumber daya gambar (image resource) di dalam memori server 
     * menggunakan library GD PHP berdasarkan data biner yang sudah didekode.
     */
    $source = imagecreatefromstring($data);
    
    // Memastikan bahwa sumber daya gambar berhasil dibuat dan data tidak mengalami kerusakan
    if ($source !== false) {
        
        /**
         * Menentukan nama file unik dengan awalan 'Strip_Pink_' ditambah tanggal dan waktu saat ini.
         * Contoh hasil: Strip_Pink_20260514_150530.png
         */
        $filename = "Strip_Pink_" . date("Ymd_His") . ".png";
        
        // Menetapkan jalur lokasi penyimpanan file ke dalam direktori folder 'uploads/'
        $filepath = "uploads/" . $filename;

        /**
         * imagealphablending: Mengaktifkan mode pencampuran warna. 
         * Sangat penting agar warna pink yang lembut dan elemen transparan menyatu secara akurat.
         */
        imagealphablending($source, true);
        
        /**
         * imagesavealpha: Menginstruksikan server untuk ikut menyimpan informasi transparansi (alpha channel).
         * Ini memastikan hasil foto tetap jernih dan memiliki kualitas warna pink yang sesuai desain.
         */
        imagesavealpha($source, true);

        /**
         * Mengeksekusi perintah untuk menuliskan/menyimpan data gambar dari memori ke file fisik di server.
         * Jika proses penyimpanan ke folder 'uploads/' berhasil, maka instruksi di dalam IF akan dijalankan.
         */
        if (imagepng($source, $filepath)) {
            

            
            /**
             * Mengalihkan halaman kembali ke galeri.php dengan membawa informasi status sukses.
             * Parameter theme=pink digunakan untuk memicu respon visual bertema pink di halaman galeri.
             */
            header("Location: galeri.php?status=success&theme=pink");
            
            // Mengakhiri seluruh proses script untuk memastikan pengalihan halaman (redirect) segera dieksekusi
            exit();
        }
    }
}

/**
 * JIKA TERJADI AKSES ILEGAL:
 * Jika file ini diakses tanpa data POST (misal diakses langsung via URL), 
 * maka sistem akan otomatis melempar user kembali ke halaman beranda.
 */
header("Location: index.php");

// Menghentikan script sepenuhnya
exit();
?>