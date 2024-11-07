<?php
session_start();
require 'config.php';

$id_perangkat_daerah = null;

if (isset($_GET['id'])) {
    $id_perangkat_daerah = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM perangkat_daerah WHERE id = ?");
    $stmt->execute([$id_perangkat_daerah]);
    $perangkat_daerah = $stmt->fetch();
    if (!$perangkat_daerah) {
        die('Perangkat daerah tidak ditemukan.');
    }
} else {
    die('ID perangkat daerah tidak ditemukan.');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        isset($_POST['tahun'], $_POST['jenis_laporan'], $_POST['judul'], $_POST['jumlah'], $_POST['nilai'], $_POST['rekomendasi_total']) &&
        isset($_FILES['lampiran_pdf']) && $_FILES['lampiran_pdf']['error'] === UPLOAD_ERR_OK
    ) {
        $tahun = $_POST['tahun'];
        $jenis_laporan = $_POST['jenis_laporan'];
        $judul = $_POST['judul'];
        $jumlah = $_POST['jumlah'];
        $nilai = $_POST['nilai'];
        $rekomendasi_total = $_POST['rekomendasi_total'];
        $lampiran_pdf = $_FILES['lampiran_pdf']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["lampiran_pdf"]["name"]);
        if (move_uploaded_file($_FILES["lampiran_pdf"]["tmp_name"], $target_file)) {
            $stmt = $pdo->prepare("INSERT INTO temuan (tahun, jenis_laporan, judul, jumlah, nilai, rekomendasi_total, lampiran_pdf, id_perangkat_daerah) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$tahun, $jenis_laporan, $judul, $jumlah, $nilai, $rekomendasi_total, $lampiran_pdf, $id_perangkat_daerah]);
            
            header('Location: dashboard.php');
            exit();
        } else {
            echo "Terjadi kesalahan saat mengunggah file.";
        }
    } else {
        echo "Data belum lengkap atau file terlalu besar.";
    }
} else {
    // Tampilkan daftar temuan
    $stmt = $pdo->prepare("SELECT * FROM temuan WHERE id_perangkat_daerah = ?");
    $stmt->execute([$id_perangkat_daerah]);
    $temuan = $stmt->fetchAll();
    if (!$temuan) {
        echo "Tidak ada temuan untuk perangkat daerah ini.";
    } else {
        echo "<table border='1'>";
        echo "<tr><th>Tahun</th><th>Jenis Laporan</th><th>Judul</th><th>Jumlah</th><th>Nilai</th><th>Rekomendasi Total</th><th>Detail</th></tr>";
        foreach ($temuan as $t) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($t['tahun']) . "</td>";
            echo "<td>" . htmlspecialchars($t['jenis_laporan']) . "</td>";
            echo "<td>" . htmlspecialchars($t['judul']) . "</td>";
            echo "<td>" . htmlspecialchars($t['jumlah']) . "</td>";
            echo "<td>" . htmlspecialchars($t['nilai']) . "</td>";
            echo "<td>" . htmlspecialchars($t['rekomendasi_total']) . "</td>";
            echo "<td><a href='detail_temuan.php?id=" . $t['id'] . "'>Detail</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Temuan</title>
</head>
<body>
    <a href="dashboard.php">Kembali ke Dashboard</a>
    <h2>Input Temuan</h2>
    <form method="POST" enctype="multipart/form-data">
        Perangkat Daerah: <?= htmlspecialchars($perangkat_daerah['nama']) ?><br>
        Tahun: <input type="text" name="tahun" required><br>
        Jenis Laporan:
        <select name="jenis_laporan">
            <option value="lkpd">LKPD</option>
            <option value="kinerja">Kinerja</option>
            <option value="lainnya">Lainnya</option>
        </select><br>
        Judul: <input type="text" name="judul" required><br>
        Jumlah: <input type="number" name="jumlah" required><br>
        Nilai: <input type="number" step="0.01" name="nilai" required><br>
        Rekomendasi Total: <input type="number" step="0.01" name="rekomendasi_total"><br>
        Lampiran PDF: <input type="file" name="lampiran_pdf"><br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>

