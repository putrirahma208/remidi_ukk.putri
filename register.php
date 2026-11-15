<?php
session_start();
include "koneksi.php";

$cek = mysqli_query($koneksi,
    "SELECT * FROM userr WHERE role='admin'"
);

$jumlah = mysqli_num_rows($cek);

if ($jumlah > 0) {
    if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
        die("Hanya admin yang boleh mendaftarkan akun.");
    }
}

if (isset($_POST['daftar'])) {

    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $simpan = mysqli_query($koneksi,
        "INSERT INTO userr VALUES
        (NULL,'$nama','$username','$password','$role')"
    );

    if ($simpan) {
        echo "<script>
            alert('Pendaftaran berhasil');
            window.location='dashboard.php';
        </script>";
    } else {
        echo "Pendaftaran gagal. Periksa username.";
    }
}
?>

<h2>Registrasi Pengguna</h2>
  <link rel="stylesheet" href="style.css">

<form method="POST">

    Nama Lengkap<br>
    <input type="text" name="nama" required><br><br>

    Username<br>
    <input type="text" name="username" required><br><br>

    Password<br>
    <input type="password" name="password" required><br><br>

    <button type="submit" name="daftar">Daftar</button>

</form>

<a href="dashboard.php">Kembali</a>