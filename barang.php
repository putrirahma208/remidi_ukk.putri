<?php
include "koneksi.php";
session_start();

if (!isset($_SESSION['login'])) {
    header("location:login.php");
    exit;
}

$data = mysqli_query($koneksi, "SELECT * FROM produkk");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>DATA BARANG</h2>

<a href="tambah_barang.php">+ Tambah Barang</a>

<br><br>

<table border="1" cellpadding="10">

<tr>
    <th>No</th>
    <th>Nama Produk</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Aksi</th>
</tr>

<?php
$no = 1;

while ($d = mysqli_fetch_array($data)) {
?>

<tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo $d['namaProduk']; ?></td>
    <td>Rp.<?php echo $d['harga'] ?></td>
    <td><?php echo $d['stok']; ?></td>

    <td>
        <a href="edit_barang.php?id_produk=<?php echo $d['id_produk']; ?>">Edit</a>

        |

        <a href="hapus_barang.php?id_produk=<?php echo $d['id_produk']; ?>"
        onclick="return confirm('Yakin ingin menghapus produk?')">Hapus</a>
    </td>
</tr>

<?php } ?>

</table>

<br>
<a href="dashboard.php">Kembali</a>

</body>
</html>
