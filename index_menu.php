<?php
include 'koneksi.php';
$result = mysqli_query($conn, "SELECT * FROM menu");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Menu</title>
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
        img {
            width: 100px;
            height: auto;
            object-fit: cover;
        }
    </style>
</head>
<body>

<div class="container shadow">
    <h2 class="text-center mb-4">📋 Daftar Menu</h2>

    <div class="mb-3 d-flex justify-content-between">
        <a href="input_menu.php" class="btn btn-success">
            <i class="fas fa-plus"></i> Tambah Menu Baru
        </a>
        <a href="dashboard_admin.php" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-primary text-center">
            <tr>
                <th>ID</th>
                <th>Nama Menu</th>
                <th>Harga</th>
                <th>Gambar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= htmlspecialchars($row['id_menu']) ?></td>
                <td><?= htmlspecialchars($row['nama_menu']) ?></td>
                <td>Rp <?= number_format($row['harga_menu'], 0, ',', '.') ?></td>
                <td>
                    <?php if (!empty($row['gambar_menu']) && file_exists("gambar_menu/" . $row['gambar_menu'])): ?>
                        <img src="gambar_menu/<?= htmlspecialchars($row['gambar_menu']) ?>" alt="Gambar Menu" class="img-thumbnail">
                    <?php else: ?>
                        <img src="gambar_menu/default.png" alt="Tidak ada gambar" class="img-thumbnail">
                    <?php endif; ?>
                </td>
                <td>
                    <span class="badge <?= $row['status'] == 'aktif' ? 'bg-success' : 'bg-secondary' ?>">
                        <?= ucfirst($row['status']) ?>
                    </span>
                </td>
                <td>
                    <a href="edit_menu.php?id=<?= $row['id_menu'] ?>" class="btn btn-warning btn-sm mb-1">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="status_menu.php?id=<?= $row['id_menu'] ?>" 
                       onclick="return confirm('Ubah status menu ini?')"
                       class="btn btn-info btn-sm">
                        <?= $row['status'] == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
