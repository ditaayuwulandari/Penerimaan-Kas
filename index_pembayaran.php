<?php
include 'koneksi.php';

// Cek filter tanggal
$where = "";
if (!empty($_GET['tanggal_awal']) && !empty($_GET['tanggal_akhir'])) {
    $tanggal_awal = $_GET['tanggal_awal'];
    $tanggal_akhir = $_GET['tanggal_akhir'];
    $where = "WHERE bayar.tanggal_pembayaran BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
} else {
    $tanggal_awal = "";
    $tanggal_akhir = "";
}

// Query utama
$query = "
    SELECT 
        bayar.id_pembayaran,
        bayar.id_penjualan,
        bayar.tanggal_pembayaran,
        bayar.jumlah_dibayar,
        cust.nama_customer,
        SUM(dp.subtotal) AS total_tagihan
    FROM pembayaran bayar
    JOIN penjualan pj ON bayar.id_penjualan = pj.id_penjualan
    JOIN customer cust ON pj.id_customer = cust.id_customer
    JOIN detail_penjualan dp ON dp.id_penjualan = pj.id_penjualan
    $where
    GROUP BY bayar.id_pembayaran, bayar.id_penjualan, bayar.tanggal_pembayaran, bayar.jumlah_dibayar, cust.nama_customer
    ORDER BY bayar.tanggal_pembayaran DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('FOTO_GRIYA_DAHAR.JPG');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 20px;
        }
        .container {
            margin-top: 40px;
            background: rgba(255, 255, 255, 0.8);
            padding: 25px;
            border-radius: 10px;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">📋 Daftar Pembayaran</h2>

    <!-- Form Filter -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
            <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" value="<?= htmlspecialchars($tanggal_awal) ?>">
        </div>
        <div class="col-md-4">
            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="<?= htmlspecialchars($tanggal_akhir) ?>">
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-primary">🔍 Filter</button>
        </div>
    </form>

    <!-- Tabel Pembayaran -->
    <table class="table table-bordered table-hover">
        <thead class="table-primary">
            <tr>
                <th>Nama Customer</th>
                <th>Tanggal Pembayaran</th>
                <th>Total Harus Di Bayar</th>
                <th>Uang Diberikan</th>
                <th>Struk</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['nama_customer']}</td>
                        <td>{$row['tanggal_pembayaran']}</td>
                        <td>Rp " . number_format($row['total_tagihan'], 0, ',', '.') . "</td>
                        <td>Rp " . number_format($row['jumlah_dibayar'], 0, ',', '.') . "</td>
                        <td>
                            <a href='struk.php?id_penjualan={$row['id_penjualan']}' class='btn btn-success btn-sm' target='_blank'>🧾 Lihat Struk</a>
                        </td>
                    </tr>";
                    $no++;
                }
            } else {
                echo '<tr><td colspan="6" class="text-center text-danger">Tidak ada data untuk rentang tanggal tersebut.</td></tr>';
            }
            ?>
        </tbody>
    </table>

    <!-- Tombol Kembali -->
        <a href="dashboard_admin.php" class="btn btn-primary">Kembali</a>
        </a>
    </div>
</div>

</body>
</html>
