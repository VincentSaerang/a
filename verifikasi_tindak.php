<?php
session_start();
require 'config.php';

$id_perangkat_daerah = null;
if (isset($_GET['id'])) {
    $id_perangkat_daerah = $_GET['id'];
    $_SESSION['id_perangkat_daerah'] = $id_perangkat_daerah;
} else if (isset($_SESSION['id_perangkat_daerah'])) {
    $id_perangkat_daerah = $_SESSION['id_perangkat_daerah'];
} else {
    header('Location: pilih_perangkat_daerah.php?request=verifikasi_tindak');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'], $_POST['id_tindak'])) {
    $status = $_POST['status'];
    $id_tindak = $_POST['id_tindak'];

    $stmt = $pdo->prepare("UPDATE tindak_lanjut SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id_tindak]);

    echo "Status tindak lanjut berhasil diperbarui.";
}

$stmt = $pdo->prepare("SELECT * FROM tindak_lanjut WHERE id_perangkat_daerah = ?");
$stmt->execute([$id_perangkat_daerah]);
$tindak_lanjut = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Tindak Lanjut</title>
</head>
<body>
    <h2>Verifikasi Tindak Lanjut</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Tahun</th>
                <th>Jenis Laporan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tindak_lanjut as $tindak): ?>
                <tr>
                    <td><?= htmlspecialchars($tindak['judul']) ?></td>
                    <td><?= htmlspecialchars($tindak['tahun']) ?></td>
                    <td><?= htmlspecialchars($tindak['jenis_laporan']) ?></td>
                    <td><?= htmlspecialchars($tindak['status']) ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="id_tindak" value="<?= $tindak['id'] ?>">
                            <select name="status">
                                <option value="belum" <?= $tindak['status'] == 'belum' ? 'selected' : '' ?>>Belum</option>
                                <option value="sudah sesuai" <?= $tindak['status'] == 'sudah sesuai' ? 'selected' : '' ?>>Sudah Sesuai</option>
                                <option value="dikembalikan ke dinas" <?= $tindak['status'] == 'dikembalikan ke dinas' ? 'selected' : '' ?>>Dikembalikan ke Dinas</option>
                            </select>
                            <button type="submit">Update Status</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

