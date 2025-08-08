<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_menu = $_POST['id_menu'];
    $nama_menu = $_POST['nama_menu'];
    $harga_menu = $_POST['harga_menu'];

    $gambar_menu = '';
    if (isset($_FILES['gambar_menu']) && $_FILES['gambar_menu']['error'] === UPLOAD_ERR_OK) {
        $nama_file = $_FILES['gambar_menu']['name'];
        $tmp_file = $_FILES['gambar_menu']['tmp_name'];

        $ext_valid = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        if (in_array($ext, $ext_valid)) {
            $gambar_menu = uniqid() . '.' . $ext;
            move_uploaded_file($tmp_file, 'gambar_menu/' . $gambar_menu);
        } else {
            echo "<script>alert('Hanya file JPG, JPEG, dan PNG yang diperbolehkan'); window.history.back();</script>";
            exit;
        }
    }

    $query = "INSERT INTO menu (id_menu, nama_menu, harga_menu, gambar_menu)
              VALUES ('$id_menu', '$nama_menu', '$harga_menu','$gambar_menu')";

    if (mysqli_query($conn, $query)) {
        header("Location: index_menu.php");
        exit;
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('FOTO_GRIYA_DAHAR.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 650px;
            background-color: rgba(255, 255, 255, 0.92);
            border-radius: 10px;
            padding: 30px;
            margin-top: 50px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
        }

        h3 {
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        label {
            font-weight: 500;
        }

        .btn i {
            margin-right: 5px;
        }

        .btn + .btn {
            margin-left: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h3>🍽️ Tambah Menu Baru</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="id_menu" class="form-label">ID Menu</label>
            <input type="text" name="id_menu" id="id_menu" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="nama_menu" class="form-label">Nama Menu</label>
            <input type="text" name="nama_menu" id="nama_menu" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="harga_menu" class="form-label">Harga Menu</label>
            <input type="number" name="harga_menu" id="harga_menu" class="form-control" required>
        </div>
        <div class="mb-4">
            <label for="gambar_menu" class="form-label">Gambar Menu</label>
            <input type="file" name="gambar_menu" id="gambar_menu" class="form-control" accept=".jpg,.jpeg,.png" required>
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Simpan
            </button>
            <button type="reset" class="btn btn-danger">
                <i class="fas fa-undo"></i> Reset
            </button>
            <a href="index_menu.php" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>
</body>
</html>
