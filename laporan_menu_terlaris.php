<?php
include 'koneksi.php';

$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';
$hasil = [];

if ($tanggal_awal && $tanggal_akhir) {
    $query = mysqli_query($conn, "
        SELECT m.nama_menu, SUM(dp.jumlah) AS total_terjual
        FROM detail_penjualan dp
        JOIN menu m ON dp.id_menu = m.id_menu
        JOIN penjualan p ON dp.id_penjualan = p.id_penjualan
        WHERE p.tanggal_penjualan BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
        GROUP BY m.nama_menu
        ORDER BY total_terjual DESC
        LIMIT 3
    ");

    if ($query) {
        while ($row = mysqli_fetch_assoc($query)) {
            $hasil[] = $row;
        }
    } else {
        die("Query Error: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Menu Terlaris</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            max-width: 1000px;
            margin: auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .table thead {
            background-color: #f8f9fa;
        }

        @media print {
            body * { visibility: hidden; }
            .container, .container * { visibility: visible; }
            .container { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="container">
    <form method="get" class="row g-3 mb-4 no-print">
        <div class="col-md-4">
            <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
            <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" value="<?= htmlspecialchars($tanggal_awal) ?>" required>
        </div>
        <div class="col-md-4">
            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="<?= htmlspecialchars($tanggal_akhir) ?>" required>
        </div>
        <div class="col-md-4 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-warning w-100">🔍 Tampilkan</button>
            <a href="dashboard_owner.php" class="btn btn-warning w-100">🔙 Kembali</a>
        </div>
    </form>

    <?php if (!empty($hasil)): ?>
        <div class="text-end mb-3 no-print">
            <button onclick="window.print()" class="btn btn-success btn-sm">🖨️ Cetak</button>
        </div>

        <h4 class="text-center">📊 <strong>LAPORAN MENU TERLARIS</strong></h4>
        <h6 class="text-center text-muted mb-4">GriyaDahar MbokSum</h6>
        <p class="text-center"><em>Periode: <?= date('d-m-Y', strtotime($tanggal_awal)) ?> s/d <?= date('d-m-Y', strtotime($tanggal_akhir)) ?></em></p>

        <p class="mb-3">
            Berikut ini adalah tiga menu yang paling banyak terjual selama periode yang dipilih. Informasi ini bisa digunakan untuk strategi promosi dan analisis tren menu:
        </p>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama Menu</th>
                    <th>Total Terjual</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hasil as $menu): ?>
                    <tr>
                        <td><?= htmlspecialchars($menu['nama_menu']) ?></td>
                        <td><?= htmlspecialchars($menu['total_terjual']) ?> porsi</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="alert alert-info mt-4">
            <strong>Catatan:</strong> Data ini hanya menampilkan 3 menu dengan penjualan tertinggi berdasarkan jumlah porsi yang terjual.
        </div>

    <?php elseif ($tanggal_awal && $tanggal_akhir): ?>
        <div class="alert alert-warning text-center">
            Tidak ditemukan data penjualan dalam rentang tanggal tersebut.
        </div>
    <?php endif; ?>
</div>

</body>
</html>
