<?php
include 'koneksi.php';

$id_user = $_GET['id_user'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'");
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data User</title>
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
            max-width: 600px;
            background: rgba(255, 255, 255, 0.85);
            padding: 30px;
            margin-top: 50px;
            border-radius: 10px;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }
    </style>
</head>
<body>

<div class="container shadow">
    <h3 class="text-center mb-4">✏️ Edit Data User</h3>

    <form action="update_login.php" method="POST">
        <input type="hidden" name="id_user" value="<?= $data['id_user'] ?>">

        <div class="mb-3">
            <label for="nama_user" class="form-label">Nama</label>
            <input type="text" name="nama_user" class="form-control" id="nama_user" value="<?= $data['nama_user'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="email" value="<?= $data['email'] ?? '' ?>" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Peran</label>
            <select name="role" class="form-select" id="role" required>
                <option value="admin" <?= $data['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="owner" <?= $data['role'] == 'owner' ? 'selected' : '' ?>>Owner</option>
                <option value="kasir" <?= $data['role'] == 'kasir' ? 'selected' : '' ?>>Kasir</option>
            </select>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="index_login.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Batal
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

</body>
</html>
