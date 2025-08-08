<?php
include 'koneksi.php';

$id_penjualan = $_POST['id_penjualan'];
$jumlah = $_POST['jumlah_dibayar'];
$tanggal = $_POST['tanggal_pembayaran'];

// 1. Ambil nomor struk terakhir yang angkanya di akhir
$result = mysqli_query($conn, "
    SELECT MAX(CAST(SUBSTRING_INDEX(struk, '-', -1) AS UNSIGNED)) AS max_struk 
    FROM pembayaran
");
$row = mysqli_fetch_assoc($result);
$last_number = $row['max_struk'] ?? 0;

// 2. Buat nomor struk baru
$new_number = $last_number + 1;
$no_struk_baru = 'TRX-' . str_pad($new_number, 3, '0', STR_PAD_LEFT);

// 3. Simpan ke database
$query = mysqli_query($conn, "
    INSERT INTO pembayaran (id_penjualan, jumlah_dibayar, tanggal_pembayaran, struk)
    VALUES ('$id_penjualan', '$jumlah', '$tanggal', '$no_struk_baru')
");
if ($query) {
    echo "<script>
        alert('Pembayaran berhasil disimpan!');
        window.location.href = 'dashboard_kasir.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal menyimpan pembayaran!');
        window.history.back();
    </script>";
}
?>
