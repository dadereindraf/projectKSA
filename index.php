<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">>

    <link rel="shortcut icon" href="assets/images/logoUBL.png">

    <link href="assets/libs/c3/c3.min.css" rel="stylesheet" type="text/css" />

    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />

    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />

    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

</head>

<body data-sidebar="dark">

    <!-- Loader -->
    <!-- <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div> -->
    <!-- End of Loader -->

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- Navbar Header -->
        <?php include 'navbar.php' ?>
        <!-- End of Navbar -->

        <!-- Left Sidebar-->
        <?php include 'leftSidebar.php' ?>
        <!-- End of Left Sidebar -->

        <!-- ============================================================== -->
        <!--                          ISI KONTEN                            -->
        <!-- ============================================================== -->
        <div class="main-content">

            <!-- Page Content -->
            <div class="page-content">

                <!-- Container -->
                <div class="container-fluid">

                    <div class="row">

                    <?php
include 'koneksi.php';

// Menghitung total Data Raw
$query_total = "SELECT COUNT(*) AS total_rows FROM datapks";
$result_total = mysqli_query($koneksi, $query_total);
$total_rows_datapks = ($result_total) ? mysqli_fetch_assoc($result_total)['total_rows'] : "Error: " . mysqli_error($koneksi);

// Menghitung data Aktif (tanggal_akhir lebih besar dari hari ini)
$query_aktif = "SELECT COUNT(*) AS total_aktif FROM datapks WHERE tanggal_akhir > CURDATE()";
$result_aktif = mysqli_query($koneksi, $query_aktif);
$total_rows_aktif = ($result_aktif) ? mysqli_fetch_assoc($result_aktif)['total_aktif'] : "Error: " . mysqli_error($koneksi);

// Menghitung data Jatuh Tempo (tanggal_akhir antara hari ini dan 30 hari mendatang)
$query_jatuh_tempo = "SELECT COUNT(*) AS total_jatuh_tempo FROM datapks WHERE tanggal_akhir BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)";
$result_jatuh_tempo = mysqli_query($koneksi, $query_jatuh_tempo);
$total_rows_jatuh_tempo = ($result_jatuh_tempo) ? mysqli_fetch_assoc($result_jatuh_tempo)['total_jatuh_tempo'] : "Error: " . mysqli_error($koneksi);

// Menghitung data Expired (tanggal_akhir lebih kecil dari hari ini)
$query_expired = "SELECT COUNT(*) AS total_expired FROM datapks WHERE tanggal_akhir < CURDATE()";
$result_expired = mysqli_query($koneksi, $query_expired);
$total_rows_expired = ($result_expired) ? mysqli_fetch_assoc($result_expired)['total_expired'] : "Error: " . mysqli_error($koneksi);

// Menampilkan data ke dalam elemen card
echo "
<div class='row'>
    <div class='col-md-6 col-xl-2'>
        <div class='card'>
            <a href='dataRaw.php' style='text-decoration: none; color: inherit;'>
                <div class='card-body'>
                    <h5 class='card-title'>Total Data PKS</h5>
                    <p class='text-danger'><strong style='font-size: 30px;'>$total_rows_datapks</strong></p>
                </div>
            </a>
        </div>
    </div>

    <div class='col-md-6 col-xl-2'>
        <div class='card'>
            <a href='dataAktif.php' style='text-decoration: none; color: inherit;'>
                <div class='card-body'>
                    <h5 class='card-title'>Data Aktif</h5>
                    <p class='text-success'><strong style='font-size: 30px;'>$total_rows_aktif</strong></p>
                </div>
            </a>
        </div>
    </div>

    <div class='col-md-6 col-xl-2'>
        <div class='card'>
            <a href='dataJatuhTempo.php' style='text-decoration: none; color: inherit;'>
                <div class='card-body'>
                    <h5 class='card-title'>Data Jatuh Tempo</h5>
                    <p class='text-warning'><strong style='font-size: 30px;'>$total_rows_jatuh_tempo</strong></p>
                </div>
            </a>
        </div>
    </div>

    <div class='col-md-6 col-xl-2'>
        <div class='card'>
            <a href='dataExpired.php' style='text-decoration: none; color: inherit;'>
                <div class='card-body'>
                    <h5 class='card-title'>Data Expired</h5>
                    <p class='text-danger'><strong style='font-size: 30px;'>$total_rows_expired</strong></p>
                </div>
            </a>
        </div>
    </div>
</div>";
?>

                        <!-- End of Data Raw-->
                    </div>
                    <!-- End of Hasil Pengujian -->

                </div>
                <!-- End of Container -->

            </div>
            <!-- End of Page Content -->

            <!-- Footer -->
            <?php include 'footer.php' ?>
            <!-- End of Footer -->

        </div>
        <!-- ============================================================== -->
        <!--                        END OF ISI KONTEN                       -->
        <!-- ============================================================== -->

    </div>
    <!-- End of Begin Page -->

    <!-- Right Sidebar -->
    <?php include 'rightSidebar.php' ?>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>

    <script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.7/MathJax.js?config=TeX-MML-AM_CHTML">
    </script>


    <!-- Peity chart-->
    <script src="assets/libs/peity/jquery.peity.min.js"></script>

    <!--C3 Chart-->
    <script src="assets/libs/d3/d3.min.js"></script>
    <script src="assets/libs/c3/c3.min.js"></script>

    <script src="assets/libs/jquery-knob/jquery.knob.min.js"></script>

    <script src="assets/js/pages/dashboard.init.js"></script>

    <script src="assets/js/app.js"></script>

</body>

</html>