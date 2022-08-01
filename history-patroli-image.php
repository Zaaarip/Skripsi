<?php
include ("header.php");
include ("sidebar.php");
include ("nav.php");
include ("koneksi.php");
// Include config file
require_once "config.php";
require_once "helpers.php";

$idRute       = $_GET['rute'];
$idCheckPoint = $_GET['checkpoint'];
$queryLoadImg = "SELECT rute_details.id_rute, rute_details.id_checkpoint, checkpoint.nama_checkpoint FROM rute_details ".
                "JOIN checkpoint ON rute_details.id_checkpoint = checkpoint.id_checkpoint ".
                "WHERE rute_details.id_rute = '".$idRute."' AND rute_details.id_checkpoint = '".$idCheckPoint."' LIMIT 1";
$rQueryLoadImg = mysqli_query($link, $queryLoadImg);
$hQueryLoadImg = mysqli_fetch_array($rQueryLoadImg);
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="text-center h3 mb-0 text-gray-800"> <span class="font-weight-bold text-info "> History Patroli (<?php echo $hQueryLoadImg['nama_checkpoint']; ?>) </h1>
    <div class="card my-4">
        <div class="card-body">

            <?php
            // Include config file
            require_once "config.php";
            require_once "helpers.php";

            //Get current URL and parameters for correct pagination
            $protocol = $_SERVER['SERVER_PROTOCOL'];
            $domain     = $_SERVER['HTTP_HOST'];
            $script   = $_SERVER['SCRIPT_NAME'];
            $parameters   = $_GET ? $_SERVER['QUERY_STRING'] : "" ;
            $protocol=strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https')
            === FALSE ? 'http' : 'https';
            $currenturl = $protocol . '://' . $domain. $script . '?' . $parameters;

            //Pagination
            if (isset($_GET['pageno'])) {
                $pageno = $_GET['pageno'];
            } else {
                $pageno = 1;
            }

            //$no_of_records_per_page is set on the index page. Default is 10.
            $offset = ($pageno-1) * $no_of_records_per_page;

            $total_pages_sql = "SELECT COUNT(*) FROM history_image WHERE rute_detail_id = '".$hQueryLoadImg['rute_detail_id']."'";
            $result = mysqli_query($link,$total_pages_sql);
            $total_rows = mysqli_fetch_array($result)[0];
            $total_pages = ceil($total_rows / $no_of_records_per_page);

            //Column sorting on column name
            $orderBy = array('id_history_image');
            $order = 'id_history_image';
            if (isset($_GET['order']) && in_array($_GET['order'], $orderBy)) {
                $order = $_GET['order'];
            }

            //Column sort order
            $sortBy = array('asc', 'desc'); $sort = 'desc';
            if (isset($_GET['sort']) && in_array($_GET['sort'], $sortBy)) {
                if($_GET['sort']=='asc') {
                    $sort='desc';
                }
                else {
                    $sort='asc';
                }
            }

            // Attempt select query execution
            $sql = "SELECT * FROM history_image WHERE rute_detail_id = '".$hQueryLoadImg['rute_detail_id']."'";
            $count_pages = "SELECT * FROM history_image WHERE rute_detail_id = '".$hQueryLoadImg['rute_detail_id']."'";

            if($result = mysqli_query($link, $sql)){
                echo "<table id='example1' class='table table-striped'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>id_history_image</th>";
                echo "<th>Nama Gambar</th>";
                echo "<th>Caption</th>";
                echo "<th>Action</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                if(mysqli_num_rows($result) > 0){
                    if ($result_count = mysqli_query($link, $count_pages)) {
                        $total_pages = ceil(mysqli_num_rows($result_count) / $no_of_records_per_page);
                    }
                    $number_of_results = mysqli_num_rows($result_count);
                    echo " " . $number_of_results . " results - Page " . $pageno . " of " . $total_pages;
                    while($row = mysqli_fetch_array($result)){
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id_history_image']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['gambar']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['caption']) . "</td>";
                        echo "<td><a href='img-patroli/" . $row['gambar'] . "' target='_blank'><i class='fa fa-eye'></i></a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' align='center'>Belum ada gambar yang di upload</td></tr>";
                }
                echo "</tbody>";
                echo "</table>";
            }

            // Close connection
            mysqli_close($link);
            ?>
        </div>
    </div>
</div>

<?php
include("footer.php")
?>
