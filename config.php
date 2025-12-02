<?php
$koneksi = new mysqli("localhost", "root", "", "db_siakadlogin");

// cek error
if ($koneksi->connect_errno) {
    echo "Gagal koneksi: " . $koneksi->connect_error;
}
?>

