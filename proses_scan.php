<?php 
                    // Include config file
require_once "config.php";
require_once "helpers.php";
require_once "header.php";
$zona = "";
$nama_checkpoint = $_GET['nama_checkpoint'];

$sql = "SELECT * FROM history_patroli INNER JOIN checkpoint ON history_patroli.id_checkpoint = checkpoint.id_checkpoint INNER JOIN zona ON checkpoint.id_zona = zona.id_zona INNER JOIN rute ON rute.id_rute = history_patroli.id_rute WHERE checkpoint.nama_checkpoint = '".$nama_checkpoint."' AND rute.status = 'aktif' AND history_patroli.id_user = '".$_SESSION['id']."'";
$result = mysqli_query($link,$sql) or die(mysqli_error($link));
while ($row1 = mysqli_fetch_array($result)) {
  $rute = $row1['id_rute'];
}
$baris = mysqli_num_rows($result);
if ($baris>0) {
  echo "<script>
  alert('Data Sudah Ada');
  location.replace('dashboard.php');
  </script>
  ";
}
$sql1 = "SELECT * FROM checkpoint INNER JOIN zona ON checkpoint.id_zona = zona.id_zona WHERE checkpoint.nama_checkpoint = '".$nama_checkpoint."' GROUP BY zona.id_zona" ;
$result1 = mysqli_query($link,$sql1) or die(mysqli_error($link));
$hasil = mysqli_fetch_assoc($result1);
$sql2 = "SELECT * FROM rute WHERE id_user = '".$_SESSION['id']."' AND status = 'aktif'";
$result2 = mysqli_query($link,$sql2) or die(mysqli_error($link));
while($row = mysqli_fetch_array($result2)){
  $rute = $row['detail_rute'];
  $rute1 = $row['id_rute'];
}
$array_rute =str_split($rute);
// var_dump($array_rute);
for ($i=0; $i <count($array_rute) ; $i++) { 
  $sql3 = "SELECT * FROM history_patroli INNER JOIN checkpoint ON history_patroli.id_checkpoint = checkpoint.id_checkpoint INNER JOIN zona on zona.id_zona = checkpoint.id_zona WHERE zona.nama_zona = '".$array_rute[$i]."'";
  $zona = $array_rute[$i];
  $result3 = mysqli_query($link, $sql3) or die(mysqli_error($link));
  $baris1 = mysqli_num_rows($result3);
  $sql4 = "SELECT * FROM checkpoint INNER JOIN zona on zona.id_zona = checkpoint.id_zona WHERE zona.nama_zona = '".$zona."'";
  $result4 = mysqli_query($link, $sql4) or die(mysqli_error($link));
  $baris2 = mysqli_num_rows($result4);
  $sql7 = "SELECT * FROM zona WHERE nama_zona = '".$array_rute[$i]."'";
  $result7 = mysqli_query($link,$sql7) or die(mysqli_error($link));
  $hasil1 = mysqli_fetch_array($result7);
  if ($baris1 != $baris2 AND $zona == $hasil1['nama_zona']) {
    $sql5 = "SELECT * FROM checkpoint INNER JOIN zona ON checkpoint.id_zona = zona.id_zona where checkpoint.id_checkpoint = '".$hasil['id_checkpoint']."'";
    $result5 = mysqli_query($link,$sql5);
    $baris3 = mysqli_num_rows($result5);
    if ($baris3>0) {
      echo $hasil['id_zona'];
      echo "<br>";
      echo $hasil['id_checkpoint'];
      echo "<br>";
      echo $_SESSION['id'];
      echo "<br>";
      $sql6 = "INSERT INTO `history_patroli` (`id_history`, `id_user`, `id_rute`, `id_checkpoint`) VALUES (NULL, '".$_SESSION['id']."', '".$rute1."', '".$hasil['id_checkpoint']."')";
        $result6 = mysqli_query($link,$sql6) or die(mysqli_error($link));
        echo "<script>
        alert('Data Disimpan');
        location.replace('dashboard.php');
        </script>
        ";
      }else{

        echo "<script>
        alert('Data Checkpoint Tidak Sesuai');
        location.href('dashboard.php');
        </script>
        ";
      }
      break;
    }
  }
  // var_dump($array_rute);
?>