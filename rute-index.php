<?php 
include ("header.php");
include ("sidebar.php");
include ("nav.php");
include ("koneksi.php");

?>

<!-- Begin Page Content -->
<div class="container-fluid">
  <h1 class="text-center h3 mb-0 text-gray-800"> <span class="font-weight-bold text-info "> Data Rute </h1>    
      <div class="card-body">
        <div class="page-header clearfix">
            <a href="rute-create.php" class="btn btn-success float-right">Add New Record</a>
            <a href="rute-index.php?id_zona=<?php echo $_GET['id_zona']; ?>" class="btn btn-info float-right mr-2">Reset View</a>
        </div>

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

        $total_pages_sql = "SELECT COUNT(*) FROM rute";
        $result = mysqli_query($link,$total_pages_sql);
        $total_rows = mysqli_fetch_array($result)[0];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

                    //Column sorting on column name
        $orderBy = array('detail_rute', 'id_user');
        $order = 'id_rute';
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
    $sql = "SELECT *, (SELECT TIMEDIFF(MAX(hp1.waktu),MIN(hp1.waktu)) AS lama FROM history_patroli hp1 WHERE hp1.id_rute = hp.id_rute GROUP BY hp1.id_rute) AS durasi FROM history_patroli hp RIGHT JOIN rute r ON r.id_rute = hp.id_rute INNER JOIN user u ON u.id_user = r.id_user GROUP BY r.id_rute;";
    $count_pages = "SELECT * FROM rute";


    if(!empty($_GET['search'])) {
        $search = ($_GET['search']);
        $sql = "SELECT * FROM rute
        WHERE CONCAT_WS (detail_rute,id_user)
        LIKE '%$search%'
        ORDER BY $order $sort
        LIMIT $offset, $no_of_records_per_page";
        $count_pages = "SELECT * FROM rute
        WHERE CONCAT_WS (detail_rute,id_user)
        LIKE '%$search%'
        ORDER BY $order $sort";
    }
    else {
        $search = "";
    }

    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            if ($result_count = mysqli_query($link, $count_pages)) {
               $total_pages = ceil(mysqli_num_rows($result_count) / $no_of_records_per_page);
           }
           $number_of_results = mysqli_num_rows($result_count);
           echo " " . $number_of_results . " results - Page " . $pageno . " of " . $total_pages;

           echo "<table id='example1' class='table table-striped'>";
           echo "<thead>";
           echo "<tr>";
           echo "<th>Id Rute</th>";
           echo "<th>Detail Rute</th>";
           echo "<th>Nama User</th>";
           echo "<th>Status Rute</th>";
           echo "<th>Durasi</th>";
           echo "<th>Action</th>";
           echo "</tr>";
           echo "</thead>";
           echo "<tbody>";
           while($row = mysqli_fetch_array($result)){
            echo "<tr>";

            echo "<td>" . htmlspecialchars($row['id_rute']) . "</td>";echo "<td>" . htmlspecialchars($row['detail_rute']) . "</td>";echo "<td>" . htmlspecialchars($row['nama_user']) . "</td>";echo "<td>" . htmlspecialchars($row['status']) . "</td>";echo "<td>" . htmlspecialchars($row['durasi']) . "</td>";
            echo "<td>";
            echo "<a href='history-patroli-index.php?id_rute=". $row['id_rute'] ."' title='View Record' data-toggle='tooltip'><i class='far fa-eye'></i></a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
    }
}

                    // Close connection
mysqli_close($link);
?>
</div>
</div>

<?php 
include("footer.php")
?>
