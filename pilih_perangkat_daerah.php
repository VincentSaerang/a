<?php
session_start();
require 'config.php';

// Tampilkan daftar perangkat daerah
$stmt = $pdo->prepare("SELECT * FROM perangkat_daerah");
$stmt->execute();
$perangkat_daerah_list = $stmt->fetchAll();
$request = $_GET['request'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pilih Perangkat Daerah</title>
</head>
<body>
    <h2>Pilih Perangkat Daerah</h2>
    <a href="dashboard.php">Kembali ke Dashboard</a><br><br>
    <table border="1">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Pilih</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($perangkat_daerah_list as $pd): ?>
                <tr>
                    <td><?= htmlspecialchars($pd['nama']) ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="perangkat_daerah" value="<?= $pd['id'] ?>">
                            <button type="submit">Pilih</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (isset($_POST['perangkat_daerah'])): ?>
        <?php
        $perangkat_daerah = $_POST['perangkat_daerah'];
        $_SESSION['perangkat_daerah'] = $perangkat_daerah;
        if ($request == 'verifikasi_tindak') {
            header('Location: verifikasi_tindak.php?id=' . $perangkat_daerah);
        } else {
            header('Location: input_temuan.php?id=' . $perangkat_daerah);
        }
        exit();
        ?>
    <?php endif; ?>
</body>
</html>


