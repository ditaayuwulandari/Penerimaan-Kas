<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$dashboard = ($_SESSION['role'] == 'admin') ? 'dashboard_admin.php'
            : (($_SESSION['role'] == 'owner') ? 'dashboard_owner.php'
            : 'dashboard_kasir.php');

$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$query = "SELECT p.*, c.nama_customer 
          FROM penjualan p 
          LEFT JOIN customer c ON p.id_customer = c.id_customer";

if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
    $query .= " WHERE p.tanggal_penjualan BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
}

$query .= " ORDER BY p.tanggal_penjualan DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Penjualan</title>
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
            background: rgba(255, 255, 255, 0.88);
            padding: 30px;
            border-radius: 10px;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }
        h2 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 30px;
            color: #333;
        }
        .form-label {
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="container shadow">
    <h2>📋 Daftar Penjualan</h2>

    <!-- Filter Tanggal + Tombol Kembali -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
            <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control" value="<?= htmlspecialchars($tanggal_awal) ?>">
        </div>
        <div class="col-md-4">
            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
            <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control" value="<?= htmlspecialchars($tanggal_akhir) ?>">
        </div>
        <div class="col-md-4 align-self-end d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">
                🔍 Filter
            </button>
            <a href="<?= $dashboard ?>" class="btn btn-primary">
                🔙 Kembali
            </a>
        </div>
    </form>

    <!-- Tabel Penjualan -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-primary text-center">
                <tr>
                    <th>Tanggal</th>
                    <th>Customer</th>
                    <th>Kasir</th>
                    <th>Total Bayar</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row['tanggal_penjualan']) ?></td>
                            <td><?= htmlspecialchars($row['nama_customer'] ?? 'Umum') ?></td>
                            <td><?= htmlspecialchars($row['kasir']) ?></td>
                            <td>Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-danger">Tidak ada data penjualan untuk rentang tanggal tersebut.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
