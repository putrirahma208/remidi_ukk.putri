<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['login'])) {
    header("location:login.php");
    exit;
}

$data = mysqli_query($koneksi, "SELECT * FROM produkk");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stok Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>DATA STOK BARANG</h2>

<br>

<table border="1" cellpadding="10">

<tr>
    <th>No</th>
    <th>Nama Barang</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Keterangan</th>
</tr>

<?php
$no = 1;

while ($d = mysqli_fetch_array($data)) {
?>

<tr>
    <td><?php echo $no++; ?></td>

    <td><?php echo htmlspecialchars($d['namaProduk']); ?></td>

    <td>Rp. <?php echo ($d['harga']); ?></td>

    <td><?php echo $d['stok']; ?></td>

    <td>
        <?php
        if ($d['stok'] == 0) {
            echo "Stok Habis";
        } elseif ($d['stok'] <= 5) {
            echo "Stok Menipis";
        } else {
            echo "Tersedia";
        }
        ?>
    </td>
</tr>

<?php } ?>

</table>
<br><br>
<a href="dashboard.php">Kembali</a>

</body>
</html>
