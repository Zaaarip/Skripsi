<?php
session_start();
if (empty($_SESSION)) {
 header("location:login.php");}
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="img/unugiri.ico" />
    <title>PENAZO</title>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <!--bootstrap-->
    <link rel="stylesheet" href="custom.css" type="text/css"/>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />

    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />
    <!-- QR Scanner-->
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="/html5-qrcode.min.js"></script>
</head>



