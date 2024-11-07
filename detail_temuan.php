<?php
session_start();
require 'config.php';

// Pastikan ada ID temuan di URL
if (!isset($_GET['id'])) {
    die('ID temuan tidak ditemukan.');
}

$id_temuan = $_GET['id'];

// Ambil data temuan dari database
$stmt = $pdo->prepare("SELECT * FROM temuan WHERE id = ?");
$stmt->execute([$id_temuan]);
$temuan = $stmt->fetch();

if (!$temuan) {
    die('Temuan tidak ditemukan.');
}

if (isset($_SESSION['role']) && $_SESSION['role'] == 'perangkat_daerah') {
    $id_perangkat_daerah = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM perangkat_daerah WHERE id = ?");
    $stmt->execute([$id_perangkat_daerah]);
    $perangkat_daerah = $stmt->fetch();
    if (!$perangkat_daerah) {
        die('Perangkat daerah tidak ditemukan.');
    }
    $is_inspektorat = isset($perangkat_daerah['role']) && $perangkat_daerah['role'] == 'inspektorat';
    $back_link = "temuan_pd.php?id=" . $temuan['id_perangkat_daerah'];
} else {
    $is_inspektorat = false;
    $back_link = "input_temuan.php?id=" . $temuan['id_perangkat_daerah'];
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Temuan</title>
</head>
<body>
    <h2>Detail Temuan</h2>
    <a href="<?= $back_link ?>">Kembali ke <?= $_SESSION['role'] == 'perangkat_daerah' ? 'Daftar Temuan' : 'Input Temuan' ?></a>

    <table border="1">
        <tr>
            <th>Judul</th>
            <th>Tahun</th>
            <th>Jenis Laporan</th>
            <th>Jumlah</th>
            <th>Nilai</th>
            <th>Rekomendasi Total</th>
            <th>Lampiran PDF</th>
        </tr>
        <tr>
            <td><?= htmlspecialchars($temuan['judul']) ?></td>
            <td><?= htmlspecialchars($temuan['tahun']) ?></td>
            <td><?= htmlspecialchars($temuan['jenis_laporan']) ?></td>
            <td><?= htmlspecialchars($temuan['jumlah']) ?></td>
            <td><?= htmlspecialchars($temuan['nilai']) ?></td>
            <td><?= htmlspecialchars($temuan['rekomendasi_total']) ?></td>
            <td><a href="uploads/<?= htmlspecialchars($temuan['lampiran_pdf']) ?>" download>Download Lampiran</a></td>
        </tr>
    </table>

    <?php if ($is_inspektorat) { ?>
    <br>
    <a href="uploads/<?= htmlspecialchars($temuan['lampiran_pdf']) ?>" download>Download Lampiran</a>
    <?php } else { ?>
    <br>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'inspektorat') { ?>
        <a href="tambah_uraian_rekomendasi.php?id=<?= $id_temuan ?>">Tambah Uraian Rekomendasi</a>
    <?php } ?>
    <?php } ?>
    <h3>Rekomendasi</h3>
    <table border="1">
        <thead>
            <tr>
                <th>Uraian</th>
                <th>Sifat</th>
                <th>Nilai</th>
                <th>Tindak Lanjut</th>
                <th>Aksi</th>
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
                        <td>";
                // Tampilkan daftar tindak lanjut dari uraian
                $stmt = $pdo->prepare("SELECT * FROM tindak_lanjut WHERE id_uraian = ?");
                $stmt->execute([$uraian['id']]);
                $tindak_lanjut = $stmt->fetchAll();

                if ($tindak_lanjut) {
                    foreach ($tindak_lanjut as $t) {
                        echo "<span>" . htmlspecialchars($t['uraian']) . "</span>";
                    }
                } else {
                    echo "Belum ada tindak lanjut";
                }
                echo "</td>
                      <td><a href='detail_tindak_lanjut.php?id_uraian=" . $uraian['id'] . "'>Detail Tindak Lanjut</a></td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

