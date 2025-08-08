<?php
include 'koneksi.php';

$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$data_customer = [];
$total_customer = 0;

if ($tanggal_awal && $tanggal_akhir) {
    $query = mysqli_query($conn, "
        SELECT DISTINCT c.nama_customer, c.alamat
        FROM penjualan p
        JOIN customer c ON p.id_customer = c.id_customer
        WHERE p.tanggal_penjualan BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
    ");

    while ($row = mysqli_fetch_assoc($query)) {
        $data_customer[] = $row;
    }

    $total_customer = count($data_customer);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Jumlah Customer</title>
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

    <?php if ($tanggal_awal && $tanggal_akhir): ?>
        <div class="mb-3 text-end no-print">
            <button onclick="window.print()" class="btn btn-sm btn-success">🖨️ Cetak Laporan</button>
        </div>

        <div class="card" id="laporan-area">
            <div class="card-body table-responsive">
                <h4 class="text-center">📌 <strong>LAPORAN JUMLAH CUSTOMER</strong></h4>
                <h6 class="text-center text-muted mb-4">GriyaDahar MbokSum</h6>
                <p class="text-center"><em>Periode: <?= date('d-m-Y', strtotime($tanggal_awal)) ?> s/d <?= date('d-m-Y', strtotime($tanggal_akhir)) ?></em></p>

                <div class="text-center my-3">
                    <h5>Total Customer yang Melakukan Transaksi:</h5>
                    <h2 class="text-success"><?= $total_customer ?> Customer</h2>
                </div>

                <?php if (!empty($data_customer)): ?>
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Customer</th>
                                <th>Alamat Lengkap</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data_customer as $index => $row): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($row['nama_customer']) ?></td>
                                <td><?= htmlspecialchars($row['alamat']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-warning text-center">Tidak ada transaksi pada periode ini.</div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
