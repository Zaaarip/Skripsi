<?php 
include ("header.php");
include ("sidebar.php");
include ("nav.php");
include ("koneksi.php");

?>

<!-- Begin Page Content -->
<div class="container-fluid">
  <h1 class="text-center h3 mb-0 text-gray-800"> <span class="font-weight-bold text-info "> Data Zona </h1>    
      <div class="card-body">
        <div class="page-header clearfix">
            <a href="zona-create.php" class="btn btn-success float-right">Add New Record</a>
            <a href="zona-index.php" class="btn btn-info float-right mr-2">Reset View</a>
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

        $total_pages_sql = "SELECT COUNT(*) FROM zona";
        $result = mysqli_query($link,$total_pages_sql);
        $total_rows = mysqli_fetch_array($result)[0];
        $total_pages = ceil($total_rows / $no_of_records_per_page);

                    //Column sorting on column name
        $orderBy = array('nama_zona');
        $order = 'id_zona';
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
    $sql = "SELECT * FROM zona";
    $count_pages = "SELECT * FROM zona";


    if(!empty($_GET['search'])) {
        $search = ($_GET['search']);
        $sql = "SELECT * FROM zona
        WHERE CONCAT_WS (nama_zona)
        LIKE '%$search%'
        ORDER BY $order $sort
        LIMIT $offset, $no_of_records_per_page";
        $count_pages = "SELECT * FROM zona
        WHERE CONCAT_WS (nama_zona)
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
           echo "<th><a href=?search=$search&sort=&order=nama_zona&sort=$sort>nama_zona</th>";

           echo "<th>Action</th>";
           echo "</tr>";
           echo "</thead>";
           echo "<tbody>";
           while($row = mysqli_fetch_array($result)){
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['nama_zona']) . "</td>";
            echo "<td>";
            echo "<a href='checkpoint-index.php?id_zona=". $row['id_zona'] ."' title='View Record' data-toggle='tooltip'><i class='far fa-eye'></i></a>";
            echo "<a href='zona-update.php?id_zona=". $row['id_zona'] ."' title='Update Record' data-toggle='tooltip'><i class='far fa-edit'></i></a>";
            echo "<a href='zona-delete.php?id_zona=". $row['id_zona'] ."' title='Delete Record' data-toggle='tooltip'><i class='far fa-trash-alt'></i></a>";
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
