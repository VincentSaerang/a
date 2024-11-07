<?php
session_start();
require 'config.php';

// Pastikan ada ID temuan di URL
if (!isset($_GET['id'])) {
    die('ID temuan tidak ditemukan.');
}

$id_temuan = $_GET['id'];

// Ambil data temuan untuk ditampilkan
$stmt = $pdo->prepare("SELECT * FROM temuan WHERE id = ?");
$stmt->execute([$id_temuan]);
$temuan = $stmt->fetch();

if (!$temuan) {
    die('Temuan tidak ditemukan.');
}

// Proses penambahan uraian rekomendasi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['uraian'], $_POST['sifat'], $_POST['nilai'])) {
        $uraian = $_POST['uraian'];
        $sifat = $_POST['sifat'];
        $nilai = $_POST['nilai'];

        // Insert uraian rekomendasi ke database
        $stmt = $pdo->prepare("INSERT INTO uraian_rekomendasi (id_temuan, uraian, sifat, nilai) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id_temuan, $uraian, $sifat, $nilai]);

        echo "Uraian rekomendasi berhasil ditambahkan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Uraian Rekomendasi</title>
</head>
<body>
    <h2>Tambah Uraian Rekomendasi untuk Temuan: <?= htmlspecialchars($temuan['judul']) ?></h2>
    
    <form method="POST">
        Uraian: <textarea name="uraian" required></textarea><br>
        Sifat:
        <select name="sifat" required>
            <option value="finansial">Finansial</option>
            <option value="non finansial">Non Finansial</option>
        </select><br>
        Nilai: <input type="number" name="nilai" step="0.01" required><br>
        <button type="submit">Tambah Uraian</button>
    </form>

    <h3>Daftar Uraian Rekomendasi</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Uraian</th>
                <th>Sifat</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Ambil semua uraian rekomendasi untuk temuan ini
            $stmt = $pdo->prepare("SELECT * FROM uraian_rekomendasi WHERE id_temuan = ?");
            $stmt->execute([$id_temuan]);
            $uraian_rekomendasi = $stmt->fetchAll();

            foreach ($uraian_rekomendasi as $uraian) {
                echo "<tr>
                        <td>" . htmlspecialchars($uraian['uraian']) . "</td>
                        <td>" . htmlspecialchars($uraian['sifat']) . "</td>
                        <td>" . htmlspecialchars($uraian['nilai']) . "</td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
    
    <br>
    <a href="detail_temuan.php?id=<?= $id_temuan ?>">Kembali ke Detail Temuan</a>
</body>
</html>
