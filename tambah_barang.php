<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['login'])) {
    header("location:login.php");
    exit;
}

if (isset($_POST['simpan'])) {

    $nama = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $simpan = mysqli_query($koneksi,
        "INSERT INTO produkk VALUES
        (NULL,'$nama','$harga','$stok')"
    );

    if ($simpan) {
        header("location:barang.php");
        exit;
    } else {
        echo "Data gagal disimpan";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Tambah Barang</h2>

<form method="POST">

    Nama Barang<br>
    <input type="text" name="nama_barang" required><br><br>

    Harga Barang<br>
    <input type="number" name="harga" min="1" required><br><br>

    Stok Barang<br>
    <input type="number" name="stok" min="0" required><br><br>

    <button type="submit" name="simpan">Simpan</button>

</form>

<br>
<a href="barang.php">Kembali</a>

</body>
</html>
