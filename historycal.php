<?php
include ("header.php");
include ("sidebar.php");
include ("nav.php");
include ("koneksi.php");

// Include config file
require_once "config.php";
require_once "helpers.php";

//Get current URL and parameters for correct pagination
$protocol = $_SERVER['SERVER_PROTOCOL'];
$domain   = $_SERVER['HTTP_HOST'];
$script   = $_SERVER['SCRIPT_NAME'];
$parameters = $_GET ? $_SERVER['QUERY_STRING'] : "" ;
$protocol=strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https')
=== FALSE ? 'http' : 'https';
$currenturl = $protocol . '://' . $domain. $script . '?' . $parameters;

$ruteDetailId = $_GET['id'];
$getZona = "SELECT rute_details.*, checkpoint.nama_checkpoint, checkpoint.id_zona, zona.nama_zona FROM rute_details ".
    "JOIN checkpoint ON rute_details.id_checkpoint = checkpoint.id_checkpoint JOIN zona ON checkpoint.id_zona = ".
    "zona.id_zona WHERE rute_details.rute_detail_id = '".$ruteDetailId."' LIMIT 1";
$rGetZona = mysqli_query($link, $getZona);
$hGetZona = mysqli_fetch_assoc($rGetZona);
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="text-center h3 mb-0 text-gray-800">
        <span class="font-weight-bold text-info "> Data History Checkpoint <?php echo $hGetZona['nama_checkpoint']; ?> (Zona <?php echo $hGetZona['nama_zona']; ?>)</span>
    </h1>
    <div class="card mt-4">
        <div class="card-body">
            <div class="mb-2">
                <form action="historycal_upload.php" class="clear-fix mb-4" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Input gambar</label>
                        <input type="hidden" name="rute_detail_id" value="<?php echo $ruteDetailId; ?>" >
                        <input type="file" name="gambar" maxlength="100" class="form-control">
                        <textarea name="caption" class="form-control" cols="30" rows="2" placeholder="Isi dengan berita kejanggalan"></textarea>
                        <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
                        <input name="upload" type="submit" class="btn btn-primary float-right">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card my-4">
        <div class="card-body">


            <?php

            //Pagination
            if (isset($_GET['pageno'])) {
                $pageno = $_GET['pageno'];
            } else {
                $pageno = 1;
            }

            //$no_of_records_per_page is set on the index page. Default is 10.
            $offset = ($pageno-1) * $no_of_records_per_page;

            $total_pages_sql = "SELECT * FROM history_image WHERE rute_detail_id = '".$ruteDetailId."'";
            $result = mysqli_query($link, $total_pages_sql);
            //        $total_rows = mysqli_fetch_array($result)[0];
            $total_rows = mysqli_num_rows($result);
            $total_pages = ceil($total_rows / $no_of_records_per_page);

            //Column sorting on column name
            $orderBy = array('gambar');
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
            $sql = "SELECT * FROM history_image WHERE rute_detail_id = '".$ruteDetailId."'";
            $count_pages = "SELECT * FROM history_image WHERE rute_detail_id = '".$ruteDetailId."'";


            if(!empty($_GET['search'])) {
                $search = ($_GET['search']);
                $sql = "SELECT * FROM history_image
                    WHERE CONCAT_WS (id_history_patroli)
                    LIKE '%$search%'
                    AND rute_detail_id = $ruteDetailId
                    ORDER BY $order $sort
                    LIMIT $offset, $no_of_records_per_page";
                $count_pages = "SELECT * FROM history_image
                            WHERE CONCAT_WS (id_history_patroli)
                            LIKE '%$search%'
                            AND rute_detail_id = $ruteDetailId
                            ORDER BY $order $sort";
            }
            else {
                $search = "";
            }

            if($result = mysqli_query($link, $sql)){

                echo "<table id='example1' class='table table-striped'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th><a href=?search=$search&sort=&order=id_history_image&sort=$sort>id</th>";
                echo "<th><a href=?search=$search&sort=&order=rute_detail_id&sort=$sort>rute_detail_id</th>";
                echo "<th><a href=?search=$search&sort=&order=nama_gambar&sort=$sort>nama_gambar</th>";
                echo "<th><a href=?search=$search&sort=&order=caption&sort=$sort>caption</th>";
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
                        echo "<td>" . htmlspecialchars($row['rute_detail_id']) . "</td>";
                        echo "<td><a href='img-patroli/" . htmlspecialchars($row['nama_gambar']) . "' target='_blank'> Lihat Gambar </a></td>";
                        echo "<td>" . htmlspecialchars($row['caption']) . "</td>";
                        echo "<td>";
                        echo "<a href='img-patroli/". $row['gambar'] ."' title='".$row['caption']."' target='_blank' data-toggle='tooltip'><i class='far fa-eye'></i></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "<td colspan='5' align='center'>Belum ada data</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
            }

            // Close connection
            mysqli_close($link);
            ?>
        </div>
    </div>

    <?php
    include("footer.php")
    ?>
