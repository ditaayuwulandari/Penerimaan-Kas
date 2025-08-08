<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

// Redirect berdasarkan role
$dashboard = ($_SESSION['role'] == 'admin') ? 'dashboard_admin.php'
            : (($_SESSION['role'] == 'owner') ? 'dashboard_owner.php'
            : 'dashboard_kasir.php');

$result = mysqli_query($conn, "SELECT * FROM customer");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Customer</title>

    <!-- Bootstrap & Font Awesome -->
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
            background: rgba(255, 255, 255, 0.85);
            padding: 30px;
            border-radius: 10px;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }
    </style>
</head>
<body>

<div class="container shadow">
    <h2 class="text-center mb-4">📇 Daftar Customer</h2>

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-primary text-center">
            <tr>
                <th>Nama</th>
                <th>No HP</th>
                <th>Email</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= htmlspecialchars($row['nama_customer']) ?></td>
                <td><?= htmlspecialchars($row['no_hp']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['alamat']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="mt-3">
        <a href="<?= $dashboard ?>" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

</body>
</html>
