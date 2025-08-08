<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id_penjualan'])) {
    die("ID penjualan tidak ditemukan.");
}

$id = $_GET['id_penjualan'];

// Ambil data penjualan dan customer (termasuk nama kasir dari tabel penjualan)
$q1 = mysqli_query($conn, "
    SELECT p.*, c.nama_customer 
    FROM penjualan p
    JOIN customer c ON p.id_customer = c.id_customer
    WHERE p.id_penjualan = '$id'
");

$data = mysqli_fetch_assoc($q1);

// Ambil detail penjualan dan nama menu
$detail2 = mysqli_query($conn, "
    SELECT dp.*, m.nama_menu, m.harga_menu 
    FROM detail_penjualan dp
    JOIN menu m ON dp.id_menu = m.id_menu
    WHERE dp.id_penjualan = '$id'
");

// Ambil data pembayaran untuk jumlah dibayar
$q2 = mysqli_query($conn, "
    SELECT * FROM pembayaran 
    WHERE id_penjualan = '$id'
");
$data_bayar = mysqli_fetch_assoc($q2);
$jumlah_dibayar = $data_bayar['jumlah_dibayar'] ?? 0;

// Tentukan link dashboard berdasarkan role
$role = $_SESSION['role'] ?? '';
if ($role == 'admin') {
    $dashboard = 'dashboard_admin.php';
} elseif ($role == 'owner') {
    $dashboard = 'dashboard_owner.php';
} elseif ($role == 'kasir') {
    $dashboard = 'dashboard_kasir.php';
} else {
    $dashboard = 'index.php';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background: url('foto_griya_dahar.jpg') no-repeat center center fixed;
            background-size: cover;
            padding: 20px;
        }
        .struk {
            max-width: 400px;
            margin: auto;
            background-color: white;
            border: 1px dashed #333;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }
        .text-center {
            text-align: center;
        }
        table {
            width: 100%;
            font-size: 14px;
            border-collapse: collapse;
        }
        th, td {
            padding: 4px;
            border: 1px solid #ddd;
        }
        .total {
            font-weight: bold;
        }
        .btn-kembali, .btn-cetak {
            margin-top: 20px;
        }
        hr {
            border: 1px dashed #333;
        }
        @media print {
            .btn-kembali, .btn-cetak {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="struk">
    <div class="text-center">
        <h5>GRIYA DAHAR</h5>
        <p>Jalan Mangunan KM.4.5, RT.15, Mangunan, Dlingo, Cempluk, Kec. Dlingo, Yogyakarta <br>Yogyakarta</p>
        <p>Kontak: 0819-3170-9303</p>
        <hr>
        <p>
            <strong>Struk Penjualan</strong><br>
            No. Transaksi: <?= htmlspecialchars($data['id_penjualan']) ?><br>
            Tanggal: <?= htmlspecialchars($data['tanggal_penjualan']) ?><br>
            Nama Customer: <?= htmlspecialchars($data['nama_customer']) ?><br>
            Kasir: <?= htmlspecialchars($data['kasir'] ?? '-') ?>
        </p>
        <hr>
    </div>

    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $total = 0;
        while ($d = mysqli_fetch_assoc($detail2)) {
            $subtotal = $d['harga_menu'] * $d['jumlah'];
            $total += $subtotal;
        ?>
            <tr>
                <td><?= htmlspecialchars($d['nama_menu']) ?></td>
                <td><?= $d['jumlah'] ?></td>
                <td><?= number_format($d['harga_menu']) ?></td>
                <td><?= number_format($subtotal) ?></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="total">TOTAL BAYAR</td>
                <td class="total"><?= number_format($total) ?></td>
            </tr>
            <tr>
                <td colspan="3" class="total">JUMLAH DIBAYAR</td>
                <td class="total"><?= number_format($jumlah_dibayar) ?></td>
            </tr>
            <tr>
                <td colspan="3" class="total">KEMBALIAN</td>
                <td class="total"><?= number_format($jumlah_dibayar - $total) ?></td>
            </tr>
        </tfoot>
    </table>

    <hr>

    <div class="text-center mt-3">
        <p>Terima kasih telah berbelanja!<br>~ GRIYA DAHAR ~</p>
    </div>

    <div class="text-center btn-cetak">
        <button onclick="window.print()" class="btn btn-success">🖨️ Cetak Struk</button>
    </div>

    <div class="text-center btn-kembali">
        <a href="<?= $dashboard ?>" class="btn btn-primary">🔙 Kembali ke Dashboard</a>
    </div>
</div>

</body>
</html>
