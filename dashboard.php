<?php 
include ("header.php");
include ("sidebar.php");
include ("nav.php");
include "koneksi.php";

?>

<!-- Begin Page Content -->

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>
    <?php
    $sql = "SELECT * FROM rute WHERE status = 'aktif' AND id_user = '".$_SESSION['id']."'";
    $result = mysqli_query($koneksi,$sql) or die(mysqli_error($koneksi));
    $row1   = mysqli_fetch_array($result);
    if ($row1 == "" || $row1 == null) {
        $rute1 = "Terimakasih sudah berpatroli";
    } else {
        $rute1 = $row1['detail_rute'];
    }
    if($_SESSION['level_user']=='satpam'){?>
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-Black-800">Rute Anda Adalah : <?php echo $rute1;?></h1>
                </div>
    <?php
    }
    ?>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <?php if ($_SESSION['level_user']=='kadiv'){ ?>
                <!-- Illustrations -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        Peta
                    </div>
                    <div class="card-body">
                    <img src="img/peta.jpeg" width="100%" alt="Peta">
                    </div>
                </div>
            <?php }else{ ?>
                <form action="proses_scan.php" method="GET">
                    <div class="form-group">
                        <label>Input Manual</label>
                        <input type="text" name="nama_checkpoint" maxlength="100"class="form-control">
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                </form>
                <body>
                    <div id="qr-reader" style="width:500px"></div>
                    <div id="qr-reader-results"></div>
                </body>
                <script>
                    function docReady(fn) {
        // see if DOM is already available
        if (document.readyState === "complete"
            || document.readyState === "interactive") {
            // call on next available tick
        setTimeout(fn, 1);
    } else {
        document.addEventListener("DOMContentLoaded", fn);
    }
}

docReady(function () {
    var resultContainer = document.getElementById('qr-reader-results');
    var lastResult, countResults = 0;
    function onScanSuccess(decodedText, decodedResult) {
        if (decodedText !== lastResult) {
            ++countResults;
            lastResult = decodedText;
                // Handle on success condition with the decoded message.
                console.log(`Scan result ${decodedText}`, decodedResult);
                location.replace(`${decodedText}`);
            }
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    });
</script>
<?php } ?>



</div>
</div>
</div>

</div>

</div>    
</div>          





<?php 
include("footer.php")
?>


