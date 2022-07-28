<?php 
include ("header.php");
include ("sidebar.php");
include ("nav.php");
include ("koneksi.php");

?>

<!-- Begin Page Content -->
<div class="container-fluid">
  <h1 class="text-center h3 mb-0 text-gray-800"> <span class="font-weight-bold text-info "> Data Satpam </h1>    
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

                    $total_pages_sql = "SELECT COUNT(*) FROM user";
                    $result = mysqli_query($link,$total_pages_sql);
                    $total_rows = mysqli_fetch_array($result)[0];
                    $total_pages = ceil($total_rows / $no_of_records_per_page);

                    //Column sorting on column name
                    $orderBy = array('id_user', 'username', 'password', 'nama_user', 'level_user');
                    $order = 'id_user';
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
                    $sql = "SELECT * FROM user where level_user = 'satpam'";
                    $count_pages = "SELECT * FROM user";


                    if(!empty($_GET['search'])) {
                        $search = ($_GET['search']);
                        $sql = "SELECT * FROM user
                            WHERE CONCAT_WS (id_user,username,password,nama_user,level_user)
                            LIKE '%$search%'
                            ORDER BY $order $sort
                            LIMIT $offset, $no_of_records_per_page";
                        $count_pages = "SELECT * FROM user
                            WHERE CONCAT_WS (id_user,username,password,nama_user,level_user)
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
                            echo "<table id='example1' class='table table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>id_user</th>";
                                        echo "<th>username</th>";
                                        echo "<th>nama_user</th>";
                                        echo "<th>level_user</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['id_user']) . "</td>";echo "<td>" . htmlspecialchars($row['username']) . "</td>";echo "<td>" . htmlspecialchars($row['nama_user']) . "</td>";echo "<td>" . htmlspecialchars($row['level_user']) . "</td>";
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
