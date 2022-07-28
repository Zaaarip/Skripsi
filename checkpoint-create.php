<?php 
include ("header.php");
include ("sidebar.php");
include ("nav.php");
include ("koneksi.php");
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$id_zona = "";
$nama_checkpoint = "";

$id_zona_err = "";
$nama_checkpoint_err = "";


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id_zona = trim($_POST["id_zona"]);
    $nama_checkpoint = trim($_POST["nama_checkpoint"]);


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

      $vars = parse_columns('checkpoint', $_POST);
      $stmt = $pdo->prepare("INSERT INTO checkpoint (id_zona,nama_checkpoint) VALUES (?,?)");

      if($stmt->execute([ $id_zona,$nama_checkpoint  ])) {
        include "phpqrcode/qrlib.php"; 
       /*create folder*/
       $tempdir="img-qrcode/";
       if (!file_exists($tempdir))
          mkdir($tempdir, 0755);
      $file_name=$nama_checkpoint.".png";   
      $file_path = $tempdir.$file_name;
      QRcode::png("proses_scan.php?nama_checkpoint=".$nama_checkpoint."", $file_path, "H", 6, 4);
      /* param (1)qrcontent,(2)filename,(3)errorcorrectionlevel,(4)pixelwidth,(5)margin */

      $stmt = null;
      echo "<script>
      location.replace('zona-index.php');
      </script>
      ";
  } else{
    echo "Something went wrong. Please try again later.";
}

}
?>

<!-- Begin Page Content -->
<div class="container-fluid">
  <h1 class="text-center h3 mb-0 text-gray-800"> <span class="font-weight-bold text-info "> Create Checkpoint </h1>    
      <div class="card-body">
        <div class="page-header clearfix">
            <a href="checkpoint-create.php" class="btn btn-success float-right">Add New Record</a>
            <a href="checkpoint-index.php" class="btn btn-info float-right mr-2">Reset View</a>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="form-group">
                <label>id_zona</label>
                <select class="form-control" id="id_zona" name="id_zona">
                    <?php
                    $sql = "SELECT *,id_zona FROM zona";
                    $result = mysqli_query($link, $sql);
                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        $duprow = $row;
                        unset($duprow["id_zona"]);
                        $value = implode(" | ", $duprow);
                        if ($row["id_zona"] == $id_zona){
                            echo '<option value="' . "$row[id_zona]" . '"selected="selected">' . "$value" . '</option>';
                        } else {
                            echo '<option value="' . "$row[id_zona]" . '">' . "$value" . '</option>';
                        }
                    }
                    ?>
                </select>
                <span class="form-text"><?php echo $id_zona_err; ?></span>
            </div>
            <div class="form-group">
                <label>nama_checkpoint</label>
                <input type="text" name="nama_checkpoint" maxlength="100"class="form-control" value="<?php echo $nama_checkpoint; ?>">
                <span class="form-text"><?php echo $nama_checkpoint_err; ?></span>
            </div>

            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="zona-index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php 
include("footer.php")
?>
