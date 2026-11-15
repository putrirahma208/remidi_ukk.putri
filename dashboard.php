<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Kasir</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h2>Aplikasi Kasir</h2>

    <h3>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?></h3>

    <hr>

    <a href="register.php">Register</a>
    <br><br>

    <a href="pelanggan.php">Pelanggan</a>
    <br><br>

    <a href="barang.php">Pendataan Barang</a>
    <br><br>

    <a href="transaksi.php">Pembelian</a>
    <br><br>

    <a href="stok.php">Stok Barang</a>
    <br><br>

    <a href="logout.php">Logout</a>

</body>
</html>
