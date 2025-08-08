<?php
include 'koneksi.php';

$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$where = "";
if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
    $where = "WHERE tanggal_penjualan BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
}

$query_penjualan = mysqli_query($conn, "SELECT * FROM penjualan $where ORDER BY tanggal_penjualan DESC");

$penjualan = [];
while ($p = mysqli_fetch_assoc($query_penjualan)) {
    $id = $p['id_penjualan'];
    $penjualan[$id] = [
        'tanggal' => $p['tanggal_penjualan'],
        'items' => []
    ];

    $query_detail = mysqli_query($conn, "
        SELECT dp.*, m.nama_menu, m.status 
        FROM detail_penjualan dp 
        JOIN menu m ON dp.id_menu = m.id_menu
        WHERE dp.id_penjualan = '$id'
    ");

    while ($d = mysqli_fetch_assoc($query_detail)) {
        $penjualan[$id]['items'][] = [
            'nama_menu' => $d['nama_menu'],
            'jumlah' => $d['jumlah'],
            'harga' => $d['harga_satuan'],
            'subtotal' => $d['subtotal']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-image: url('FOTO_GRIYA_DAHAR.JPG');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 960px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-weight: bold;
            font-size: 28px;
        }

        .form-filter {
            margin-bottom: 30px;
        }

        .penjualan {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            margin-bottom: 30px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .penjualan-info {
            background: #f8f9fa;
            padding: 14px 20px;
            font-size: 16px;
            font-weight: 500;
            color: #333;
            border-bottom: 1px solid #dee2e6;
        }

        .penjualan-header {
            background: #198754;
            color: #fff;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 600;
        }

        table {
            width: 100%;
            margin: 0;
        }

        th, td {
            padding: 12px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #f1f3f5;
            font-weight: 600;
            color: #495057;
        }

        .total {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .alert {
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="fas fa-receipt me-2"></i>Riwayat Detail Penjualan</h2>

    <!-- Filter Form -->
    <form method="GET" class="form-filter row g-3 align-items-end">
        <div class="col-md-4">
            <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
            <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" value="<?= htmlspecialchars($tanggal_awal) ?>">
        </div>
        <div class="col-md-4">
            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="<?= htmlspecialchars($tanggal_akhir) ?>">
        </div>
        <div class="col-md-4 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-success w-100 btn-icon">
                <i class="fas fa-search"></i> Tampilkan
            </button>
            <a href="dashboard_admin.php" class="btn btn-secondary w-100 btn-icon">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>

    <!-- Penjualan -->
    <?php if (empty($penjualan)): ?>
        <div class="alert alert-warning text-center">
            <i class="fas fa-exclamation-circle me-1"></i> Tidak ada data penjualan pada rentang tanggal tersebut.
        </div>
    <?php else: ?>
        <?php foreach ($penjualan as $id => $data): ?>
            <div class="penjualan">
            <div class="penjualan-info">
                <div class="row">
                    <div class="col-12 text-md-end">
                        <i class="fas fa-calendar-alt me-1"></i> 
                        Tanggal: <strong><?= date('d-m-Y', strtotime($data['tanggal'])) ?></strong>
                    </div>
                </div>
            </div>
                <div class="penjualan-header">Detail Menu</div>
                <table class="table table-bordered table-hover table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Nama Menu</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        foreach ($data['items'] as $item): 
                            $total += $item['subtotal'];
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($item['nama_menu']) ?></td>
                                <td><?= $item['jumlah'] ?></td>
                                <td>Rp<?= number_format($item['harga'], 0, ',', '.') ?></td>
                                <td>Rp<?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="total">
                            <td colspan="3">Total</td>
                            <td>Rp<?= number_format($total, 0, ',', '.') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
