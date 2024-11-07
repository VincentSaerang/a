<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM pengguna WHERE nama = ? AND password = ?");
    $stmt->execute([$nama, md5($password)]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header('Location: dashboard.php');
    } else {
        $error = "Nama atau Kata Sandi salah";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        Nama: <input type="text" name="nama" required><br>
        Kata Sandi: <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
