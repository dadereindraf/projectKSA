<?php
include 'koneksi.php'; // Include your database connection

if (isset($_POST['selectedYear'])) {
    $selectedYear = $_POST['selectedYear'];

    // Query to get the monthly data for the selected year
    $query_bulan = "SELECT MONTH(tanggalAkhir) AS bulan, COUNT(*) AS total
        FROM datapks
        WHERE YEAR(tanggalAkhir) = ?
        GROUP BY MONTH(tanggalAkhir)
        ORDER BY bulan ASC
    ";

    $stmt = $koneksi->prepare($query_bulan);
    $stmt->bind_param('i', $selectedYear);
    $stmt->execute();
    $result = $stmt->get_result();

    $bulan_data = [];
    $total_bulan_data = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $bulan_data[] = $row['bulan'];
            $total_bulan_data[] = $row['total'];
        }
    } else {
        echo json_encode(['error' => mysqli_error($koneksi)]);
        exit;
    }

    // Return data as JSON
    echo json_encode(['bulan' => $bulan_data, 'total' => $total_bulan_data]);
}
?>
