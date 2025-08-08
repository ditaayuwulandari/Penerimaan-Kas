<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_user = $_POST['nama_user'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    if (empty($nama_user) || empty($password) || empty($role)) {
        echo "<script>alert('Semua field harus diisi!'); window.location.href='login.php';</script>";
        exit;
    }

    // Ambil data user berdasarkan nama dan role
    $query = mysqli_query($conn, "SELECT * FROM users WHERE nama_user='$nama_user' AND role='$role'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        // Cek password
        if (password_verify($password, $data['password'])) {
            $_SESSION['id_user'] = $data['id_user'];
            $_SESSION['nama_user'] = $data['nama_user'];
            $_SESSION['role'] = $data['role'];

            if ($data['role'] === 'admin') {
                header('Location: dashboard_admin.php');
            } elseif ($data['role'] === 'owner') {
                header('Location: dashboard_owner.php');
            } elseif ($data['role'] === 'kasir') {
                header('Location: dashboard_kasir.php');
            } else {
                echo "<script>alert('Role tidak dikenali!'); window.location.href='login.php';</script>";
            }
            exit;
        } else {
            echo "<script>alert('Password salah!'); window.location.href='login.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('User tidak ditemukan atau role salah!'); window.location.href='login.php';</script>";
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Sistem</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primary: #4CAF50;
            --primary-dark: #388E3C;
        }

        body {
            margin: 0;
            padding: 0;
            background: url('FOTO_GRIYA_DAHAR.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: rgba(0,0,0,0.2) 0px 8px 16px;
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: var(--primary);
        }

        .form-group {
            margin-bottom: 20px;
        }

        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: var(--primary);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: var(--primary-dark);
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>🔐 Login Sistem</h2>
    <h2>Selamat datang Di Sistem Penjualan Griya Dahar Mbok Sum</h2>
    <form method="POST" action="login.php">
        <div class="form-group">
            <input type="text" name="nama_user" placeholder="Username" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="form-group">
            <select name="role" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin</option>
                <option value="owner">Owner</option>
                <option value="kasir">Kasir</option>
            </select>
        </div>
        <button type="submit">Masuk</button>
    </form>
    </form>

    <!-- Bagian tambahan -->
<div style="text-align: center; margin-top: 10px;">
    <a href="reset_password.php" style="color: #388E3C; text-decoration: none; font-size: 14px;">
        🔒 Ubah/buat password di sini
    </a>
</div>
    
</div>

</body>
</html>
