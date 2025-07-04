<?php
session_start();
include 'baglanti.php';

if (!isset($_SESSION['KullaniciID'])) {
    header("Location: login.php");
    exit;
}

$stok_id = isset($_GET['stok_id']) ? (int)$_GET['stok_id'] : 0;

if ($stok_id == 0) {
    echo "Stok ID belirtilmedi.";
    exit;
}

$sqlStok = "SELECT s.StokID, s.Miktar, e.MarkaModel FROM Stoklar s INNER JOIN Esya e ON s.EsyaID = e.EsyaID WHERE s.StokID = $stok_id";
$stokResult = $conn->query($sqlStok);
if (!$stokResult || $stokResult->num_rows == 0) {
    echo "Stok bulunamadı.";
    exit;
}
$stok = $stokResult->fetch_assoc();

$mesaj = "";

if (isset($_POST['stok_hareket_ekle'])) {
    $islemTipi = $_POST['islemtipi'];
    $miktar = (int)$_POST['miktar'];
    $aciklama = $conn->real_escape_string($_POST['aciklama']);

    if ($miktar <= 0) {
        $mesaj = "Miktar pozitif sayı olmalıdır.";
    } else {
        $mevcutMiktar = (int)$stok['Miktar'];
        if ($islemTipi == 'cikis' && $miktar > $mevcutMiktar) {
            $mesaj = "Yeterli stok yok. Mevcut: $mevcutMiktar";
        } else {
            $yeniMiktar = ($islemTipi == 'giris') ? $mevcutMiktar + $miktar : $mevcutMiktar - $miktar;

            $updateSql = "UPDATE Stoklar SET Miktar = $yeniMiktar WHERE StokID = $stok_id";
            if ($conn->query($updateSql) === TRUE) {
                $insertSql = "INSERT INTO StokHareketleri (StokID, Islemtipi, Miktar, Aciklama) VALUES ($stok_id, '$islemTipi', $miktar, '$aciklama')";
                if ($conn->query($insertSql) === TRUE) {
                    $mesaj = "Stok hareketi başarıyla eklendi.";
                    $stok['Miktar'] = $yeniMiktar;
                } else {
                    $mesaj = "Hareket ekleme hatası: " . $conn->error;
                }
            } else {
                $mesaj = "Stok güncelleme hatası: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Stok Hareketi Ekle</title>
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
                <h2>Stok Hareketi Ekle: <?= htmlspecialchars($stok['MarkaModel']) ?></h2>
                <p>Mevcut Miktar: <?= $stok['Miktar'] ?></p>

                <a href="stoklar.php" class="btn btn-secondary mb-3">Geri Dön</a>

                <?php if ($mesaj): ?>
                    <div class="alert alert-info"><?= $mesaj ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>İşlem Tipi</label>
                        <select name="islemtipi" class="form-control" required>
                            <option value="giris">Giriş</option>
                            <option value="cikis">Çıkış</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Miktar</label>
                        <input type="number" name="miktar" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Açıklama</label>
                        <textarea name="aciklama" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" name="stok_hareket_ekle" class="btn btn-primary">Ekle</button>
                </form>
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
