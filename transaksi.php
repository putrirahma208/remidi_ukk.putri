<?php
session_start();
include "koneksi.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['beli'])) {
    $id_produk = (int) ($_POST['id_produk'] ?? 0);
    $jumlahProduk = (int) ($_POST['jumlahProduk'] ?? 0);
    $id_user = (int) ($_SESSION['id_user'] ?? $_SESSION['id'] ?? 1);

    if ($id_produk > 0 && $jumlahProduk > 0) {
        $q_produk = mysqli_query($koneksi, "SELECT * FROM produkk WHERE id_produk = $id_produk");
        $p = mysqli_fetch_assoc($q_produk);

        if ($p && $p['stok'] >= $jumlahProduk) {
            $total = $p['harga'] * $jumlahProduk;

            $insert_penjualan = mysqli_query($koneksi, 
                "INSERT INTO penjualann (tgl_penjualan, id_user, total) VALUES (NOW(), $id_user, $total)"
            );

            if ($insert_penjualan) {
                $id_penjualan_baru = mysqli_insert_id($koneksi);

                mysqli_query($koneksi, 
                    "INSERT INTO detailpenjualann (id_penjualan, id_produk, jumlahProduk, subtotal) 
                    VALUES ($id_penjualan_baru, $id_produk, $jumlahProduk, $total)"
                );

                mysqli_query($koneksi, 
                    "UPDATE produkk SET stok = stok - $jumlahProduk WHERE id_produk = $id_produk"
                );

                $pesan_sukses = "Transaksi berhasil disimpan!";
            } else {
                $pesan_error = "Gagal menyimpan ke tabel penjualan: " . mysqli_error($koneksi);
            }
        } else {
            $pesan_error = "Stok barang tidak mencukupi!";
        }
    } else {
        $pesan_error = "Pilih barang dan masukkan jumlah yang valid!";
    }
}

if (isset($_POST['edit'])) {
    $id = (int) $_POST['id'];
    $jumlahProduk = (int) ($_POST['jumlahProduk'] ?? 0);

    $q_detail = mysqli_query($koneksi, "SELECT * FROM detailpenjualann WHERE id_penjualan = $id");
    $d = mysqli_fetch_assoc($q_detail);

    if ($d && $jumlahProduk > 0) {
        $id_produk = $d['id_produk'];
        $q_produk = mysqli_query($koneksi, "SELECT * FROM produkk WHERE id_produk = $id_produk");
        $p = mysqli_fetch_assoc($q_produk);

        $stok_tersedia = $p['stok'] + $d['jumlahProduk'];

        if ($jumlahProduk <= $stok_tersedia) {
            $total = $p['harga'] * $jumlahProduk;

            mysqli_query($koneksi, "UPDATE detailpenjualann SET jumlahProduk = $jumlahProduk, subtotal = $total WHERE id_penjualan = $id");
            mysqli_query($koneksi, "UPDATE produkk SET stok = $stok_tersedia - $jumlahProduk WHERE id_produk = $id_produk");
            mysqli_query($koneksi, "UPDATE penjualann SET total = $total WHERE id_penjualan = $id");

            $pesan_sukses = "Transaksi berhasil diperbarui!";
        } else {
            $pesan_error = "Stok tidak mencukupi untuk perubahan ini!";
        }
    }
}

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $q_detail = mysqli_query($koneksi, "SELECT * FROM detailpenjualann WHERE id_penjualan = $id");
    $d = mysqli_fetch_assoc($q_detail);

    if ($d) {
        mysqli_query($koneksi, "UPDATE produkk SET stok = stok + {$d['jumlahProduk']} WHERE id_produk = {$d['id_produk']}");
        mysqli_query($koneksi, "DELETE FROM detailpenjualann WHERE id_penjualan = $id");
        mysqli_query($koneksi, "DELETE FROM penjualann WHERE id_penjualan = $id");
        $pesan_sukses = "Transaksi berhasil dihapus!";
    }
}

$produk = mysqli_query($koneksi, "SELECT * FROM produkk WHERE stok > 0");

$data = mysqli_query($koneksi,
    "SELECT penjualann.*, userr.username, produkk.namaProduk, detailpenjualann.jumlahProduk
    FROM penjualann
    LEFT JOIN userr ON penjualann.id_user = userr.id
    LEFT JOIN detailpenjualann ON penjualann.id_penjualan = detailpenjualann.id_penjualan
    LEFT JOIN produkk ON detailpenjualann.id_produk = produkk.id_produk
    ORDER BY penjualann.id_penjualan DESC"
);

if (!$data) {
    $pesan_error = "Error Query Tabel: " . mysqli_error($koneksi);
}

$edit = null;
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $q_edit = mysqli_query($koneksi,
        "SELECT detailpenjualann.*, produkk.namaProduk
        FROM detailpenjualann 
        JOIN produkk ON detailpenjualann.id_produk = produkk.id_produk
        WHERE id_penjualan = $id"
    );
    $edit = mysqli_fetch_assoc($q_edit);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Kasir</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>TRANSAKSI KASIR</h2>

<!Tampilkan Pesan Sukses atau Error>
<?php if (isset($pesan_sukses)): ?>
    <p style="color: pink; font-weight: bold;"><?php echo $pesan_sukses; ?></p>
<?php endif; ?>

<?php if (isset($pesan_error)): ?>
    <p style="color: red; font-weight: bold;"><?php echo $pesan_error; ?></p>
<?php endif; ?>

<form method="POST" action="transaksi.php">
<?php if ($edit): ?>
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    Nama Barang<br>
    <input type="text" value="<?php echo htmlspecialchars($edit['namaProduk']); ?>" readonly>
    <br><br>

    Jumlah<br>
    <input type="number" name="jumlahProduk" value="<?php echo $edit['jumlahProduk']; ?>" min="1" required>
    <br><br>

    <button type="submit" name="edit">Simpan Perubahan</button>
    <a href="transaksi.php">Batal</a>

<?php else: ?>
    Pilih Barang<br>
    <select name="id_produk" required>
        <option value="">Pilih Barang</option>
        <?php while ($p = mysqli_fetch_assoc($produk)): ?>
            <option value="<?php echo $p['id_produk']; ?>">
                <?php echo htmlspecialchars($p['namaProduk']); ?> 
                - Rp. <?php echo ($p['harga']); ?> 
                (Stok: <?php echo $p['stok']; ?>)
            </option>
        <?php endwhile; ?>
    </select>
    <br><br>

    Jumlah<br>
    <input type="number" name="jumlahProduk" min="1" required>
    <br><br>

    <button type="submit" name="beli">Beli</button>
<?php endif; ?>
</form>

<hr>

<h2>DATA TRANSAKSI</h2>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Nama Barang</th>
        <th>Jumlah</th>
        <th>Petugas</th>
        <th>Total</th>
        <th>Aksi</th>
    </tr>

    <?php 
    if ($data && mysqli_num_rows($data) > 0):
        $no = 1; 
        while ($d = mysqli_fetch_assoc($data)): 
            $id_transaksi = $d['id_penjualan'] ?? $d['id'] ?? 0;
    ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $d['tgl_penjualan']; ?></td>
        <td><?php echo htmlspecialchars($d['namaProduk'] ?? '-'); ?></td>
        <td><?php echo $d['jumlahProduk'] ?? '-'; ?></td>
        <td><?php echo htmlspecialchars($d['username'] ?? 'Admin'); ?></td>
        <td>Rp. <?php echo ($d['total']); ?></td>
        <td>
            <a href="transaksi.php?edit=<?php echo $id_transaksi; ?>">Edit</a> |
            <a href="transaksi.php?hapus=<?php echo $id_transaksi; ?>" onclick="return confirm('Hapus transaksi?')">Hapus</a>
        </td>
    </tr>
    <?php 
        endwhile; 
    else: 
    ?>
    <tr>
        <td colspan="7" align="center">Belum ada data transaksi.</td>
    </tr>
    <?php endif; ?>
</table>

<br>
<a href="dashboard.php">Kembali</a>

</body>
</html>
