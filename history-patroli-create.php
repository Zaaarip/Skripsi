<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$id_user = "";
$id_rute = "";
$waktu = "";

$id_user_err = "";
$id_rute_err = "";
$waktu_err = "";


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
        $id_user = trim($_POST["id_user"]);
		$id_rute = trim($_POST["id_rute"]);
		$waktu = trim($_POST["waktu"]);
		

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

        $vars = parse_columns('history_patroli', $_POST);
        $stmt = $pdo->prepare("INSERT INTO history_patroli (id_user,id_rute,waktu) VALUES (?,?,?)");

        if($stmt->execute([ $id_user,$id_rute,$waktu  ])) {
                $stmt = null;
                header("location: history_patroli-index.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

}
?>
