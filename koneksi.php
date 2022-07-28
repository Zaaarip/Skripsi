<?php
// konfigurasi database
$host = "localhost"; 
$user = "root"; 
$password = "";
$database = "skripsi_razi"; 

$koneksi = mysqli_connect($host, $user, $password, $database);
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
