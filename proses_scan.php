<?php
// Include config file
require_once "config.php";
require_once "helpers.php";
require_once "header.php";
$zona = "";
$nama_checkpoint = $_GET['nama_checkpoint'];

$getDataCheckpoint = "SELECT checkpoint.*, zona.id_zona, zona.nama_zona FROM checkpoint JOIN zona ON checkpoint.id_zona = zona.id_zona WHERE checkpoint.nama_checkpoint = '".$nama_checkpoint."'";
$resultGetDataCP   = mysqli_query($link, $getDataCheckpoint);
$rowDataCP         = mysqli_fetch_assoc($resultGetDataCP);

$dataRuteDetail = "SELECT rute_details.*, checkpoint.nama_checkpoint, checkpoint.id_checkpoint FROM rute_details JOIN checkpoint ON ".
    "rute_details.id_checkpoint = checkpoint.id_checkpoint ".
    "WHERE checkpoint.nama_checkpoint = '".$nama_checkpoint."' ".
    "AND rute_details.id_user = '".$_SESSION['id']."' AND rute_details.scanned = '0' LIMIT 1";
$rDataRuteDetail = mysqli_query($link,$dataRuteDetail);
$hDataRuteDetail = mysqli_fetch_assoc($rDataRuteDetail);

if (mysqli_num_rows($rDataRuteDetail) < 1) {
    echo "<script>
  alert('Data Sudah Ada');
  location.replace('dashboard.php');
  </script>
  ";
} else {
    // check zona teratas
    $query1 = "SELECT * FROM rute_details WHERE id_user = '".$_SESSION['id']."' AND scanned = '0' LIMIT 1";
    $rQuery1 = mysqli_query($link, $query1);
    $hQuery1 = mysqli_fetch_assoc($rQuery1);

// apabila zona sebelumnya belum terselesaikan
    if ($hQuery1['nama_zona'] != $rowDataCP['nama_zona']) {
        echo "<script>
            alert('Belum semua checkpoint di scan/periksa');
            location.replace('dashboard.php');
            </script>
        ";
    } else {
        $updateRuteDetail = "UPDATE rute_details SET scanned = '1' WHERE rute_detail_id = '".$hDataRuteDetail['rute_detail_id']."'";
        if ($rUpdateRuteDetail = mysqli_query($link, $updateRuteDetail)) {
            $query3 = "INSERT INTO `history_patroli` (`id_history`, `id_user`, `id_rute`, `id_checkpoint`) VALUES (NULL, '"
                .$_SESSION['id']."', '".$hDataRuteDetail['id_rute']."', '".$hDataRuteDetail['id_checkpoint']."')";
            if (mysqli_query($link, $query3)) {
                $checkJumlah = "SELECT * FROM rute_details WHERE id_user = '".$_SESSION['id']."' AND scanned = '0'";
                $rCheckJumlah = mysqli_query($link, $checkJumlah);

                if (mysqli_num_rows($rCheckJumlah) > 0) {
                    echo "mohon tunggu 1";
                    echo '<script>alert("Data Disimpan");location.replace("historycal.php?id='.$hDataRuteDetail['rute_detail_id'].'");</script>';
                } else {
                    $getRute = "SELECT * FROM rute WHERE id_user = '".$_SESSION['id']."' AND status = 'aktif' LIMIT 1";
                    $rGetRute = mysqli_query($link, $getRute);
                    $hGetRute = mysqli_fetch_assoc($rGetRute);

                    //update rute
                    $updateRute = "UPDATE rute SET status = 'selesai' WHERE id_rute = '".$hGetRute['id_rute']."'";
                    mysqli_query($link, $updateRute);
                    echo "mohon tunggu";
                    echo "<script>alert('Data Disimpan');location.replace('dashboard.php');</script>";
                }
            } else {
                echo "<script>
                alert('Data GAGAL Disimpan');
                location.replace('dashboard.php');
                </script>
                ";
            }
        }
    }
}


?>