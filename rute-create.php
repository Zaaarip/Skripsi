<?php 
include ("koneksi.php");
// Include config file
require_once "config.php";
require_once "helpers.php";

$sql1 = "SELECT * FROM zona";
$result1 = mysqli_query($link,$sql1);
$rute = "";
$shuffleRute2 = "";
$shuffleRute3 = "";
// Revisian: Menampung zona kedalam array
$arrRute = array();
$arrIndex = 0;
while($row = mysqli_fetch_array($result1)){
    $arrRute[$arrIndex] = $row['nama_zona'];
    $arrIndex++;
}
$arrIndex = 0;

// Define variables and initialize with empty values
$sql = "SELECT * FROM user where level_user = 'satpam'";
$result = mysqli_query($link,$sql);
$wadah = array();

$updateRD  = "UPDATE rute_details SET scanned = '1' WHERE scanned = '0'";
$rUpdateRD = mysqli_query($link,$updateRD);

while($row = mysqli_fetch_array($result)){
    shuffle($arrRute);
    foreach($arrRute as $item) {
        $rute .= $item;
    }
    $sql2 = "SELECT * FROM rute WHERE id_user = '".$row['id_user']."' AND status = 'aktif';";
    $result2 = mysqli_query($link,$sql2);
    while($row2 = mysqli_fetch_array($result2)){
        $data_rute ==  $row2['detail_rute'];
        while ($data_rute == $rute) {
            shuffle($arrRute);
            foreach($arrRute as $item) {
                $rute .= $item;
            }
            if (!empty($wadah)) {
                for ($i=0; $i < count($wadah) ; $i++) {
                    if ($wadah[$i] == $rute) {
                        shuffle($arrRute);
                        foreach ($arrRute as $item) {
                            $rute .= $item;
                        }
                    }
                }
            }
        }
    }
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
    if($stmt->execute([ $rute, $id_user  ])) {
        // ambil id terakhir di insert pada table rute
        $lastInsertId = $pdo->lastInsertId();
        // insert ke rute details untuk jalur rute patroli
        foreach ($arrRute as $item) {
            $loadCheckPoint = "SELECT checkpoint.*, zona.nama_zona FROM checkpoint JOIN zona ON checkpoint.id_zona = zona.id_zona WHERE zona.nama_zona = '".$item."' ORDER BY checkpoint.id_checkpoint ASC";
            $checkPointCollection = mysqli_query($link, $loadCheckPoint);
            while($dataCP = mysqli_fetch_assoc($checkPointCollection)) {
                $query = "INSERT INTO rute_details(id_rute, nama_zona, id_checkpoint, id_user, scanned) VALUES('".$lastInsertId."', '".$dataCP['nama_zona']."', '".$dataCP['id_checkpoint']."', '".$id_user."', '0')";
                mysqli_query($link, $query);
            }
        }
        $stmt = null;
        $rute = "";
    } else{
        echo "Something went wrong. Please try again later.";
    }
  }
      header("location: rute-index.php");


?>
