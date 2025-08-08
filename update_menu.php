<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_user = $_POST['nama_user'] ?? '';
    $password  = $_POST['password'] ?? '';
    $role      = $_POST['role'] ?? '';

    if (empty($nama_user) || empty($password) || empty($role)) {
        echo "<script>alert('Semua field harus diisi!'); window.location.href='login.php';</script>";
        exit;
    }

    // Ambil user berdasarkan nama_user dan role
    $query = mysqli_query($conn, "SELECT * FROM users WHERE nama_user = '$nama_user' AND role = '$role'");
    $user = mysqli_fetch_assoc($query);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            // Login sukses
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama_user'] = $user['nama_user'];
            $_SESSION['role'] = $user['role'];

            // Redirect sesuai role
            if ($user['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            } elseif ($user['role'] == 'owner') {
                header("Location: owner/dashboard.php");
            } elseif ($user['role'] == 'kasir') {
                header("Location: kasir/dashboard.php");
            } else {
                echo "<script>alert('Role tidak dikenal'); window.location.href='login.php';</script>";
            }
        } else {
            echo "<script>alert('Password salah!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('User tidak ditemukan atau role salah!'); window.location.href='login.php';</script>";
    }
}
?>
