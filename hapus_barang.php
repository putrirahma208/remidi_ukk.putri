<?php
include "koneksi.php";
session_start();

if (!isset($_SESSION['login'])) {
    header("location:login.php");
    exit;
}

$id = (int) $_GET['id_produk'];

$hapus = mysqli_query($koneksi,
    "DELETE FROM produkk WHERE id_produk='$id'"
);

if ($hapus) {
    header("location:barang.php");
    exit;
} else {
    echo "Produk gagal dihapus";
}
?>
