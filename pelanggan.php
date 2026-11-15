<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_tlp = $_POST['no_tlp'];

    mysqli_query($koneksi, "INSERT INTO pelanggann 
        (nama, alamat, no_tlp)
        VALUES ('$nama', '$alamat', '$no_tlp')");

    header("Location: pelanggan.php");
    exit;
}

if (isset($_POST['edit'])) {
    $id = $_POST['id_pelanggan'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_tlp = $_POST['no_tlp'];

    mysqli_query($koneksi, "UPDATE pelanggann SET
        nama='$nama',
        alamat='$alamat',
        no_tlp='$no_tlp'
        WHERE id_pelanggan='$id'");

    header("Location: pelanggan.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($koneksi, "DELETE FROM pelanggann
        WHERE id_pelanggan='$id'");

    header("Location: pelanggan.php");
    exit;
}

$edit = null;

if (isset($_GET['ubah'])) {
    $id = $_GET['ubah'];

    $query = mysqli_query($koneksi, "SELECT * FROM pelanggann
        WHERE id_pelanggan='$id'");

    $edit = mysqli_fetch_assoc($query);
}

$data = mysqli_query($koneksi, "SELECT * FROM pelanggann");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Pelanggan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h2>Data Pelanggan</h2>

    <form method="POST">

        <input type="hidden" name="id_pelanggan"
        value="<?= $edit['id_pelanggan'] ?? ''; ?>">

        <label>Nama Pelanggan</label>
        <input type="text" name="nama"
        value="<?= htmlspecialchars($edit['nama'] ?? ''); ?>"
        required>

        <label>Alamat</label>
        <textarea name="alamat" required><?= htmlspecialchars($edit['alamat'] ?? ''); ?></textarea>

        <label>No HP</label>
        <input type="text" name="no_tlp"
        value="<?= htmlspecialchars($edit['no_tlp'] ?? ''); ?>"
        required>

        <?php if ($edit) { ?>
            <button type="submit" name="edit">Update</button>
            <a href="pelanggan.php">Batal</a>
        <?php } else { ?>
            <button type="submit" name="tambah">Tambah Pelanggan</button>
        <?php } ?>

    </form>

    <h2>Daftar Pelanggan</h2>

    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>No.Tlp</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        while ($p = mysqli_fetch_assoc($data)) {
        ?>

        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($p['nama']); ?></td>
            <td><?= htmlspecialchars($p['alamat']); ?></td>
            <td><?= htmlspecialchars($p['no_tlp']); ?></td>
            <td>
                <a class="edit" href="pelanggan.php?ubah=<?= $p['id_pelanggan']; ?>">Edit</a>

                <a class="hapus"
                href="pelanggan.php?hapus=<?= $p['id_pelanggan']; ?>"
                onclick="return confirm('Yakin ingin menghapus pelanggan?')">
                Hapus</a>
            </td>
        </tr>

        <?php } ?>

    </table>

    <br>
    <a href="dashboard.php">Kembali</a>

</div>

</body>
</html>