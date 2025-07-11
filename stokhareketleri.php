<?php
session_start();
include 'baglanti.php';
include 'kontrol.php';

if (!isset($_SESSION['KullaniciID'])) {
    header("Location: login.php");
    exit;
}

if (!yetkiKontrol($conn, $_SESSION['KullaniciID'], 'stokhareket_islemleri')) {
    echo "Bu sayfayı görüntüleme yetkiniz yok.";
    exit;
}

if (!isset($_SESSION['KullaniciID'])) {
    header("Location: login.php");
    exit;
}
// Yetki kontrolü (YENİ: access_denied.php yönlendirmesi)
if ($_SESSION['Role'] != 'admin') {
    header("Location: access_denied.php"); // Burada sayfanın adını kendi dosya adına göre ayarlayabilirsin
    exit;
}

if (!isset($_GET['stok_id'])) {
    echo "Stok ID belirtilmedi.";
    exit;
}

$stok_id = (int)$_GET['stok_id'];

$sqlStok = "SELECT s.StokID, s.Miktar, e.Marka, e.Model FROM Stoklar s INNER JOIN Esya e ON s.EsyaID = e.EsyaID WHERE s.StokID = $stok_id";
$stokResult = $conn->query($sqlStok);
if (!$stokResult || $stokResult->num_rows == 0) {
    echo "Stok bulunamadı.";
    exit;
}
$stok = $stokResult->fetch_assoc();

$sqlHareket = "SELECT * FROM StokHareketleri WHERE StokID = $stok_id ORDER BY Tarih DESC";
$hareketResult = $conn->query($sqlHareket);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Stok Hareketleri</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />
</head>
<body id="page-top">
<div id="wrapper">

    <?php include 'sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <?php include 'topbar.php'; ?>

            <div class="container-fluid">
                <h2>Stok Hareketleri: <?= htmlspecialchars($stok['Marka']) ?> (StokID: <?= $stok['StokID'] ?>)</h2>
                <p>Mevcut Miktar: <?= $stok['Miktar'] ?></p>
                <a href="stoklar.php" class="btn btn-secondary mb-3">Geri Dön</a>

                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>Hareket ID</th>
                        <th>İşlem Tipi</th>
                        <th>Miktar</th>
                        <th>Tarih</th>
                        <th>Açıklama</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($hareketResult && $hareketResult->num_rows > 0): ?>
                        <?php while ($row = $hareketResult->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['HareketID'] ?></td>
                                <td><?= ($row['Islemtipi'] == 'giris') ? 'Giriş' : 'Çıkış' ?></td>
                                <td><?= $row['Miktar'] ?></td>
                                <td><?= $row['Tarih'] ?></td>
                                <td><?= htmlspecialchars($row['Aciklama']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">Hareket bulunamadı.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

        <?php include 'footer.php'; ?>

    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
