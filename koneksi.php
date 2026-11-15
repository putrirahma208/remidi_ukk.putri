<?php

$koneksi = mysqli_connect(
    "localhost",
    "root",
    "",
    "kasir"
);

if (!$koneksi) {
    die("Koneksi database gagal");
}

?>