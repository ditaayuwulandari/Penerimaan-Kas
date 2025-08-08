<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Owner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('FOTO_GRIYA_DAHAR.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            font-family: Arial, sans-serif;
        }
        .card-box {
            border-radius: 20px;
            padding: 30px;
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
            height: 100%;
        }
        .card-box h5 {
            font-size: 1.2rem;
            margin-bottom: 15px;
        }
        .card-box a {
            font-size: 1rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center fw-bold text-white mb-5" style="background: linear-gradient(to right, #218838, #28a745); padding: 15px; border-radius: 12px;">
            📊 Dashboard Owner Griya Dahar Mbok Sum
        </h2>

        <div class="text-end mb-3">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Laporan Penjualan -->
            <div class="col-md-3">
                <div class="card-box bg-primary text-center">
                    <h5>Laporan Penjualan</h5>
                    <a href="laporan_penjualan.php" class="btn btn-light btn-sm w-100">Lihat Laporan</a>
                </div>
            </div>


            <!-- Laporan Menu Terlaris -->
            <div class="col-md-3">
                <div class="card-box bg-warning text-center">
                    <h5>Laporan Menu Terlaris</h5>
                    <a href="laporan_menu_terlaris.php" class="btn btn-light btn-sm w-100">Lihat Laporan</a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card-box bg-info text-center">
                    <h5>Laporan Jumlah Customer</h5>
                    <a href="laporan_customer.php" class="btn btn-light btn-sm w-100">Lihat Customer</a>
                </div>
            </div>
             <!-- Laporan Penerimaan Kas -->
             <div class="col-md-3">
                <div class="card-box bg-success text-center">
                    <h5>Laporan Penerimaan Kas</h5>
                    <a href="laporan_penerimaan_kas.php" class="btn btn-light btn-sm w-100">Lihat Laporan</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
