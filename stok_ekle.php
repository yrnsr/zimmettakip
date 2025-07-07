<?php
session_start();
include 'baglanti.php';

if (!isset($_SESSION['KullaniciID'])) {
    header("Location: login.php");
    exit;
}
// Yetki kontrolü (YENİ: access_denied.php yönlendirmesi)
if ($_SESSION['Role'] != 'admin') {
    header("Location: access_denied.php"); // Burada sayfanın adını kendi dosya adına göre ayarlayabilirsin
    exit;
}

// Esya listesini al (stok eklerken seçim için)
$esya_sorgu = "SELECT * FROM Esya ORDER BY MarkaModel ASC";
$esya_sonuc = $conn->query($esya_sorgu);

$mesaj = "";

if(isset($_POST['stok_ekle'])) {
    $esya_id = (int)$_POST['esya_id'];
    $miktar = (int)$_POST['miktar'];

    if ($esya_id > 0 && $miktar > 0) {
        // Stoklar tablosunda zaten bu eşya için kayıt var mı kontrol et
        $kontrol_sorgu = "SELECT * FROM Stoklar WHERE EsyaID = $esya_id";
        $kontrol_sonuc = $conn->query($kontrol_sorgu);

        if ($kontrol_sonuc->num_rows > 0) {
            $mesaj = "Bu eşya için zaten stok kaydı var.";
        } else {
            // Yeni stok kaydı ekle
            $ekle_sorgu = "INSERT INTO Stoklar (EsyaID, Miktar) VALUES ($esya_id, $miktar)";
            if ($conn->query($ekle_sorgu) === TRUE) {
                $mesaj = "Stok başarıyla eklendi.";
            } else {
                $mesaj = "Hata: " . $conn->error;
            }
        }
    } else {
        $mesaj = "Lütfen geçerli eşya ve miktar seçiniz.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Yeni Stok Ekle</title>
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
                <h2>Yeni Stok Ekle</h2>

                <?php if ($mesaj): ?>
                    <div class="alert alert-info"><?= $mesaj ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label>Esya Seçin</label>
                        <select name="esya_id" class="form-control" required>
                            <option value="">-- Seçiniz --</option>
                            <?php while($esya = $esya_sonuc->fetch_assoc()): ?>
                                <option value="<?= $esya['EsyaID'] ?>"><?= htmlspecialchars($esya['MarkaModel']) ?> (<?= htmlspecialchars($esya['SeriNo']) ?>)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Miktar</label>
                        <input type="number" name="miktar" class="form-control" min="1" required>
                    </div>
                    <button type="submit" name="stok_ekle" class="btn btn-primary">Stok Ekle</button>
                </form>

                <a href="stoklar.php" class="btn btn-secondary mt-3">Geri Dön</a>
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
