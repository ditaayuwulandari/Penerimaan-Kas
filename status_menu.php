<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($conn, "SELECT status FROM menu WHERE id_menu = '$id'");
    $row = mysqli_fetch_assoc($query);

    $new_status = ($row['status'] == 'aktif') ? 'nonaktif' : 'aktif';

    mysqli_query($conn, "UPDATE menu SET status = '$new_status' WHERE id_menu = '$id'");
}

header('Location: index_menu.php');
exit;
