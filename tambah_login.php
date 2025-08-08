<?php include 'koneksi.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah User Baru</title>
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
            max-width: 600px;
            background: rgba(255, 255, 255, 0.85);
            padding: 30px;
            border-radius: 10px;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }
        h3 {
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-text {
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>

<div class="container shadow">
    <h3>➕ Tambah User Baru</h3>

    <form action="simpan_login.php" method="POST">
        <div class="mb-3">
            <label for="id_user" class="form-label">ID User</label>
            <input type="text" name="id_user" id="id_user" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="nama_user" class="form-label">Nama User</label>
            <input type="text" name="nama_user" id="nama_user" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
            <div class="form-text">Password akan otomatis di-hash untuk keamanan.</div>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-select" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin</option>
                <option value="owner">Owner</option>
                <option value="kasir">Kasir</option>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="index_login.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Batal
            </a>
        </div>
    </form>
</div>

</body>
</html>
