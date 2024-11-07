<?php
session_start();
require 'config.php';

// Tambahkan perangkat daerah
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nama'], $_POST['password'])) {
    $nama = $_POST['nama'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO perangkat_daerah (nama, password) VALUES (?, ?)");
    $stmt->execute([$nama, $password]);

    header('Location: akun_perangkat_daerah.php');
    exit();
}

// Edit perangkat daerah
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nama'], $_POST['password'])) {
        $nama = $_POST['nama'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE perangkat_daerah SET nama = ?, password = ? WHERE id = ?");
        $stmt->execute([$nama, $password, $id]);

        header('Location: akun_perangkat_daerah.php');
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM perangkat_daerah WHERE id = ?");
    $stmt->execute([$id]);
    $perangkat_daerah = $stmt->fetch();
}

// Hapus perangkat daerah
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM perangkat_daerah WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: akun_perangkat_daerah.php');
    exit();
}

// Tampilkan daftar perangkat daerah
$stmt = $pdo->prepare("SELECT * FROM perangkat_daerah");
$stmt->execute();
$perangkat_daerah_list = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Perangkat Daerah</title>
</head>
<body>
    <h2>Daftar Perangkat Daerah</h2>
    <a href="dashboard.php">Kembali ke Dashboard</a>
    <br>
    <br>
    <form method="POST">
        Nama: <input type="text" name="nama" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Tambah</button>
    </form>
    <br>
    <table border="1">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($perangkat_daerah_list as $pd): ?>
                <tr>
                    <td><?= htmlspecialchars($pd['nama']) ?></td>
                    <td>
                        <a href="akun_perangkat_daerah.php?id=<?= $pd['id'] ?>">Edit</a>
                        <a href="akun_perangkat_daerah.php?hapus=<?= $pd['id'] ?>">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <?php if (isset($perangkat_daerah)): ?>
        <form method="POST">
            Nama: <input type="text" name="nama" value="<?= htmlspecialchars($perangkat_daerah['nama']) ?>" required><br>
            Password: <input type="password" name="password" required><br>
            <button type="submit">Simpan</button>
            <a href="akun_perangkat_daerah.php">Batal</a>
        </form>
    <?php endif; ?>
</body>
</html>

