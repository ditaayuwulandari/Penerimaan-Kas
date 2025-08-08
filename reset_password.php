<?php
include 'koneksi.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_user     = mysqli_real_escape_string($conn, $_POST['nama_user']);
    $email         = mysqli_real_escape_string($conn, $_POST['email']);
    $new_pass      = $_POST['new_password'];
    $confirm_pass  = $_POST['confirm_password'];

    // Cek user & email cocok
    $query = mysqli_query($conn, "SELECT * FROM users WHERE nama_user='$nama_user' AND email='$email'");
    $data = mysqli_fetch_assoc($query);

    if (!$data) {
        $message = "Username atau Email tidak ditemukan.";
    } elseif ($new_pass !== $confirm_pass) {
        $message = "Password dan konfirmasi tidak cocok.";
    } elseif (strlen($new_pass) < 6) {
        $message = "Password minimal 6 karakter.";
    } else {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $update = mysqli_query($conn, "UPDATE users SET password='$hashed_pass' WHERE nama_user='$nama_user'");
        if ($update) {
            echo "<script>alert('Password berhasil direset! Silakan login.'); window.location.href='login.php';</script>";
            exit;
        } else {
            $message = "Gagal menyimpan password baru.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f0f0; }
        .container {
            margin-top: 100px;
            max-width: 400px;
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .btn-green { background-color: #388E3C; color: white; }
        .btn-green:hover { background-color: #2E7D32; }
    </style>
</head>
<body>
<div class="container">
    <h4 class="text-center mb-4">Reset Password</h4>

    <?php if ($message): ?>
        <div class="alert alert-danger"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="nama_user" class="form-label">Username:</label>
            <input type="text" name="nama_user" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email Terdaftar:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">Password Baru:</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Konfirmasi Password:</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-green w-100">Reset Password</button>
    </form>

    <div class="mt-3 text-center">
        <a href="login.php">← Kembali ke Login</a>
    </div>
</div>
</body>
</html>
