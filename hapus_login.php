<?php
include 'koneksi.php';

if (isset($_GET['id_user'])) {
    $id_user = $_GET['id_user'];

    // Hapus user dari database
    $hapus = mysqli_query($conn, "DELETE FROM users WHERE id_user = '$id_user'");

    if ($hapus) {
        echo "<script>alert('User berhasil dihapus!'); window.location.href='index_login.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus user.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('ID User tidak ditemukan.'); window.history.back();</script>";
}
?>
