<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

if (isset($_POST['upload'])) {
    $filename = $_FILES["uploadfile"]["name"]."-".date('m-d-Y_H:i:s');
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $folder = "./img-patroli/" . $filename;

    if (move_uploaded_file($tempname, $folder)) {
        $query  = "INSERT INTO history_image(rute_detail_id, gambar, caption) VALUES('".$_POST['rute_detail_id']."', '".$filename."', '".$_POST['caption']."')";
        if (mysqli_query($link, $query)) {
            echo '<script>alert("Data Disimpan");
                    location.replace("historycal.php?id='.$_POST['rute_detail_id'].'");
                  </script>';
        } else {
            echo '<script>alert("Data Gagal Disimpan ke database");
                    location.replace("historycal.php?id='.$_POST['rute_detail_id'].'");
                  </script>';
        }
    } else {
        echo '<script>alert("Gambar tidak dapat di move dari temporary folder");
                location.replace("historycal.php?id='.$_POST['rute_detail_id'].'");
              </script>';
    }
} else {
    echo '<script>alert("Form tidak terisi.");
            location.replace("historycal.php?id='.$_POST['rute_detail_id'].'");
          </script>';
}
?>