<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Data PKS</title>
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

    <style>
        .status-aktif {
            background-color: #d4edda;
            /* Hijau muda untuk "Aktif" */
            color: #155724;
            /* Warna teks hijau */
        }

        .status-jatuh-tempo {
            background-color: #fff3cd;
            /* Oranye muda untuk "Jatuh Tempo" */
            color: #856404;
            /* Warna teks oranye */
        }

        .status-expired {
            background-color: gray;
            /* Abu-abu muda untuk "Expired" */
            color: #fff;
            /* Warna teks merah */
        }

        th {
            text-align: center;
        }
    </style>

</head>

<body data-sidebar="dark">

    <!-- Loader -->
    <!-- <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div> -->

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
                        <div class="col">
                            <div class="uploadFile">
                                <form action="" method="post" enctype="multipart/form-data">
                                    <input type="file" name="fileToUpload" id="fileToUpload">
                                    <button class="btn btn-primary" type="submit" name="submit">Unggah File</button>
                                </form>

                                <?php
                                include 'koneksi.php';

                                // Tombol hapus (delete)
                                if (isset($_POST["truncate"])) {
                                    $sql_truncate = "TRUNCATE TABLE datapks";
                                    if ($koneksi->query($sql_truncate) === TRUE) {
                                        echo "Semua baris berhasil dihapus dari tabel dataraw.";
                                    } else {
                                        echo "Error: " . $sql_truncate . "<br>" . $koneksi->error;
                                    }
                                }
                                ?>


                                <button class="btn btn-danger" type="submit" name="truncate" data-toggle="modal" data-target="#myModal">Hapus Data</button>


                                <!-- sample modal content -->
                                <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title mt-0" id="myModalLabel">Modal Heading</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <h5>Apakah yakin untuk menghapus data?</h5>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Tidak</button>
                                                <form action="" method="POST">
                                                    <button type="submit" name="truncate" class="btn btn-primary waves-effect waves-light">Ya, yakin</button>
                                                </form>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->



                                <?php
                                include 'koneksi.php';

                                function convertDateFormat($date)
                                {
                                    // Cek apakah tanggal dalam format MM/DD/YYYY
                                    if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $date, $matches)) {
                                        return $matches[3] . '-' . str_pad($matches[1], 2, '0', STR_PAD_LEFT) . '-' . str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                                    }
                                    // Cek apakah tanggal dalam format DD-MM-YYYY
                                    elseif (preg_match('/^(\d{1,2})-(\d{1,2})-(\d{4})$/', $date, $matches)) {
                                        return $matches[3] . '-' . str_pad($matches[2], 2, '0', STR_PAD_LEFT) . '-' . str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                                    }
                                    return $date; // Kembalikan tanggal asli jika tidak cocok
                                }


                                if (isset($_POST["submit"])) {
                                    $target_dir = "uploads/";
                                    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                                    $uploadOk = 1;
                                    $firstRowSkipped = false;
                                    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                                    // Hanya izinkan file CSV
                                    if ($fileType != "csv") {
                                        echo "Hanya file CSV yang diperbolehkan.";
                                        $uploadOk = 0;
                                    }

                                    if ($uploadOk == 0) {
                                        echo "Unggahan file gagal.";
                                    } else {
                                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                                            echo "File " . basename($_FILES["fileToUpload"]["name"]) . " berhasil diunggah.";

                                            // Proses file CSV dan simpan ke database
                                            $file = fopen($target_file, "r");

                                            // Lakukan operasi INSERT SQL ke dalam tabel database
                                            while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
                                                // Melewati baris pertama yang berisi nama kolom
                                                if (!$firstRowSkipped) {
                                                    $firstRowSkipped = true;
                                                    continue; // Skip baris ini dan lanjutkan ke baris berikutnya
                                                }

                                                // Bersihkan data yang diambil dari CSV
                                                $namaMitra = mysqli_real_escape_string($koneksi, $data[0]);
                                                $judulPks = isset($data[1]) ? mysqli_real_escape_string($koneksi, $data[1]) : ''; // Handle kosong
                                                $nomorPks = mysqli_real_escape_string($koneksi, $data[2]);
                                                $ruangLingkup = mysqli_real_escape_string($koneksi, $data[3]);
                                                // Sebelum melakukan INSERT, konversi tanggal
                                                $tanggalAwal = convertDateFormat(mysqli_real_escape_string($koneksi, $data[4]));
                                                $tanggalAkhir = convertDateFormat(mysqli_real_escape_string($koneksi, $data[5]));

                                                $tahunBerakhir = mysqli_real_escape_string($koneksi, $data[6]);
                                                $linkPks = mysqli_real_escape_string($koneksi, $data[7]);
                                                $status = mysqli_real_escape_string($koneksi, $data[8]);
                                                $picKsa = isset($data[9]) ? mysqli_real_escape_string($koneksi, $data[9]) : ''; // Handle kosong
                                                $noHpPic = isset($data[10]) ? mysqli_real_escape_string($koneksi, $data[10]) : ''; // Handle kosong
                                                $emailPic = isset($data[11]) ? mysqli_real_escape_string($koneksi, $data[11]) : ''; // Handle kosong
                                                $picEksternal = isset($data[12]) ? mysqli_real_escape_string($koneksi, $data[12]) : ''; // Handle kosong
                                                $noHpEksternal = isset($data[13]) ? mysqli_real_escape_string($koneksi, $data[13]) : ''; // Handle kosong
                                                $emailEksternal = isset($data[14]) ? mysqli_real_escape_string($koneksi, $data[14]) : ''; // Handle kosong
                                                $emailAsdep = isset($data[15]) ? mysqli_real_escape_string($koneksi, $data[15]) : ''; // Handle kosong
                                                $emailDeputi = isset($data[16]) ? mysqli_real_escape_string($koneksi, $data[16]) : ''; // Handle kosong



                                                // Lakukan operasi INSERT SQL ke dalam tabel database
                                                $sql = "INSERT INTO datapks (namaMitra, judulPks, nomorPks, ruangLingkup, tanggalAwal, tanggalAkhir, tahunBerakhir, linkPks, status, picKsa, noHpPic, emailPic, picEksternal, noHpEksternal, emailEksternal, emailAsdep, emailDeputi) 
                        VALUES ('$namaMitra', '$judulPks', '$nomorPks', '$ruangLingkup', '$tanggalAwal', '$tanggalAkhir', '$tahunBerakhir', '$linkPks', '$status', '$picKsa', '$noHpPic', '$emailPic', '$picEksternal', '$noHpEksternal', '$emailEksternal', '$emailAsdep', '$emailDeputi')";

                                                if ($koneksi->query($sql) !== TRUE) {
                                                    echo "Error: " . $sql . "<br>" . $koneksi->error;
                                                }
                                            }
                                            fclose($file);
                                        } else {
                                            echo "Maaf, terjadi kesalahan saat mengunggah file.";
                                        }
                                    }
                                }
                                ?>




                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Data PKS</h5>
                                    <div class="table-responsive">
                                        <table id="datatable" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Mitra</th>
                                                    <th>Judul PKS</th>
                                                    <th>Nomor PKS</th>
                                                    <th>Ruang Lingkup</th>
                                                    <th>Tanggal Awal</th>
                                                    <th>Tanggal Akhir</th>
                                                    <th>Tahun Berakhir</th>
                                                    <th>Link PKS</th>
                                                    <th>Status</th>
                                                    <th>PIC KSA</th>
                                                    <th>No HP PIC</th>
                                                    <th>Email PIC</th>
                                                    <th>PIC Eksternal</th>
                                                    <th>No Hp Eksternal</th>
                                                    <th>Email Eksternal</th>
                                                    <th>Email Asisten Deputi</th>
                                                    <th>Email Deputi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Sisipkan file koneksi.php
                                                include 'koneksi.php';

                                                $sql = "SELECT * FROM datapks";
                                                $result = mysqli_query($koneksi, $sql);

                                                if (mysqli_num_rows($result) > 0) {
                                                    $nomor = 1;
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        // Mendapatkan tanggal akhir dan mengubahnya menjadi timestamp
                                                        $tanggal_akhir = strtotime($row['tanggalAkhir']);
                                                        $tanggal_sekarang = time();
                                                        $tanggal_jatuh_tempo = strtotime("+45 days", $tanggal_sekarang);

                                                        // Menentukan status dan kelas untuk kolom status
                                                        if ($tanggal_akhir > $tanggal_jatuh_tempo) {
                                                            $status_class = "status-aktif";
                                                            $status = "Aktif";
                                                        } elseif ($tanggal_akhir <= $tanggal_jatuh_tempo && $tanggal_akhir > $tanggal_sekarang) {
                                                            $status_class = "status-jatuh-tempo";
                                                            $status = "Jatuh Tempo";
                                                        } else {
                                                            $status_class = "status-expired";
                                                            $status = "Expired";
                                                        }

                                                        // Output tabel dengan kelas di kolom status
                                                        echo "<tr>";
                                                        echo "<td>" . $nomor . "</td>";
                                                        echo "<td>" . $row['namaMitra'] . "</td>";
                                                        echo "<td>" . $row['judulPks'] . "</td>";
                                                        echo "<td>" . $row['nomorPks'] . "</td>";
                                                        echo "<td>" . $row['ruangLingkup'] . "</td>";
                                                        echo "<td>" . $row['tanggalAwal'] . "</td>";
                                                        echo "<td>" . $row['tanggalAkhir'] . "</td>";
                                                        echo "<td>" . $row['tahunBerakhir'] . "</td>";
                                                        echo "<td><a href='" . $row['linkPks'] . "' target='_blank'>Link PKS</a></td>";
                                                        echo "<td class='$status_class'>" . $status . "</td>";  // Menerapkan kelas status
                                                        echo "<td>" . $row['picKsa'] . "</td>";
                                                        echo "<td>" . $row['noHpPic'] . "</td>";
                                                        echo "<td>" . $row['emailPic'] . "</td>";
                                                        echo "<td>" . $row['picEksternal'] . "</td>";
                                                        echo "<td>" . $row['noHpEksternal'] . "</td>";
                                                        echo "<td>" . $row['emailEksternal'] . "</td>";
                                                        echo "<td>" . $row['emailAsdep'] . "</td>";
                                                        echo "<td>" . $row['emailDeputi'] . "</td>";
                                                        echo "</tr>";
                                                        $nomor++;
                                                    }
                                                } else {
                                                    echo "<div class='row mt-4'>
                                            <div class='col-md-12'>
                                                <div class='alert alert-warning text-center' role='alert'>
                                                    Tidak ada data raw yang ditemukan.
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

        <script src="assets/libs/jquery-knob/jquery.knob.min.js"></script>

        <!-- Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>

        <script src="assets/js/pages/dashboard.init.js"></script>

        <script src="assets/js/app.js"></script>

        <script>
            $(document).ready(function() {
                // Cek apakah DataTable sudah diinisialisasi
                if ($.fn.DataTable.isDataTable('#datatable')) {
                    $('#datatable').DataTable().destroy(); // Hancurkan instance DataTables sebelumnya
                }

                // Inisialisasi DataTables
                $('#datatable').DataTable({
                    scrollX: true,
                    paging: true,
                    searching: true,
                    lengthChange: true
                });
            });
        </script>


</body>

</html>