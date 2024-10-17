<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Jatuh Tempo 45</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">>
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/logoUBL.png">

    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

</head>

<body data-sidebar="dark">

    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- Navbar Header -->
        <?php include 'navbar.php' ?>
        <!-- End of Navbar -->

        <!-- Left Sidebar-->
        <?php include 'leftSidebar.php' ?>
        <!-- End of Left Sidebar -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Jatuh Tempo 45 Hari</h5>

                                    <table id="datatable" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>No. Telp</th>
                                                <th>Nomor PKS</th>
                                                <!-- <th>Ruang Lingkup</th>
                                                <th>Tanggal Awal</th> -->
                                                <th>Tanggal Akhir</th>
                                                <!-- <th>Tahun</th> -->
                                                <th>Link PKS</th>
                                                <th>Status</th>
                                                <th>PIC</th>
                                                <th>Aksi</th> <!-- Tambahkan kolom untuk tombol -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        // Sisipkan file koneksi.php
                                        include 'koneksi.php';

                                        // Mendapatkan tanggal hari ini dan tanggal jatuh tempo
                                        $tanggal_sekarang = date('Y-m-d'); // Format YYYY-MM-DD
                                        $tanggal_jatuh_tempo = date('Y-m-d', strtotime("10 days")); // 30 hari ke depan

                                        // Modifikasi query untuk mengambil data dengan status jatuh tempo
                                        $sql = "SELECT * FROM datapks WHERE tanggal_akhir = '$tanggal_jatuh_tempo'";
                                        $result = mysqli_query($koneksi, $sql);

                                        if (mysqli_num_rows($result) > 0) {
                                            $nomor = 1;
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                // Menentukan status jatuh tempo
                                                $status = "jatuh tempo";

                                                echo "<tr>";
                                                echo "<td>" . $nomor . "</td>";
                                                echo "<td>" . $row['nama'] . "</td>";
                                                echo "<td>" . $row['no_telp'] . "</td>";
                                                echo "<td>" . $row['nomor_pks'] . "</td>";
                                                // echo "<td>" . $row['ruang_lingkup'] . "</td>";
                                                // echo "<td>" . $row['tanggal_awal'] . "</td>";
                                                echo "<td>" . $row['tanggal_akhir'] . "</td>";
                                                // echo "<td>" . $row['tahun'] . "</td>";
                                                echo "<td>" . $row['link_pks'] . "</td>";
                                                echo "<td>" . $status . "</td>";  // Status tetap jatuh tempo
                                                echo "<td>" . $row['pic'] . "</td>";

                                                // Menambahkan tombol Kirim Reminder PKS
                                                $no_telp = $row['no_telp']; // Tidak perlu encode lagi di sini
                                                $nomor_pks = $row['nomor_pks']; 
                                                $ruang_lingkup = $row['ruang_lingkup']; 
                                                $tanggal_akhir = $row['tanggal_akhir']; 
                                                // Tombol dengan onClick
                                                echo "<td><button onclick=\"openWhatsApp('$no_telp', '$nomor_pks', '$ruang_lingkup', '$tanggal_akhir')\" class='btn btn-success'>Kirim Reminder PKS</button></td>";
                                                echo "</tr>";
                                                $nomor++;
                                            }
                                        } else {
                                            echo "<div class='row mt-4'>
                                            <div class='col-md-12'>
                                                <div class='alert alert-warning text-center' role='alert'>
                                                    Tidak ada data jatuh tempo dalam 10 hari yang ditemukan.
                                                </div>
                                            </div>
                                        </div>";
                                        }
                                        ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page-content -->


            <!-- ============== FOOTER ================-->

            <?php include 'footer.php' ?>

            <!-- ============== END OF FOOTER ================-->
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    <div class="right-bar">
        <div data-simplebar class="h-100">
            <div class="rightbar-title px-3 py-4">
                <a href="javascript:void(0);" class="right-bar-toggle float-right">
                    <i class="mdi mdi-close noti-icon"></i>
                </a>
                <h5 class="m-0">Settings</h5>
            </div>

            <!-- Settings -->
            <hr class="mt-0" />
            <h6 class="text-center mb-0">Choose Layouts</h6>

            <div class="p-4">
                <div class="mb-2">
                    <img src="assets/images/layouts/layout-1.jpg" class="img-fluid img-thumbnail" alt="">
                </div>
                <div class="custom-control custom-switch mb-3">
                    <input type="checkbox" class="custom-control-input theme-choice" id="light-mode-switch" checked />
                    <label class="custom-control-label" for="light-mode-switch">Light Mode</label>
                </div>

                <div class="mb-2">
                    <img src="assets/images/layouts/layout-2.jpg" class="img-fluid img-thumbnail" alt="">
                </div>
                <div class="custom-control custom-switch mb-3">
                    <input type="checkbox" class="custom-control-input theme-choice" id="dark-mode-switch" data-bsStyle="assets/css/bootstrap-dark.min.css" data-appStyle="assets/css/app-dark.min.css" />
                    <label class="custom-control-label" for="dark-mode-switch">Dark Mode</label>
                </div>
            </div>

        </div> <!-- end slimscroll-menu-->
    </div>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>

    <!-- Required datatable js -->
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <!-- Datatable init js -->
    <script src="assets/js/pages/datatables.init.js"></script>

    <script src="assets/libs/jquery-knob/jquery.knob.min.js"></script>

    <script src="assets/js/pages/dashboard.init.js"></script>

    <script src="assets/js/app.js"></script>

    <script>
        function openWhatsApp(phoneNumber, nomor_pks, ruang_lingkup, tanggalAkhir) {
            var message = encodeURIComponent(
                "Perihal: Peringatan Berakhirnya Masa Kerjasama PKS.\n"+ 
                "Dengan hormat"+",\n\n" +
                "Masa berlaku kerjasama PKS dengan *" + nomor_pks + "* tentang *" +ruang_lingkup+ "* akan berakhir pada tanggal *" +tanggalAkhir+ "* atau 15 hari lagi, mohon kerjsamanya untuk menindaklanjuti PKS ini.\n\n"+
                "Diharapkan semua kewajiban pihak pertama maupun pihak kedua dapat diselesaikan sebelum tanggal berakhir PKS.\n\n"+
                "Kami ingin meminta konfirmasi kepada PIC PKS untuk melakukan proses konfirmasi dengan cara mengisi form konfirmasi (form terlampir) dan membalas pesan ini melalui email ataupun WA serta melakukan upload di https://bit.ly/DOCPKSKSA\n\n"+
                "Jika ada pertanyaan lebih lanjut mengenai hal ini, dapat menghubungi tim tata kelola\n\n"+
                "Terima kasih atas perhatian dan kerjasamanya\n\n"+
                "Hormat kami,\n\n\n"+
                "Tim Tata Kelola"
            );

            var url = "https://wa.me/" + phoneNumber + "?text=" + message;
            window.open(url, '_blank');
        }
    </script>

</body>

</html>