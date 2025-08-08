<?php
include 'koneksi.php';

// Fungsi untuk membersihkan input dari karakter aneh
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Ambil dan sanitasi input
$id_user    = mysqli_real_escape_string($conn, clean_input($_POST['id_user']));
$nama_user  = mysqli_real_escape_string($conn, clean_input($_POST['nama_user']));
$email      = mysqli_real_escape_string($conn, clean_input($_POST['email']));
$password   = mysqli_real_escape_string($conn, $_POST['password']);
$role       = mysqli_real_escape_string($conn, clean_input($_POST['role']));

// Validasi input kosong
if (empty($id_user) || empty($nama_user) || empty($email) || empty($password) || empty($role)) {
    echo "<script>alert('❌ Semua field harus diisi!'); window.history.back();</script>";
    exit;
}

// Validasi format email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('❌ Format email tidak valid!'); window.history.back();</script>";
    exit;
}

// Validasi panjang password minimal 6 karakter
if (strlen($password) < 6) {
    echo "<script>alert('❌ Password minimal 6 karakter!'); window.history.back();</script>";
    exit;
}

// Cek apakah ID user sudah digunakan
$cek = mysqli_query($conn, "SELECT id_user FROM users WHERE id_user = '$id_user'");
if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('❌ ID \"$id_user\" sudah digunakan. Gunakan ID lain.'); window.history.back();</script>";
    exit;
}

// Hash password sebelum disimpan ke database
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Simpan user baru
$query = "INSERT INTO users (id_user, nama_user, email, password, role)
          VALUES ('$id_user', '$nama_user', '$email', '$hashed_password', '$role')";

$simpan = mysqli_query($conn, $query);

if ($simpan) {
    echo "<script>alert('✅ User berhasil ditambahkan!'); window.location.href='index_login.php';</script>";
} else {
    // Tampilkan pesan error SQL jika ada kesalahan
    $error_msg = mysqli_error($conn);
    echo "<script>alert('❌ Gagal menyimpan user: $error_msg'); window.history.back();</script>";
}
?>
