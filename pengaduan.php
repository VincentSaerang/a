<?php
session_start();
require 'config.php';

// Ambil daftar pengaduan
$stmt = $pdo->prepare("SELECT * FROM pengaduan");
$stmt->execute();
$pengaduan = $stmt->fetchAll();

// Hapus pengaduan jika tombol hapus ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_pengaduan'])) {
    $id_pengaduan = $_POST['id_pengaduan'];

    // Hapus pengaduan dari database
    $stmt = $pdo->prepare("DELETE FROM pengaduan WHERE id = ?");
    $stmt->execute([$id_pengaduan]);

    echo "Pengaduan berhasil dihapus.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pengaduan</title>
</head>
<body>
    <h2>Daftar Pengaduan</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Lengkap</th>
                <th>Alamat</th>
                <th>Nomor Handphone</th>
                <th>Jenis Pengaduan</th>
                <th>Isi Pengaduan</th>
                <th>Lampiran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pengaduan as $aduan): ?>
                <tr>
                    <td><?= htmlspecialchars($aduan['tanggal']) ?></td>
                    <td><?= htmlspecialchars($aduan['nama_lengkap']) ?></td>
                    <td><?= htmlspecialchars($aduan['alamat']) ?></td>
                    <td><?= htmlspecialchars($aduan['nomor_handphone']) ?></td>
                    <td><?= htmlspecialchars($aduan['jenis_pengaduan']) ?></td>
                    <td><?= htmlspecialchars($aduan['isi_pengaduan']) ?></td>
                    <td><a href="uploads/<?= htmlspecialchars($aduan['lampiran']) ?>" download>Download Lampiran</a></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="id_pengaduan" value="<?= $aduan['id'] ?>">
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
