<?php
session_start();
require 'config.php';

$id_uraian = $_GET['id_uraian'];
$stmt = $pdo->prepare("SELECT * FROM uraian_rekomendasi WHERE id = ?");
$stmt->execute([$id_uraian]);
$uraian = $stmt->fetch();
$id_perangkat_daerah = null;

if (!$uraian) {
    die('Uraian rekomendasi tidak ditemukan');
}

$temuan_id = $uraian['id_temuan'];
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT nama FROM perangkat_daerah WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $perangkat_daerah = $stmt->fetch();
    $id_perangkat_daerah = $_SESSION['user_id'];
}

$stmt = $pdo->prepare("SELECT * FROM tindak_lanjut WHERE id_uraian = ?");
$stmt->execute([$id_uraian]);
$tindak_lanjut_list = $stmt->fetchAll();

if (count($tindak_lanjut_list) > 1) {
    echo "Tindak lanjut sudah ada lebih dari 1.<br>";
    echo "<a href='detail_temuan.php?id=" . $temuan_id . "'>Kembali ke detail temuan</a>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['uraian'], $_POST['sifat'], $_POST['periode'], $_FILES['bukti_tindak_lanjut'])) {
        $uraian = $_POST['uraian'];
        $sifat = $_POST['sifat'];
        $periode = $_POST['periode'];
        if (!in_array($sifat, ['finansial', 'non finansial'])) {
            die('Sifat harus finansial atau non finansial.');
        }

        $bukti_tindak_lanjut = "TL_" . $id_uraian . "_" . $perangkat_daerah['nama'] . "_" . pathinfo($_FILES["bukti_tindak_lanjut"]["name"], PATHINFO_EXTENSION);
        $target_dir = "uploads/";
        $target_file = $target_dir . $bukti_tindak_lanjut;

        if (move_uploaded_file($_FILES["bukti_tindak_lanjut"]["tmp_name"], $target_file)) {
            $stmt = $pdo->prepare("INSERT INTO tindak_lanjut (id_uraian, uraian, sifat, periode, bukti_tindak_lanjut, id_perangkat_daerah, temuan_id, status) VALUES (:id_uraian, :uraian, :sifat, :periode, :bukti_tindak_lanjut, :id_perangkat_daerah, :temuan_id, 'belum')");
            $stmt->execute([
                ':id_uraian' => $id_uraian,
                ':uraian' => $uraian,
                ':sifat' => $sifat,
                ':periode' => $periode,
                ':bukti_tindak_lanjut' => $bukti_tindak_lanjut,
                ':id_perangkat_daerah' => $id_perangkat_daerah ? $id_perangkat_daerah : null,
                ':temuan_id' => $temuan_id
            ]);

            header('Location: detail_temuan.php?id=' . $temuan_id);
            exit();
        } else {
            echo "Terjadi kesalahan saat mengunggah file.";
        }
    }
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $pdo->prepare("SELECT bukti_tindak_lanjut FROM tindak_lanjut WHERE id = ?");
    $stmt->execute([$id]);
    $bukti_tindak_lanjut = $stmt->fetchColumn();
    $stmt = $pdo->prepare("DELETE FROM tindak_lanjut WHERE id = ?");
    $stmt->execute([$id]);

    unlink("uploads/" . $bukti_tindak_lanjut);

    header('Location: detail_tindak_lanjut.php?id_uraian=' . $id_uraian);
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Tindak Lanjut</title>
</head>
<body>
    <h2>Tambah Tindak Lanjut untuk Uraian: <?= htmlspecialchars($uraian['uraian']) ?></h2>
    <?php if (count($tindak_lanjut_list) == 0) { ?>
    <form method="POST" enctype="multipart/form-data">
        Uraian: <textarea name="uraian" required></textarea><br>
        Sifat: <select name="sifat" required>
            <option value="finansial">Finansial</option>
            <option value="non finansial">Non Finansial</option>
        </select><br>
        Periode: <input type="text" name="periode" required><br>
        Bukti Tindak Lanjut: <input type="file" name="bukti_tindak_lanjut" required><br>
        <button type="submit">Simpan</button>
        <a href="detail_temuan.php?id=<?= $temuan_id ?>">Batal / Kembali</a>
    </form>
    <?php } else { ?>
        <a href="detail_temuan.php?id=<?= $temuan_id ?>">Kembali</a>
    <?php } ?>
    
    <h3>Daftar Tindak Lanjut</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Uraian</th>
                <th>Sifat</th>
                <th>Periode</th>
                <th>Bukti Tindak Lanjut</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tindak_lanjut_list as $tindak_lanjut): ?>
                <tr>
                    <td><?= htmlspecialchars($tindak_lanjut['uraian']) ?></td>
                    <td><?= htmlspecialchars($tindak_lanjut['sifat']) ?></td>
                    <td><?= htmlspecialchars($tindak_lanjut['periode']) ?></td>
                    <td><a href="uploads/<?= htmlspecialchars($tindak_lanjut['bukti_tindak_lanjut']) ?>">Download</a></td>
                    <td><a href="detail_tindak_lanjut.php?id_uraian=<?= $id_uraian ?>&hapus=<?= $tindak_lanjut['id'] ?>">Hapus</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

