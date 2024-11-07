<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h2>Dashboard <?php echo ucfirst($_SESSION['role']) ?></h2>
    <ul>
        <?php if ($_SESSION['role'] == 'inspektorat') { ?>
            <li><a href="pilih_perangkat_daerah.php?request=input_temuan">Input Temuan</a></li>
            <li><a href="verifikasi_tindak.php">Verifikasi Tindak</a></li>
            <li><a href="pengaduan.php">Pengaduan</a></li>
            <li><a href="akun_perangkat_daerah.php">Daftar Akun Perangkat Daerah</a></li>
        <?php } elseif ($_SESSION['role'] == 'perangkat_daerah') { ?>
            <li><a href="temuan_pd.php">Daftar Temuan</a></li>
        <?php } ?>
    </ul>

    <a href="logout.php">Logout</a>
</body>
</html>

