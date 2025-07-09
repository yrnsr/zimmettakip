<?php
session_start();
include 'baglanti.php';

// Kullanıcı giriş kontrolü
if (!isset($_SESSION['KullaniciID'])) {
    header("Location: login.php");
    exit;
}

// Yetki kontrolü (YENİ: access_denied.php yönlendirmesi)
if ($_SESSION['Role'] != 'admin') {
    header("Location: access_denied.php"); // Burada sayfanın adını kendi dosya adına göre ayarlayabilirsin
    exit;
}

$sql = "SELECT s.StokID, s.Miktar, e.Marka, e.Model, e.SeriNo, e.Ozellik 
        FROM Stoklar s 
        INNER JOIN Esya e ON s.EsyaID = e.EsyaID";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Stok Listesi</title>
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
                <h2>Stok Listesi</h2>
                <a href="stokhareket_ekle.php" class="btn btn-success mb-3">Yeni Stok Hareketi Ekle</a>
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>Stok ID</th>
                        <th>Marka</th>
                        <th>Model</th>
                        <th>Seri No</th>
                        <th>Özellik</th>
                        <th>Miktar</th>
                        <th>İşlemler</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['StokID'] ?></td>
                                <td><?= htmlspecialchars($row['Marka']) ?></td>
                                <td><?= htmlspecialchars($row['Model']) ?></td>
                                <td><?= htmlspecialchars($row['SeriNo']) ?></td>
                                <td><?= htmlspecialchars($row['Ozellik']) ?></td>
                                <td><?= $row['Miktar'] ?></td>
                                <td>
                                    <a href="stokhareketleri.php?stok_id=<?= $row['StokID'] ?>" class="btn btn-info btn-sm">Hareketler</a>
                                    <a href="stokhareket_ekle.php?stok_id=<?= $row['StokID'] ?>" class="btn btn-primary btn-sm">Hareket Ekle</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6">Stok bulunamadı.</td></tr>
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
