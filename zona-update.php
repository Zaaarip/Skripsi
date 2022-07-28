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
if(isset($_POST["id_zona"]) && !empty($_POST["id_zona"])){
    // Get hidden input value
    $id_zona = $_POST["id_zona"];

    $nama_zona = trim($_POST["nama_zona"]);
    

    // Prepare an update statement
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
        exit('Something weird happened');
    }

    $vars = parse_columns('zona', $_POST);
    $stmt = $pdo->prepare("UPDATE zona SET nama_zona=? WHERE id_zona=?");

    if(!$stmt->execute([ $nama_zona,$id_zona  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        echo "<script>
        location.replace('zona-index.php');
        </script>
        ";
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["id_zona"] = trim($_GET["id_zona"]);
    if(isset($_GET["id_zona"]) && !empty($_GET["id_zona"])){
        // Get URL parameter
        $id_zona =  trim($_GET["id_zona"]);

        // Prepare a select statement
        $sql = "SELECT * FROM zona WHERE id_zona = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $id_zona;

            // Bind variables to the prepared statement as parameters
            if (is_int($param_id)) $__vartype = "i";
            elseif (is_string($param_id)) $__vartype = "s";
            elseif (is_numeric($param_id)) $__vartype = "d";
            else $__vartype = "b"; // blob
            mysqli_stmt_bind_param($stmt, $__vartype, $param_id);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $nama_zona = htmlspecialchars($row["nama_zona"]);
                    

                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.<br>".$stmt->error;
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">
  <h1 class="text-center h3 mb-0 text-gray-800"> <span class="font-weight-bold text-info "> Update Zona </h1>    
      <div class="card-body">
        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

            <div class="form-group">
                <label>nama_zona</label>
                <input type="text" name="nama_zona" maxlength="100"class="form-control" value="<?php echo $nama_zona; ?>">
                <span class="form-text"><?php echo $nama_zona_err; ?></span>
            </div>

            <input type="hidden" name="id_zona" value="<?php echo $id_zona; ?>"/>
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="zona-index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php 
include("footer.php")
?>
