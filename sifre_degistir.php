<?php
session_start();
include 'baglanti.php';

// Giriş kontrolü
if (!isset($_SESSION['KullaniciID'])) {
    header("Location: login.php");
    exit;
}

$kullaniciID = $_SESSION['KullaniciID'];
$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eskiSifre = $_POST["eskiSifre"];
    $yeniSifre = $_POST["yeniSifre"];
    $yeniSifreTekrar = $_POST["yeniSifreTekrar"];

    if ($yeniSifre != $yeniSifreTekrar) {
        $mesaj = "Yeni şifreler eşleşmiyor.";
    } else {
        // Kullanıcının mevcut şifresini çek
        $sql = "SELECT Sifre FROM Kullanici WHERE KullaniciID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $kullaniciID);
        $stmt->execute();
        $stmt->bind_result($hashSifre);
        $stmt->fetch();
        $stmt->close();

        if (!password_verify($eskiSifre, $hashSifre)) {
            $mesaj = "Eski şifreniz hatalı.";
        } else {
            // Yeni şifreyi hashle
            $yeniHash = password_hash($yeniSifre, PASSWORD_DEFAULT);

            // Güncelle
            $sql = "UPDATE Kullanici SET Sifre = ? WHERE KullaniciID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $yeniHash, $kullaniciID);
            if ($stmt->execute()) {
                $mesaj = "Şifre başarıyla değiştirildi.";
            } else {
                $mesaj = "Bir hata oluştu: " . $conn->error;
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Şifre Değiştir</title>
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
                    <h2 class="mb-4">Şifre Değiştir</h2>

                    <?php if (!empty($mesaj)): ?>
                        <div class="alert alert-info"><?php echo htmlspecialchars($mesaj); ?></div>
                    <?php endif; ?>

                    <form method="POST" class="col-md-6">
                        <div class="form-group">
                            <label>Eski Şifre</label>
                            <input type="password" name="eskiSifre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Yeni Şifre</label>
                            <input type="password" name="yeniSifre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Yeni Şifre Tekrar</label>
                            <input type="password" name="yeniSifreTekrar" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Değiştir</button>
                    </form>
                </div>

            </div>
            <?php include 'footer.php'; ?>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
