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

                    <!-- Menambahkan mx-0 untuk menghilangkan margin horizontal -->
                    <?php
                    include 'koneksi.php';

                    // Menghitung total Data Raw
                    $query_total = "SELECT COUNT(*) AS total_rows FROM datapks";
                    $result_total = mysqli_query($koneksi, $query_total);
                    $total_rows_datapks = ($result_total) ? mysqli_fetch_assoc($result_total)['total_rows'] : "Error: " . mysqli_error($koneksi);

                    // Menghitung data Aktif (tanggal_akhir lebih besar dari hari ini)
                    $query_aktif = "SELECT COUNT(*) AS total_aktif FROM datapks WHERE tanggalAkhir > CURDATE()";
                    $result_aktif = mysqli_query($koneksi, $query_aktif);
                    $total_rows_aktif = ($result_aktif) ? mysqli_fetch_assoc($result_aktif)['total_aktif'] : "Error: " . mysqli_error($koneksi);

                    // Menghitung data Jatuh Tempo (tanggal_akhir antara hari ini dan 30 hari mendatang)
                    // Menghitung total data jatuh tempo
                    $query_jatuh_tempo = "SELECT COUNT(*) AS total_jatuh_tempo FROM datapks WHERE tanggalAkhir > CURDATE() AND tanggalAkhir <= DATE_ADD(CURDATE(), INTERVAL 45 DAY)";
                    $result_jatuh_tempo = mysqli_query($koneksi, $query_jatuh_tempo);
                    $total_rows_jatuh_tempo = ($result_jatuh_tempo) ? mysqli_fetch_assoc($result_jatuh_tempo)['total_jatuh_tempo'] : "Error: " . mysqli_error($koneksi);

                    // Menghitung data Expired (tanggal_akhir lebih kecil dari hari ini)
                    $query_expired = "SELECT COUNT(*) AS total_expired FROM datapks WHERE tanggalAkhir < CURDATE()";
                    $result_expired = mysqli_query($koneksi, $query_expired);
                    $total_rows_expired = ($result_expired) ? mysqli_fetch_assoc($result_expired)['total_expired'] : "Error: " . mysqli_error($koneksi);

                    // Menampilkan data ke dalam elemen card
                    echo "
                        <div class='row'>
                            <div class='col-3 mb-4'> <!-- Menambahkan margin bawah -->
                                <div class='card h-100'>
                                    <a href='datapks.php' style='text-decoration: none; color: inherit;'>
                                        <div class='card-body'>
                                            <h5 class='card-title'>Total Data PKS</h5>
                                            <p><strong style='font-size: 30px;'>$total_rows_datapks</strong></p>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class='col-3 mb-4'> <!-- Menambahkan margin bawah -->
                                <div class='card h-100'>
                                    <a href='datapks.php' style='text-decoration: none; color: inherit;'>
                                        <div class='card-body'>
                                            <h5 class='card-title'>Data Aktif</h5>
                                            <p class='text-success'><strong style='font-size: 30px;'>$total_rows_aktif</strong></p>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class='col-3 mb-4'> <!-- Menambahkan margin bawah -->
                                <div class='card h-100'>
                                    <a href='datapks.php' style='text-decoration: none; color: inherit;'>
                                        <div class='card-body'>
                                            <h5 class='card-title'>Data Jatuh Tempo H-45</h5>
                                            <p class='text-warning'><strong style='font-size: 30px;'>$total_rows_jatuh_tempo</strong></p>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class='col-3 mb-4'> <!-- Menambahkan margin bawah -->
                                <div class='card h-100'>
                                    <a href='datapks.php' style='text-decoration: none; color: inherit;'>
                                        <div class='card-body'>
                                            <h5 class='card-title'>Data Expired</h5>
                                            <p class='text-danger'><strong style='font-size: 30px;'>$total_rows_expired</strong></p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                            ";
                    ?>

                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Grafik PKS</h5>
                                    <canvas id='barChart' width='100%' height='50'></canvas>
                                </div>
                            </div>
                        </div>
                        <?php

                        // Menghitung jumlah data berdasarkan tahun dari tanggal_akhir
                        $query_tahun = "SELECT YEAR(tanggalAkhir) AS tahun, COUNT(*) AS total
                                    FROM datapks
                                    GROUP BY YEAR(tanggalAkhir)
                                    ORDER BY tahun ASC
                                    ";

                        $result_tahun = mysqli_query($koneksi, $query_tahun);
                        $tahun_data = [];
                        $total_data = [];

                        if ($result_tahun) {
                            while ($row = mysqli_fetch_assoc($result_tahun)) {
                                $tahun_data[] = $row['tahun'];
                                $total_data[] = $row['total'];
                            }
                        } else {
                            echo "Error: " . mysqli_error($koneksi);
                        }

                        // Konversi array PHP ke format JSON untuk Chart.js
                        $tahun_json = json_encode($tahun_data);
                        $total_json = json_encode($total_data);

                        echo "
                            <div class='col-xl-6'>
                                <div class='card mb-4'>
                                    <div class='card-body'>
                                        <h5 class='card-title'>Grafik PKS Per Tahun</h5>
                                        <canvas id='yearbarChart' width='100%' height='50'></canvas> <!-- Canvas untuk grafik batang -->
                                    </div>
                                </div>
                            </div>
                        
                        ";
                        ?>
                    </div>

                    <div class="row">
                        <div class='col-xl-12'>
                            <div class='card mb-4'>
                                <div class='card-body'>
                                    <h5 class='card-title'>Grafik PKS Summary Bulan</h5>
                                    <select id='yearSelect' class='form-select mb-3' aria-label='Select Year'>
                                        <option value='' disabled selected>Pilih Tahun</option>
                                        <?php
                                        // Fetch all unique years from the database
                                        $query_years = "SELECT DISTINCT YEAR(tanggalAkhir) AS tahun FROM datapks ORDER BY tahun DESC";
                                        $result_years = mysqli_query($koneksi, $query_years);

                                        if ($result_years) {
                                            while ($row = mysqli_fetch_assoc($result_years)) {
                                                echo "<option value='{$row['tahun']}'>{$row['tahun']}</option>";
                                            }
                                        } else {
                                            echo "Error: " . mysqli_error($koneksi);
                                        }
                                        ?>
                                    </select>
                                    <canvas id='monthbarChart' width='100%' height='30'></canvas>
                                </div>
                            </div>
                        </div>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Bar Chart untuk status data (Aktif, Jatuh Tempo, Expired)
        var ctxBar = document.getElementById('barChart').getContext('2d');
        var barChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Aktif', 'Jatuh Tempo', 'Expired'],
                datasets: [{
                    label: 'Jumlah Data',
                    data: [<?php echo $total_rows_aktif; ?>, <?php echo $total_rows_jatuh_tempo; ?>, <?php echo $total_rows_expired; ?>],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.5)', // Hijau (Aktif)
                        'rgba(255, 206, 86, 0.5)', // Kuning (Jatuh Tempo)
                        'rgba(255, 99, 132, 0.5)' // Merah (Expired)
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)', // Hijau (Aktif)
                        'rgba(255, 206, 86, 1)', // Kuning (Jatuh Tempo)
                        'rgba(255, 99, 132, 1)' // Merah (Expired)
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Grafik tahun
        const tahunData = <?php echo $tahun_json; ?>;
        const totalData = <?php echo $total_json; ?>;

        const ctxYear = document.getElementById('yearbarChart').getContext('2d');
        const yearBarChart = new Chart(ctxYear, {
            type: 'bar',
            data: {
                labels: tahunData,
                datasets: [{
                    label: 'Jumlah Data PKS',
                    data: totalData,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)', // Semua biru
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tahun'
                        }
                    }
                }
            }
        });

        // Function to update the month chart based on the selected year
        function updateMonthChart(year) {
            $.ajax({
                url: 'fetch_month_data.php', // Make sure this file exists
                type: 'POST',
                data: {
                    selectedYear: year
                },
                success: function(response) {
                    const data = JSON.parse(response);

                    // Update the chart labels and data
                    monthBarChart.data.labels = data.bulan.map(month => {
                        const date = new Date(0, month - 1);
                        return date.toLocaleString('default', {
                            month: 'long'
                        });
                    });
                    monthBarChart.data.datasets[0].data = data.total;
                    monthBarChart.update();
                },
                error: function() {
                    console.error("Error fetching data");
                }
            });
        }

        // Event listener for year selection
        document.getElementById('yearSelect').addEventListener('change', function() {
            const selectedYear = this.value;
            updateMonthChart(selectedYear);
        });

        // Optional: Initialize chart with the current year's data when the page loads
        // const currentYear = new Date().getFullYear();
        // updateMonthChart(currentYear);



        // Initialize the month bar chart with empty data or default data
        const ctxMonth = document.getElementById('monthbarChart').getContext('2d');
        const monthBarChart = new Chart(ctxMonth, {
            type: 'bar',
            data: {
                labels: [], // Empty initially
                datasets: [{
                    label: 'Jumlah Data PKS per Bulan',
                    data: [], // Empty initially
                    backgroundColor: 'rgba(255, 159, 64, 0.5)', // Orange color for the bars
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>