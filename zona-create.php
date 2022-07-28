<?php 
include ("header.php");
include ("sidebar.php");
include ("nav.php");
include ("koneksi.php");
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$nama_zona = "";

$nama_zona_err = "";


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nama_zona = trim($_POST["nama_zona"]);
        

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

        $vars = parse_columns('zona', $_POST);
        $stmt = $pdo->prepare("INSERT INTO zona (nama_zona) VALUES (?)");

        if($stmt->execute([ $nama_zona  ])) {
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
  <h1 class="text-center h3 mb-0 text-gray-800"> <span class="font-weight-bold text-info "> Create Zona </h1>    
      <div class="card-body">
        <div class="page-header clearfix">
            <a href="zona-create.php" class="btn btn-success float-right">Add New Record</a>
            <a href="zona-index.php" class="btn btn-info float-right mr-2">Reset View</a>
        </div>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                                <label>nama_zona</label>
                                <input type="text" name="nama_zona" maxlength="100"class="form-control" value="<?php echo $nama_zona; ?>">
                                <span class="form-text"><?php echo $nama_zona_err; ?></span>
                            </div>

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="zona-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
</div>
</div>

<?php 
include("footer.php")
?>
