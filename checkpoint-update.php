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
if(isset($_POST["id_checkpoint"]) && !empty($_POST["id_checkpoint"])){
    // Get hidden input value
    $id_checkpoint = $_POST["id_checkpoint"];

    $id_zona = trim($_POST["id_zona"]);
        $nama_checkpoint = trim($_POST["nama_checkpoint"]);
        

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

    $vars = parse_columns('checkpoint', $_POST);
    $stmt = $pdo->prepare("UPDATE checkpoint SET id_zona=?,nama_checkpoint=? WHERE id_checkpoint=?");

    if(!$stmt->execute([ $id_zona,$nama_checkpoint,$id_checkpoint  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: checkpoint-read.php?id_checkpoint=$id_checkpoint");
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["id_checkpoint"] = trim($_GET["id_checkpoint"]);
    if(isset($_GET["id_checkpoint"]) && !empty($_GET["id_checkpoint"])){
        // Get URL parameter
        $id_checkpoint =  trim($_GET["id_checkpoint"]);

        // Prepare a select statement
        $sql = "SELECT * FROM checkpoint WHERE id_checkpoint = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $id_checkpoint;

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

                    $id_zona = htmlspecialchars($row["id_zona"]);
                    $nama_checkpoint = htmlspecialchars($row["nama_checkpoint"]);
                    

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
  <h1 class="text-center h3 mb-0 text-gray-800"> <span class="font-weight-bold text-info "> Data Barang </h1>    
      <div class="card-body">
        <div class="page-header clearfix">
            <a href="checkpoint-create.php" class="btn btn-success float-right">Add New Record</a>
            <a href="checkpoint-index.php" class="btn btn-info float-right mr-2">Reset View</a>
        </div>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

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

                        <input type="hidden" name="id_checkpoint" value="<?php echo $id_checkpoint; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="zona-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
</div>
</div>

<?php 
include("footer.php")
?>
