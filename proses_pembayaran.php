<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_penjualan = $_POST['id_penjualan'] ?? '';
    $uang_dibayar = $_POST['uang'] ?? 0;

    if (!$id_penjualan || !$uang_dibayar) {
        die("Data tidak lengkap.");
    }

    // Hitung total dari detail_penjualan
    $query_total = mysqli_query($conn, "
        SELECT SUM(subtotal) AS total 
        FROM detail_penjualan 
        WHERE id_penjualan = '$id_penjualan'
    ");
    if (!$query_total) {
        die("Gagal menghitung total: " . mysqli_error($conn));
    }

    $data_total = mysqli_fetch_assoc($query_total);
    $total = $data_total['total'] ?? 0;

    if ($total == 0) {
        die("Total penjualan tidak ditemukan atau 0.");
    }

    // Cek apakah sudah ada pembayaran untuk penjualan ini
    $cek_pembayaran = mysqli_query($conn, "SELECT * FROM pembayaran WHERE id_penjualan = '$id_penjualan'");
    if (mysqli_num_rows($cek_pembayaran) == 0) {
        // Generate ID pembayaran otomatis (format BYR001, BYR002, dst)
        $last_id = mysqli_query($conn, "SELECT MAX(id_pembayaran) AS max_id FROM pembayaran");
        $data_id = mysqli_fetch_assoc($last_id);
        $kode_terakhir = $data_id['max_id'] ?? 'BYR000';
        $urutan = (int) substr($kode_terakhir, 3) + 1;
        $id_pembayaran = 'BYR' . str_pad($urutan, 3, '0', STR_PAD_LEFT);

        // Generate nomor struk unik (format TRX-001, TRX-002, dst)
        $result_struk = mysqli_query($conn, "
            SELECT MAX(CAST(SUBSTRING_INDEX(struk, '-', -1) AS UNSIGNED)) AS max_struk 
            FROM pembayaran
        ");
        $row_struk = mysqli_fetch_assoc($result_struk);
        $last_number = $row_struk['max_struk'] ?? 0;
        $new_number = $last_number + 1;
        $no_struk_baru = 'TRX-' . str_pad($new_number, 3, '0', STR_PAD_LEFT);

        $tanggal = date('Y-m-d');
        $keterangan = '';

        // Simpan pembayaran ke DB
        $simpan = mysqli_query($conn, "
            INSERT INTO pembayaran 
            (id_pembayaran, id_penjualan, jumlah_dibayar, tanggal_pembayaran, struk)
            VALUES 
            ('$id_pembayaran', '$id_penjualan', '$uang_dibayar', '$tanggal', '$no_struk_baru')
        ");

        if (!$simpan) {
            die("Gagal menyimpan pembayaran: " . mysqli_error($conn));
        }
    } else {
        die("Pembayaran untuk penjualan ini sudah ada.");
    }

    // Redirect ke halaman struk untuk lihat detail pembayaran
    header("Location: struk.php?id_penjualan=$id_penjualan");
    exit;
} else {
    die("Akses tidak valid.");
}
?>
