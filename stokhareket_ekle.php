<?php
session_start();
include 'baglanti.php';

// Kullanıcı giriş kontrolü
if (!isset($_SESSION['KullaniciID'])) {
    header("Location: login.php");
    exit;
}

// Yetki kontrolü
if ($_SESSION['Role'] != 'admin') {
    header("Location: access_denied.php");
    exit;
}

$mesaj = "";

// Tüm stoklar dropdown için çekilir
$sqlStoklar = "SELECT s.StokID, e.Marka, e.Model, s.Miktar
               FROM Stoklar s
               INNER JOIN Esya e ON s.EsyaID = e.EsyaID";
$stoklarResult = $conn->query($sqlStoklar);

// Form gönderildiğinde işlem yapılır
if (isset($_POST['stok_hareket_ekle'])) {
    $stok_id = (int)$_POST['stok_id'];
    $islemTipi = $_POST['islemtipi'];
    $miktar = (int)$_POST['miktar'];
    $aciklama = $conn->real_escape_string($_POST['aciklama']);

    if ($stok_id == 0 || $miktar <= 0) {
        $mesaj = "Lütfen geçerli stok ve miktar seçin.";
    } else {
        // Seçilen stokun mevcut miktarı alınır
        $sqlMevcut = "SELECT Miktar FROM Stoklar WHERE StokID = $stok_id";
        $mevcutResult = $conn->query($sqlMevcut);

        if ($mevcutResult && $mevcutResult->num_rows > 0) {
            $row = $mevcutResult->fetch_assoc();
            $mevcutMiktar = (int)$row['Miktar'];

            if ($islemTipi == 'cikis' && $miktar > $mevcutMiktar) {
                $mesaj = "Yeterli stok yok. Mevcut miktar: $mevcutMiktar";
            } else {
                // Yeni miktar hesaplanır
                $yeniMiktar = ($islemTipi == 'giris') ? $mevcutMiktar + $miktar : $mevcutMiktar - $miktar;

                // Stok güncelle
                $updateSql = "UPDATE Stoklar SET Miktar = $yeniMiktar WHERE StokID = $stok_id";
                if ($conn->query($updateSql) === TRUE) {
                    // Hareket tablosuna ekleme
                    $insertSql = "INSERT INTO StokHareketleri (StokID, Islemtipi, Miktar, Aciklama) 
                                  VALUES ($stok_id, '$islemTipi', $miktar, '$aciklama')";
                    if ($conn->query($insertSql) === TRUE) {
                        $mesaj = "Stok hareketi başarıyla eklendi.";
                    } else {
                        $mesaj = "Hareket ekleme hatası: " . $conn->error;
                    }
                } else {
                    $mesaj = "Stok güncelleme hatası: " . $conn->error;
                }
            }
        } else {
            $mesaj = "Seçilen stok bulunamadı.";
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
                <h2 class="mb-4">Yeni Stok Hareketi Ekle</h2>

                <a href="stoklar.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Geri Dön</a>

                <?php if ($mesaj): ?>
                    <div class="alert alert-info"><?= htmlspecialchars($mesaj) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>Stok Seç</label>
                        <select name="stok_id" class="form-control" required>
                            <option value="0">Seçiniz</option>
                            <?php if ($stoklarResult && $stoklarResult->num_rows > 0): ?>
                                <?php while ($stok = $stoklarResult->fetch_assoc()): ?>
                                    <option value="<?= $stok['StokID'] ?>">
                                        <?= htmlspecialchars($stok['Marka']) . " " . htmlspecialchars($stok['Model']) . " (Mevcut: " . $stok['Miktar'] . ")" ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <option value="0">Stok bulunamadı.</option>
                            <?php endif; ?>
                        </select>
                    </div>

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

                    <button type="submit" name="stok_hareket_ekle" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Ekle
                    </button>
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
