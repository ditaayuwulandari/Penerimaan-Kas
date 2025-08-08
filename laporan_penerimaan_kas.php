<?php
include 'koneksi.php';

$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$data = [];
$total_penerimaan = 0;

if ($tanggal_awal && $tanggal_akhir) {
    $query = mysqli_query($conn, "
        SELECT 
            pb.tanggal_pembayaran AS tanggal,
            p.kasir,
            pb.struk AS no_struk,
            p.id_penjualan,
            CONCAT('Penjualan Tunai dari ', c.nama_customer) AS sumber_penerimaan,
            (
                SELECT SUM(dp.subtotal)
                FROM detail_penjualan dp
                WHERE dp.id_penjualan = p.id_penjualan
            ) AS jumlah
        FROM penjualan p
        JOIN pembayaran pb ON pb.id_penjualan = p.id_penjualan
        JOIN customer c ON p.id_customer = c.id_customer
        WHERE pb.tanggal_pembayaran BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
        ORDER BY pb.tanggal_pembayaran ASC
    ");

    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
        $total_penerimaan += $row['jumlah'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penerimaan Kas</title>
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
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        @media print {
            body * { visibility: hidden; }
            #laporan-area, #laporan-area * { visibility: visible; }
            #laporan-area { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
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

    <?php if (!empty($data)): ?>
        <div class="mb-3 text-end no-print">
            <button onclick="window.print()" class="btn btn-sm btn-success">🖨️ Cetak Laporan</button>
        </div>

        <div class="card" id="laporan-area">
            <div class="card-body table-responsive">
                <h4 class="text-center">🧾 <strong>LAPORAN PENERIMAAN KAS</strong></h4>
                <h6 class="text-center text-muted mb-4">GriyaDahar MbokSum</h6>
                <p class="text-center"><em>Periode: <?= date('d-m-Y', strtotime($tanggal_awal)) ?> s/d <?= date('d-m-Y', strtotime($tanggal_akhir)) ?></em></p>

                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Kasir</th>
                            <th>Struk</th>
                            <th>Sumber Penerimaan</th>
                            <th>Total Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                            <td><?= htmlspecialchars($row['kasir']) ?></td>
                            <td>
                                <a href="struk.php?id_penjualan=<?= $row['id_penjualan'] ?>" 
                                   class="btn btn-sm btn-info" target="_blank" title="Lihat Struk">
                                    🧾 TRX-<?= str_pad($row['no_struk'], 3, '0', STR_PAD_LEFT) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($row['sumber_penerimaan']) ?></td>
                            <td>Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-success fw-bold">
                            <td colspan="4" class="text-end">Total:</td>
                            <td>Rp <?= number_format($total_penerimaan, 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    <?php elseif ($tanggal_awal && $tanggal_akhir): ?>
        <div class="alert alert-warning text-center">Tidak ada data penerimaan kas dalam periode ini.</div>
    <?php endif; ?>
</div>
</body>
</html>
