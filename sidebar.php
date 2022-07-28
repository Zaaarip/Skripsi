<?php
include "koneksi.php";
?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-left mt-4 mb-4 " href="dashboard.php">
                <div class="sidebar-brand-icon ">
                    <img src="">
                    <div class="sidebar-brand-text mx-0 text-left h4 font-weight-bolder">PENAZO</div>
                </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
                </li>
                <?php if ($_SESSION['level_user'] == 'kadiv' ){ ?>
                    
                <!-- Nav Item - Pages Collapse Menu -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="zona-index.php" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-file"></i>
                    <span>Data Zona</span>
                </a>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="rute-index.php" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-file"></i>
                    <span>Data Rute</span>
                </a>
            </li>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="user-index.php" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-file"></i>
                    <span>Data Satpam</span>
                </a>
            </li>
                <?php } ?>
            <li class="nav-item">
                <a class="nav-link collapsed" href="logout.php" data-target="#collapseTwo"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-file"></i>
                <span>Log Out</span>
            </a>

        </li>


    </ul>
        <!-- End of Sidebar -->