<?php
include 'koneksi.php';

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM menu WHERE id_menu = '$id'");
$row = mysqli_fetch_assoc($data);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('foto_griya_dahar.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4>✏️ Edit Data Menu</h4>
        </div>
        <div class="card-body">
            <form action="update_menu.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_menu" value="<?= $row['id_menu'] ?>">
                
                <div class="mb-3">
                    <label class="form-label">Nama Menu</label>
                    <input type="text" name="nama_menu" class="form-control" value="<?= htmlspecialchars($row['nama_menu']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="harga_menu" class="form-control" value="<?= $row['harga_menu'] ?>" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Gambar Sekarang</label><br>
                    <?php if (!empty($row['gambar_menu']) && file_exists("gambar_menu/" . $row['gambar_menu'])): ?>
                        <img src="gambar_menu/<?= htmlspecialchars($row['gambar_menu']) ?>" width="150" class="img-thumbnail mb-2">
                    <?php else: ?>
                        <p class="text-muted">(Tidak ada gambar)</p>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ganti Gambar (Opsional)</label>
                    <input type="file" name="gambar_menu" class="form-control">
                </div>

                <div class="d-flex justify-content-between">
                    <a href="index_menu.php" class="btn btn-success">Kembali</a>
                    <button type="submit" class="btn btn-success">💾 Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
