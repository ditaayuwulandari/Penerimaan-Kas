<?php
include 'koneksi.php';

$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$query = null;
$hasil = [];
$grand_total = 0;

if ($tanggal_awal && $tanggal_akhir) {
    $query = mysqli_query($conn, "
        SELECT 
            m.nama_menu,
            dp.harga_satuan,
            SUM(dp.jumlah) AS total_jumlah,
            SUM(dp.subtotal) AS total_subtotal
        FROM penjualan p
        JOIN detail_penjualan dp ON p.id_penjualan = dp.id_penjualan
        JOIN menu m ON dp.id_menu = m.id_menu
        WHERE p.tanggal_penjualan BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
        GROUP BY m.nama_menu, dp.harga_satuan
        ORDER BY m.nama_menu ASC
    ");

    while ($row = mysqli_fetch_assoc($query)) {
        $hasil[] = $row;
        $grand_total += $row['total_subtotal'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan per Menu</title>
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
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 0px 12px rgba(0,0,0,0.1);
        }
        @media print {
            body * {
                visibility: hidden;
            }
            #laporan-area, #laporan-area * {
                visibility: visible;
            }
            #laporan-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-4">

    <form method="get" class="row g-3 mb-4 no-print">
        <div class="col-md-4">
            <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
            <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control"
                   value="<?= htmlspecialchars($tanggal_awal) ?>" required>
        </div>
        <div class="col-md-4">
            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control"
                   value="<?= htmlspecialchars($tanggal_akhir) ?>" required>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-warning w-100 me-2">🔍 Tampilkan</button>
            <a href="dashboard_owner.php" class="btn btn-warning w-100">🔙 Kembali</a>
        </div>
    </form>

    <?php if (!empty($hasil)): ?>
        <div class="mb-3 text-end no-print">
            <button onclick="window.print()" class="btn btn-sm btn-success">
                🖨️ Cetak Laporan
            </button>
        </div>

        <div class="card" id="laporan-area">
            <div class="card-body table-responsive">
                <h4 class="text-center">
                    🧾 <strong>LAPORAN PENJUALAN MENU</strong>
                </h4>
                <h6 class="text-center text-muted mb-4">GriyaDahar MbokSum</h6>
                <p class="text-center"><em>Periode: <?= date('d-m-Y', strtotime($tanggal_awal)) ?> s/d <?= date('d-m-Y', strtotime($tanggal_akhir)) ?></em></p>

                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                    <tr>
                        <th>Nama Menu</th>
                        <th>Jumlah Terjual</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($hasil as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama_menu']) ?></td>
                            <td><?= $row['total_jumlah'] ?></td>
                            <td>Rp <?= number_format($row['harga_satuan'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($row['total_subtotal'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-light">
                    <tr>
                        <th colspan="3" class="text-end">Total Penjualan:</th>
                        <th>Rp <?= number_format($grand_total, 0, ',', '.') ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    <?php elseif ($tanggal_awal && $tanggal_akhir): ?>
        <div class="alert alert-warning text-center">Data tidak ditemukan pada rentang tanggal tersebut.</div>
    <?php endif; ?>
</div>
</body>
</html>
