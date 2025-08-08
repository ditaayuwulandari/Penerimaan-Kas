<?php
include 'koneksi.php';

// Jika data dikirim melalui POST (form disubmit)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user    = mysqli_real_escape_string($conn, $_POST['id_user']);
    $nama_user  = mysqli_real_escape_string($conn, $_POST['nama_user']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);
    $password   = mysqli_real_escape_string($conn, $_POST['password']);
    $role       = mysqli_real_escape_string($conn, $_POST['role']);

    // Ambil data lama
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'");
    $user_lama = mysqli_fetch_assoc($result);

    if (!$user_lama) {
        die("User tidak ditemukan.");
    }

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $hashed_password = $user_lama['password']; // tidak diubah
    }
    
    // Update data user
    $query = "UPDATE users SET 
                nama_user = '$nama_user',
                email = '$email',
                password = '$hashed_password',
                role = '$role'
              WHERE id_user = '$id_user'";

    if (mysqli_query($conn, $query)) {
        header("Location: index_login.php?pesan=update_berhasil");
        exit;
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($conn);
    }
} else {
    // GET: Ambil data user berdasarkan ID dari URL
    if (isset($_GET['id_user'])) {
        $id = $_GET['id_user'];
        $result = mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id'");
        $user = mysqli_fetch_assoc($result);
        if (!$user) {
            die("User tidak ditemukan.");
        }
    } else {
        die("ID user tidak disediakan.");
    }
}
?>
