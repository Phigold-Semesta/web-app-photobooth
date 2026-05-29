<?php
/**
 * PinkyPromise Photobooth - Pusat Koneksi Database (Laragon Environment)
 * File ini berfungsi sebagai gerbang utama penghubung script PHP dengan MySQL pada Laragon.
 */

$host     = "localhost";
$username = "root";          // Default user MySQL di Laragon adalah root
$password = "";              // Default password MySQL di Laragon adalah kosong/tanpa password
$database = "db_photobooth"; // Nama database yang sudah kita buat di phpMyAdmin

// Mengaktifkan koneksi menggunakan ekstensi mysqli
$koneksi = mysqli_connect($host, $username, $password, $database);

// Validasi jika koneksi mengalami kegagalan
if (!$koneksi) {
    die("Koneksi ke database Laragon gagal: " . mysqli_connect_error());
}
?>