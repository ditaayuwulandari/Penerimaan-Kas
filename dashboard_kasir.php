<?php
include 'koneksi.php';
$menus = mysqli_query($conn, "SELECT * FROM menu WHERE status = 'aktif'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('FOTO_GRIYA_DAHAR.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
            background-color: #fff;
        }
        .card:hover {
            transform: scale(1.03);
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .cart {
            position: fixed;
            right: 20px;
            top: 80px;
            width: 320px;
            max-height: 90vh;
            overflow-y: auto;
            background-color: #ffffffee;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            z-index: 1000;
            border: 2px solid #198754;
        }
        .cart h5 {
            font-weight: bold;
            color: #198754;
            border-bottom: 2px solid #198754;
            padding-bottom: 8px;
            margin-bottom: 16px;
        }
        .cart h6 {
            text-align: right;
            margin-top: 16px;
            font-weight: bold;
        }
        .cart .list-group-item {
            background-color: #f8f9fa;
            border: none;
            border-bottom: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 8px;
            padding: 10px;
        }
        @media (max-width: 768px) {
            .cart {
                left: 0;
                right: 0;
                top: auto;
                bottom: 0;
                width: 100%;
                border-radius: 15px 15px 0 0;
            }
        }
        #daftarKeranjang li {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 15px;
            background-color: #fff;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .item-info {
            flex-grow: 1;
            font-size: 16px;
            line-height: 1.4;
        }
        .item-info strong {
            display: block;
            font-weight: 600;
        }
        .item-info small {
            color: #666;
            font-size: 14px;
        }
        .item-harga {
            font-weight: bold;
            font-size: 16px;
            white-space: nowrap;
            color: #333;
        }
    </style>
</head>
<body>
<div class="container my-4">
<h2 class="text-center mb-4 fw-bold" style="color: #ffffff; background: linear-gradient(to right, #28a745, #218838); padding: 15px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);">
    🛒 Dashboard Kasir
</h2>
<div class="text-end mb-3">
  <a href="logout.php" class="btn btn-danger">Logout</a>
</div>

    <div class="row g-3">
        <?php while ($menu = mysqli_fetch_assoc($menus)) { ?>
            <div class="col-md-3">
                <div class="card h-100">
                    <?php
                    $gambar = (!empty($menu['gambar_menu']) && file_exists("gambar_menu/" . $menu['gambar_menu']))
                        ? "gambar_menu/" . $menu['gambar_menu']
                        : "gambar_menu/default.png";
                    ?>
                    <img src="<?= $gambar ?>" class="card-img-top" alt="<?= $menu['nama_menu']; ?>">

                    <div class="card-body text-center d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title"><?= $menu['nama_menu']; ?></h5>
                            <p class="card-text">Rp <?= number_format($menu['harga_menu'], 0, ',', '.'); ?></p>
                        </div>
                        <button class="btn btn-success btn-sm mt-2"
                            onclick="tambahKeKeranjang(
                                '<?= $menu['id_menu']; ?>',
                                '<?= $menu['nama_menu']; ?>',
                                <?= $menu['harga_menu']; ?>
                            )">Tambah</button>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<!-- KERANJANG -->
<div class="cart" id="keranjang">
    <h5><i class="bi bi-cart4"></i> Penjualan</h5>
    <input type="text" id="namaKasir" class="form-control mb-3" placeholder="Nama Kasir" required>
    <h6 class="mt-3 mb-2">🡭 Detail penjualan</h6>
    <ul id="daftarKeranjang" class="list-group mb-2"></ul>
    <h6>Total: Rp <span id="totalBayar">0</span></h6>
    <hr>
    <h6 class="mt-3 mb-2">🡭 Data Customer</h6>
    <input type="text" id="namaCustomer" class="form-control mb-2" placeholder="Nama Customer" required>
    <input type="text" id="hpCustomer" class="form-control mb-2" placeholder="Nomor HP" required>
    <input type="email" id="emailCustomer" class="form-control mb-2" placeholder="Email" required>
    <input type="text" id="alamatCustomer" class="form-control mb-2" placeholder="Alamat" required>
    <button class="btn btn-success w-100" onclick="checkout()">📏 Tambah Penjualan</button>
</div>

<script>
let keranjang = [];

function tambahKeKeranjang(id, nama, harga) {
    const ada = keranjang.find(item => item.id === id);
    if (ada) {
        ada.jumlah++;
    } else {
        keranjang.push({ id, nama, harga, jumlah: 1 });
    }
    renderKeranjang();
}

function renderKeranjang() {
    const list = document.getElementById('daftarKeranjang');
    const total = document.getElementById('totalBayar');
    list.innerHTML = '';
    let totalHarga = 0;

    keranjang.forEach((item, index) => {
        const subtotal = item.harga * item.jumlah;
        totalHarga += subtotal;
        const li = document.createElement('li');
        li.className = 'list-group-item';
        li.innerHTML = `
            <div class="item-info">
                <strong>${item.nama}</strong>
                <small>Rp ${item.harga.toLocaleString()} x ${item.jumlah} = Rp ${(subtotal).toLocaleString()}</small>
            </div>
            <div>
                <button class="btn btn-sm btn-secondary" onclick="ubahJumlah(${index}, -1)">-</button>
                ${item.jumlah}
                <button class="btn btn-sm btn-secondary" onclick="ubahJumlah(${index}, 1)">+</button>
                <button class="btn btn-sm btn-danger" onclick="hapusItem(${index})">&times;</button>
            </div>`;
        list.appendChild(li);
    });
    total.innerText = totalHarga.toLocaleString();
}

function ubahJumlah(index, delta) {
    keranjang[index].jumlah += delta;
    if (keranjang[index].jumlah <= 0) keranjang.splice(index, 1);
    renderKeranjang();
}

function hapusItem(index) {
    if (confirm(`Hapus ${keranjang[index].nama} dari keranjang?`)) {
        keranjang.splice(index, 1);
        renderKeranjang();
    }
}

function checkout() {
    if (keranjang.length === 0) return alert("Keranjang kosong!");

    const namaCustomer = document.getElementById("namaCustomer").value.trim();
    const hpCustomer = document.getElementById("hpCustomer").value.trim();
    const emailCustomer = document.getElementById("emailCustomer").value.trim();
    const alamatCustomer = document.getElementById("alamatCustomer").value.trim();
    const kasir = document.getElementById("namaKasir").value.trim();

    if (!namaCustomer || !hpCustomer || !emailCustomer || !alamatCustomer || !kasir) {
        alert("Semua data wajib diisi!");
        return;
    }

    fetch('simpan_pesanan.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            keranjang: keranjang,
            kasir: kasir,
            customer: {
                nama: namaCustomer,
                hp: hpCustomer,
                email: emailCustomer,
                alamat: alamatCustomer
            }
        })
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            alert("✅ Pesanan berhasil disimpan!");
            window.location.href = "pembayaran.php?id_penjualan=" + res.id_penjualan;
        } else {
            alert("❌ Gagal menyimpan pesanan: " + res.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert("Terjadi kesalahan saat menyimpan pesanan.");
    });
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
