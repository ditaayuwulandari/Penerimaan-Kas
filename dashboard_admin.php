<?php
session_start();
include 'koneksi.php';

$menu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM menu"))['total'] ?? 0;
$customer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM customer"))['total'] ?? 0;
$users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'] ?? 0;

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('FOTO_GRIYA_DAHAR.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }
        h2 {
            font-weight: bold;
            color: #fff;
            margin-bottom: 40px;
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
        .card-box h3 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        .card-box a {
            font-size: 1rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container mt-4">
<h2 class="text-center mb-4 fw-bold" style="background: linear-gradient(to right, #28a745, #218838); padding: 15px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);">
    🛒 Dashboard Admin
</h2>
<div class="text-end mb-3">
  <a href="logout.php" class="btn btn-danger">Logout</a>
</div>

<div class="row g-4 justify-content-center">
    <div class="col-md-4">
        <div class="card-box bg-primary text-center">
            <h5>Total Menu</h5>
            <h3><?= $menu ?></h3>
            <a href="index_menu.php" class="btn btn-light btn-sm w-100">Kelola</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-box bg-warning text-center">
            <h5>Total Customer</h5>
            <h3><?= $customer ?></h3>
            <a href="index_customer.php" class="btn btn-light btn-sm w-100">Kelola</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-box bg-dark text-center">
            <h5>Total User</h5>
            <h3><?= $users ?></h3>
            <a href="index_login.php" class="btn btn-light btn-sm w-100">Kelola Login</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-box bg-success text-center">
            <h5>Penjualan</h5>
            <a href="index_penjualan.php" class="btn btn-light btn-sm w-100 mt-4">Kelola</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-box bg-info text-center">
            <h5>Detail Penjualan</h5>
            <a href="index_detail_penjualan.php" class="btn btn-light btn-sm w-100 mt-4">Kelola</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-box bg-secondary text-center">
            <h5>Pembayaran</h5>
            <a href="index_pembayaran.php" class="btn btn-light btn-sm w-100 mt-4">Kelola</a>
        </div>
    </div>
</div>

</div>
</body>
</html>
