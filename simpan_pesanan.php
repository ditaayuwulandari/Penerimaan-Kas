<?php
include 'koneksi.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
    exit;
}

// Ambil data
$keranjang = $data['keranjang'];
$kasir = $data['kasir'];
$customer = $data['customer'];

// Generate ID
function generateId($prefix) {
    return $prefix . strtoupper(substr(uniqid(), -6));
}

$id_customer = generateId("CST");
$id_penjualan = generateId("PNJ");

// Simpan customer
$sql_customer = "INSERT INTO customer (id_customer, nama_customer, no_hp, email, alamat) VALUES (
    '$id_customer',
    '".mysqli_real_escape_string($conn, $customer['nama'])."',
    '".mysqli_real_escape_string($conn, $customer['hp'])."',
    '".mysqli_real_escape_string($conn, $customer['email'])."',
    '".mysqli_real_escape_string($conn, $customer['alamat'])."'
)";

if (!mysqli_query($conn, $sql_customer)) {
    echo json_encode(['success' => false, 'message' => 'Gagal simpan customer: ' . mysqli_error($conn)]);
    exit;
}

// Simpan penjualan
$sql_penjualan = "INSERT INTO penjualan (id_penjualan, id_customer, tanggal_penjualan, kasir) VALUES (
    '$id_penjualan',
    '$id_customer',
    NOW(),
    '".mysqli_real_escape_string($conn, $kasir)."'
)";

if (!mysqli_query($conn, $sql_penjualan)) {
    echo json_encode(['success' => false, 'message' => 'Gagal simpan penjualan: ' . mysqli_error($conn)]);
    exit;
}

// Simpan detail penjualan
foreach ($keranjang as $item) {
    $id_detail = generateId("DTL");
    $id_menu = mysqli_real_escape_string($conn, $item['id']);
    $harga = (int)$item['harga'];
    $jumlah = (int)$item['jumlah'];
    $subtotal = $harga * $jumlah;

    $sql_detail = "INSERT INTO detail_penjualan (id_detail, id_penjualan, id_menu, jumlah, harga_satuan, subtotal) VALUES (
        '$id_detail',
        '$id_penjualan',
        '$id_menu',
        $jumlah,
        $harga,
        $subtotal
    )";

    if (!mysqli_query($conn, $sql_detail)) {
        echo json_encode(['success' => false, 'message' => 'Gagal insert detail: ' . mysqli_error($conn)]);
        exit;
    }
}

echo json_encode(['success' => true, 'id_penjualan' => $id_penjualan]);
?>
