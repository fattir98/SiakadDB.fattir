<?php
// Konfigurasi database
$host = "localhost";       // nama server
$user = "root";            // username default MySQL
$pass = "";                // password (kosong kalau pakai Laragon/XAMPP default)
$db   = "db_siakadlogin";       // nama database kamu

// Membuat koneksi
$koneksi = new mysqli($host, $user, $pass, $db);

// Mengecek koneksi
if ($koneksi->connect_error) {
    die("<div style='color:red;'>Koneksi gagal: " . $koneksi->connect_error . "</div>");
}
?>
