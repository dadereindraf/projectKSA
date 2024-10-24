<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Jatuh Tempo 45 Hari</title>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Jatuh Tempo 45 Hari</h5>
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
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                include 'koneksi.php';
                                                include 'vendor/autoload.php'; // Memuat PHPMailer

                                                use PHPMailer\PHPMailer\PHPMailer;
                                                use PHPMailer\PHPMailer\Exception;

                                                // Mendapatkan tanggal jatuh tempo
                                                $tanggal_jatuh_tempo = date('Y-m-d', strtotime("45 days"));

                                                // Query untuk mengambil data jatuh tempo
                                                $sql = "SELECT * FROM datapks WHERE tanggalAkhir = '$tanggal_jatuh_tempo'";
                                                $result = mysqli_query($koneksi, $sql);

                                                if (mysqli_num_rows($result) > 0) {
                                                    $nomor = 1;
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        // Menentukan status jatuh tempo
                                                        $status = "jatuh tempo";

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
                                                        echo "<td class='$status'>" . $status . "</td>";  // Menerapkan kelas status
                                                        echo "<td>" . $row['picKsa'] . "</td>";
                                                        echo "<td>" . $row['noHpPic'] . "</td>";
                                                        echo "<td>" . $row['emailPic'] . "</td>";
                                                        echo "<td>" . $row['picEksternal'] . "</td>";
                                                        echo "<td>" . $row['noHpEksternal'] . "</td>";
                                                        echo "<td>" . $row['emailEksternal'] . "</td>";
                                                        echo "<td>" . $row['emailAsdep'] . "</td>";
                                                        echo "<td>" . $row['emailDeputi'] . "</td>";

                                                        // Tombol untuk WhatsApp
                                                        $noHpPic = $row['noHpPic'];
                                                        $nomorPks = $row['nomorPks'];
                                                        $ruangLingkup = $row['ruangLingkup'];
                                                        $tanggalAkhir = $row['tanggalAkhir'];
                                                        echo "<td><button onclick=\"openWhatsApp('$noHpPic', '$nomorPks', '$ruangLingkup', '$tanggalAkhir')\" class='btn btn-success'>Whatsapp</button></td>";

                                                        // Tombol Email dengan form POST
                                                        // Tombol Email dengan form POST
                                                        echo "<td>
                                                        <form method='POST'>
                                                            <input type='hidden' name='email1' value='{$row['emailPic']}'>
                                                            <input type='hidden' name='email2' value='{$row['emailAsdep']}'>
                                                            <input type='hidden' name='email3' value='{$row['emailDeputi']}'>
                                                            <button type='submit' class='btn btn-danger' name='send_email'>Email</button>
                                                        </form>
                                                        </td>";
                                                        echo "</tr>";

                                                        echo "</tr>";
                                                        $nomor++;
                                                    }
                                                } else {
                                                    echo "<div class='alert alert-warning text-center' role='alert'>Tidak ada data jatuh tempo dalam 45 hari yang ditemukan.</div>";
                                                }

                                                // Logika untuk mengirim email
                                                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email'])) {
                                                    $email_penerima1 = $_POST['emailPic'];
                                                    $email_penerima2 = $_POST['emailAsdep'];
                                                    $email_penerima3 = $_POST['emailDeputi'];

                                                    $mail = new PHPMailer(true);

                                                    try {
                                                        // Pengaturan server Gmail
                                                        $mail->isSMTP();
                                                        $mail->Host       = 'smtp.gmail.com';
                                                        $mail->SMTPAuth   = true;
                                                        $mail->Username   = 'lyshakaera@gmail.com'; // Email pengirim
                                                        $mail->Password   = 'kscj lfsb wlec gsxn'; // Gunakan App Password Gmail
                                                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                                                        $mail->Port       = 587;

                                                        // Pengaturan email
                                                        $mail->setFrom('lyshakaera@gmail.com', 'Nama Pengirim');

                                                        // Menambahkan penerima
                                                        $mail->addAddress($email_penerima1);
                                                        if (!empty($email_penerima2)) {
                                                            $mail->addAddress($email_penerima2);
                                                        }
                                                        if (!empty($email_penerima3)) {
                                                            $mail->addAddress($email_penerima3);
                                                        }

                                                        // Konten email
                                                        $mail->isHTML(true);
                                                        $mail->Subject = 'Reminder PKS';
                                                        $mail->Body    = 'Ini adalah pengingat untuk PKS yang akan jatuh tempo.';

                                                        // Kirim email
                                                        $mail->send();
                                                        echo '<div class="alert alert-success">Email berhasil dikirim</div>';
                                                    } catch (Exception $e) {
                                                        echo '<div class="alert alert-danger">Email gagal dikirim. Error: ' . $mail->ErrorInfo . '</div>';
                                                    }
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
            // Menambahkan kode negara Indonesia (+62)
            if (phoneNumber.startsWith("0")) {
                phoneNumber = phoneNumber.substring(1); // Menghapus angka 0 di awal jika ada
            }

            // Mengencode pesan untuk URL
            var message = encodeURIComponent(
                "Perihal: Peringatan Berakhirnya Masa Kerjasama PKS.\n" +
                "Dengan hormat" + ",\n\n" +
                "Masa berlaku kerjasama PKS dengan *" + nomor_pks + "* tentang *" + ruang_lingkup + "* akan berakhir pada tanggal *" + tanggalAkhir + "*, mohon kerjsamanya untuk menindaklanjuti PKS ini.\n\n" +
                "Diharapkan semua kewajiban pihak pertama maupun pihak kedua dapat diselesaikan sebelum tanggal berakhir PKS.\n\n" +
                "Kami ingin meminta konfirmasi kepada PIC PKS untuk melakukan proses konfirmasi dengan cara mengisi form konfirmasi (form terlampir) dan membalas pesan ini melalui email ataupun WA serta melakukan upload di https://bit.ly/DOCPKSKSA\n\n" +
                "Jika ada pertanyaan lebih lanjut mengenai hal ini, dapat menghubungi tim tata kelola\n\n" +
                "Terima kasih atas perhatian dan kerjasamanya\n\n" +
                "Hormat kami,\n\n\n" +
                "Tim Tata Kelola"
            );

            var url = "https://wa.me/62" + phoneNumber + "?text=" + message;
            window.open(url, '_blank');
        }
    </script>


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