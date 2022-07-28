<?php
// mengaktifkan session php
session_start();
include "koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];


$data = mysqli_query($koneksi, "SELECT * FROM user where username='$username' and password=MD5('$password')" );
// menghitung jumlah data yang ditemukan
$cek = mysqli_num_rows($data);

if ($cek > 0) {
    $cek = mysqli_fetch_assoc($data);
        $_SESSION['id'] =$cek['id_user'];
        $_SESSION['username'] =$cek['username'];
        $_SESSION['nama_user'] =$cek['nama_user'];
        $_SESSION['level_user'] =$cek['level_user'];
        $_SESSION['status'] = "login";
        header("location:dashboard.php?pesan=sukses");
} else{
	header("location:login.php?pesan=gagal");
}

// $query = mysqli_query($koneksi, "SELECT full_name from user where id_user=''");
// $nama = mysqli_fetch_array($query);

