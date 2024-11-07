<?php
session_start();
require 'config.php';

if (isset($_SESSION['user_id'])) {
    $id_perangkat_daerah = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM perangkat_daerah WHERE id = ?");
    $stmt->execute([$id_perangkat_daerah]);
    $perangkat_daerah = $stmt->fetch();
    if (!$perangkat_daerah) {
        die('Perangkat daerah tidak ditemukan.');
    }
} else {
    die('Anda belum login.');
}

// Tampilkan daftar temuan
$stmt = $pdo->prepare("SELECT tahun, judul, jenis_laporan, id FROM temuan WHERE id_perangkat_daerah = ?");
$stmt->execute([$id_perangkat_daerah]);
$temuan = $stmt->fetchAll();
if (!$temuan) {
    echo "Tidak ada temuan untuk perangkat daerah ini.";
} else {
    echo "<table border='1'>";
    echo "<tr><th>Judul</th><th>Tahun</th><th>Jenis Laporan</th><th>Aksi</th></tr>";
    foreach ($temuan as $t) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($t['judul']) . "</td>";
        echo "<td>" . htmlspecialchars($t['tahun']) . "</td>";
        echo "<td>" . htmlspecialchars($t['jenis_laporan']) . "</td>";
        echo "<td><a href='detail_temuan.php?id=" . $t['id'] . "'>Detail</a></td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Temuan</title>
</head>
<body>
    <a href="dashboard.php">Kembali ke Dashboard</a>
</body>
</html>

