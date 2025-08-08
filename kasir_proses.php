<?php
include 'koneksi.php';

// Ambil data dari JSON body
$data = json_decode(file_get_contents("php://input"), true);

$customer = $data['customer'];
$keranjang = $data['keranjang'];
$kasir = $data['kasir'];

// Validasi awal
if (!$customer || !$keranjang || !$kasir) {
    echo json_encode(["success" => false, "message" => "Data tidak lengkap"]);
    exit;
}

// Cek apakah customer sudah ada
$query = mysqli_query($conn, "SELECT id_customer FROM customer WHERE email = '{$customer['email']}'");
if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
    $id_customer = $row['id_customer'];
} else {
    $id_customer = uniqid("CST");
    mysqli_query($conn, "INSERT INTO customer (id_customer, nama_customer, email, no_hp)
                         VALUES ('$id_customer', '{$customer['nama']}', '{$customer['email']}', '{$customer['hp']}')");
}

// Simpan ke tabel penjualan
$id_penjualan = uniqid("PNJ");
$tanggal = date("Y-m-d");
$total = 0;
foreach ($keranjang as $item) {
    $total += $item['harga'] * $item['jumlah'];
}
mysqli_query($conn, "INSERT INTO penjualan (id_penjualan, id_customer, tanggal_penjualan, total_harga, kasir)
                     VALUES ('$id_penjualan', '$id_customer', '$tanggal', $total, '$kasir')");

// Simpan ke detail_penjualan
foreach ($keranjang as $item) {
    $subtotal = $item['harga'] * $item['jumlah'];
    mysqli_query($conn, "INSERT INTO detail_penjualan (id_penjualan, id_menu, jumlah, harga, subtotal)
                         VALUES ('$id_penjualan', '{$item['id']}', {$item['jumlah']}, {$item['harga']}, $subtotal)");
}

// Simpan ke penerimaan_kas
$id_penerimaan = uniqid("KAS");
mysqli_query($conn, "INSERT INTO penerimaan_kas (id_penerimaan, id_penjualan, tanggal_penerimaan, jumlah_penerimaan_kas, keterangan)
                     VALUES ('$id_penerimaan', '$id_penjualan', '$tanggal', $total, 'Pembayaran via kasir oleh $kasir')");

echo json_encode(["success" => true, "message" => "Data berhasil disimpan"]);
?>
