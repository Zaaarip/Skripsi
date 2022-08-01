<?php
include("header.php");
include("sidebar.php");
include("nav.php");
include("koneksi.php");

// Include config file
require_once "config.php";
require_once "helpers.php";

if (isset($_FILES['gambar'])) {
    $targetDir = 'img-patroli/';
    $fileName  = $targetDir . basename($_FILES["gambar"]["name"]);dr
    $fileType  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
}
?>