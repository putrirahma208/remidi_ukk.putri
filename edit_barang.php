<?php
include "koneksi.php";
session_start();

if (!isset($_SESSION['login'])) {
    header("location:login.php");
    exit;
}

$id = (int) $_GET['id_produk'];

$data = mysqli_query($koneksi,
    "SELECT * FROM produkk WHERE id_produk='$id'"
);

$d = mysqli_fetch_array($data);

if (!$d) {
    die("Produk tidak ditemukan");
}

if (isset($_POST['update'])) {

    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $update = mysqli_query($koneksi,
        "UPDATE produkk SET
        namaProduk='$nama',
        harga='$harga',
        stok='$stok'
        WHERE id_produk='$id'"
    );

    if ($update) {
        header("location:barang.php");
        exit;
    } else {
        echo "Data gagal diubah";
    }
}
?>

<h2>Edit Produk</h2>

<form method="POST">

    Nama Produk<br>
    <input type="text" name="nama_produk"
    value="<?php echo htmlspecialchars($d['namaProduk']); ?>" required><br><br>

    Harga<br>
    <input type="number" name="harga"
    value="<?php echo $d['harga']; ?>" required><br><br>

    Stok<br>
    <input type="number" name="stok"
    value="<?php echo $d['stok']; ?>" required><br><br>

    <button type="submit" name="update">Update</button>

</form>
