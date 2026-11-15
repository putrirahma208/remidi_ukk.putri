<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $query = mysqli_query($koneksi,
        "SELECT * FROM userr WHERE username='$username'"
    );

    $data = mysqli_fetch_assoc($query);

    if ($data && $password == $data['password'] && $role == $data['role']) {

        $_SESSION['login'] = true;
        $_SESSION['id'] = $data['id'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];

        if ($data['role'] == 'admin') {
            header("Location: dashboard.php");
            exit;
        } else {
            header("Location: dashboard_petugas.php");
            exit;
        }

    } else {
        $pesan = "Username, password, atau role salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Halaman Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h2>LOGIN APLIKASI KASIR</h2>

    <?php
    if (isset($pesan)) {
        echo $pesan;
    }
    ?>

    <form method="POST">

        <label>Username</label><br>
        <input type="text" name="username" required>
        <br><br>

        <label>Password</label><br>
        <input type="password" name="password" required>
        <br><br>

        <label>Pilih Role</label><br>
        <select name="role" required>
            <option value="">Role</option>
            <option value="admin">Admin</option>
            <option value="petugas">Petugas</option>
        </select>
        <br><br>

        <button type="submit" name="login">Login</button>

    </form>

</body>
</html>
