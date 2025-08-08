<?php
include 'koneksi.php';

$id_penjualan = $_GET['id_penjualan'];

$hapus = mysqli_query($conn, "DELETE FROM penjualan WHERE id_penjualan = '$id_penjualan'");

if ($hapus) {
    echo "<script>alert('Data berhasil dihapus!'); window.location='index_penjualan.php';</script>";
} else {
    echo "Gagal menghapus data: " . mysqli_error($conn);
}
?>
