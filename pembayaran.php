<?php
include 'koneksi.php';

$id = mysqli_real_escape_string($conn, $_GET['id_penjualan']);

// Ambil data penjualan dan customer
$penjualan_result = mysqli_query($conn, "
    SELECT p.*, c.nama_customer 
    FROM penjualan p 
    JOIN customer c ON p.id_customer = c.id_customer 
    WHERE p.id_penjualan = '$id'
");

if (!$penjualan_result) {
    die("Query penjualan gagal: " . mysqli_error($conn));
}

$penjualan = mysqli_fetch_assoc($penjualan_result);

// Ambil detail penjualan + menu
$detail = mysqli_query($conn, "
    SELECT dp.*, m.nama_menu 
    FROM detail_penjualan dp
    JOIN menu m ON dp.id_menu = m.id_menu
    WHERE dp.id_penjualan = '$id'
");

if (!$detail) {
    die("Query detail gagal: " . mysqli_error($conn));
}

// Hitung total subtotal
$total = 0;
while ($d = mysqli_fetch_assoc($detail)) {
    $total += $d['subtotal'];
    $data_detail[] = $d; // Simpan data detail untuk ditampilkan ulang di tabel
}

// Update total ke tabel penjualan
$update_total = mysqli_query($conn, "
    UPDATE penjualan 
    SET total_bayar = '$total' 
    WHERE id_penjualan = '$id'
");

if (!$update_total) {
    die("Gagal update total_bayar: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('FOTO_GRIYA_DAHAR.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            border-radius: 15px;
            background-color: #ffffffd9;
            backdrop-filter: blur(5px);
            width: 100%;
            max-width: 800px;
        }

        .card-header {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        h3 {
            font-weight: bold;
        }

        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header bg-success text-white">
        <h3 class="mb-0">🧾 Pembayaran</h3>
    </div>
    <div class="card-body">
        <?php if ($penjualan): ?>
            <p><strong>👤 Customer:</strong> <?= htmlspecialchars($penjualan['nama_customer']) ?></p>
            <p><strong>👨‍💼 Kasir:</strong> <?= htmlspecialchars($penjualan['kasir']) ?></p>
            <p><strong>📅 Tanggal:</strong> <?= htmlspecialchars($penjualan['tanggal_penjualan']) ?></p>

            <div class="table-responsive mt-4">
                <table class="table table-bordered table-hover">
                    <thead class="table-success">
                        <tr>
                            <th>🍽️ Menu</th>
                            <th>💵 Harga</th>
                            <th>🔢 Jumlah</th>
                            <th>🧾 Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_detail as $d): ?>
                        <tr>
                            <td><?= htmlspecialchars($d['nama_menu']) ?></td>
                            <td>Rp <?= number_format($d['harga_satuan'], 0, ',', '.') ?></td>
                            <td><?= $d['jumlah'] ?></td>
                            <td>Rp <?= number_format($d['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="table-info fw-bold">
                            <td colspan="3" class="text-end">Total Bayar</td>
                            <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Form input uang diterima -->
            <form action="proses_pembayaran.php" method="POST" class="mt-4">
                <input type="hidden" name="id_penjualan" value="<?= htmlspecialchars($id) ?>">
                <div class="mb-3 row">
                    <label for="uang" class="col-sm-4 col-form-label"><strong>💰 Jumlah Bayar</strong></label>
                    <div class="col-sm-8">
                        <input type="number" name="uang" id="uang" class="form-control" placeholder="Contoh: 50000" required min="<?= $total ?>">
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary mt-3">💳 Bayar Sekarang</button>
                </div>
            </form>
        <?php else: ?>
            <div class="alert alert-danger">❌ Data penjualan tidak ditemukan.</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
