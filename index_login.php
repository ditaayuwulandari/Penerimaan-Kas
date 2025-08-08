<?php
include 'koneksi.php';
$result = mysqli_query($conn, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar User</title>
    
    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-image: url('FOTO_GRIYA_DAHAR.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 20px;
        }

        .container {
            margin-top: 40px;
            background: rgba(255, 255, 255, 0.92);
            padding: 30px;
            border-radius: 10px;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }

        .btn-sm i {
            margin-right: 5px;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        h3 {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container shadow">
    <h3 class="text-center mb-4">🔐 Daftar User & Password</h3>

    <div class="mb-3 d-flex justify-content-between">
        <a href="tambah_login.php" class="btn btn-success">
            <i class="fas fa-user-plus"></i> Tambah User
        </a>
        <a href="dashboard_admin.php" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID User</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Password</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id_user']) ?></td>
                        <td><?= htmlspecialchars($row['nama_user']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td>
                            <span class="text-muted">••••••</span>
                        </td>
                        <td>
                            <a href="edit_login.php?id_user=<?= $row['id_user'] ?>" class="btn btn-warning btn-sm mb-1">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="hapus_login.php?id_user=<?= $row['id_user'] ?>" 
                               onclick="return confirm('Yakin ingin menghapus user ini?')"
                               class="btn btn-danger btn-sm">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
