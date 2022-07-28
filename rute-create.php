<?php 
include ("koneksi.php");
// Include config file
require_once "config.php";
require_once "helpers.php";

$sql1 = "SELECT * FROM zona";
$result1 = mysqli_query($link,$sql1);
$rute = "";
while($row = mysqli_fetch_array($result1)){
    $rute .=  $row['nama_zona'];
}

// Define variables and initialize with empty values
$sql = "SELECT * FROM user where level_user = 'satpam'";
$result = mysqli_query($link,$sql);
$wadah = array();
while($row = mysqli_fetch_array($result)){
    $rute = str_shuffle($rute);
    $sql2 = "SELECT * FROM jalur WHERE id_user = '".$row['id_user']."' AND status = 'aktif';";
    $result2 = mysqli_query($link,$sql2);
    while($row2 = mysqli_fetch_array($result2)){
        $data_rute ==  $row2['detail_rute'];
        while ($data_rute == $rute) {
            $rute =str_shuffle($rute);
        if (!empty($wadah)) {
            for ($i=0; $i < count($wadah) ; $i++) { 
                if ($wadah[$i] == $rute) {
                    $rute = str_shuffle($rute);
                }
            }
        }
    }
}
    $detail_rute = $rute;
    $id_user = $row['id_user'];


    $dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
    $options = [
          PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
      ];
      try {
          $pdo = new PDO($dsn, $db_user, $db_password, $options);
      } catch (Exception $e) {
          error_log($e->getMessage());
          exit('Something weird happened'); //something a user can understand
      }

      $vars = parse_columns('rute', $_POST);
      $stmt = $pdo->prepare("INSERT INTO rute (detail_rute,id_user,status) VALUES (?,?,'aktif')");

$sql3 = "UPDATE `rute` SET `status` = 'selesai' WHERE `rute`.`id_user` = '".$row['id_user']."' AND status = 'aktif';";
$result3 = mysqli_query($link,$sql3);
  if($stmt->execute([ $detail_rute,$id_user  ])) {
    $stmt = null;

} else{
    echo "Something went wrong. Please try again later.";
}
  }
      header("location: rute-index.php");


?>
