<?php
session_start();
include 'baglanti.php';

// Giriş kontrolü
if (!isset($_SESSION['KullaniciID'])) {
    header("Location: login.php");
    exit;
}

// Yetki kontrolü (YENİ: access_denied.php yönlendirmesi)
if ($_SESSION['Role'] != 'admin') {
    header("Location: access_denied.php"); // Burada sayfanın adını kendi dosya adına göre ayarlayabilirsin
    exit;
}

// Kullanıcı ekleme işlemi
$mesaj = "";
if(isset($_POST['ekle'])){
    $sicilNo = $conn->real_escape_string($_POST['SicilNo']);
    $ad = $conn->real_escape_string($_POST['Ad']);
    $soyad = $conn->real_escape_string($_POST['Soyad']);
    $email = $conn->real_escape_string($_POST['Email']);
    $sifre = password_hash($_POST['Sifre'], PASSWORD_DEFAULT);
    $role = $conn->real_escape_string($_POST['Role']);

    $sql = "INSERT INTO Kullanici (SicilNo, Ad, Soyad, Email, Sifre, Role)
            VALUES ('$sicilNo', '$ad', '$soyad', '$email', '$sifre', '$role')";

    if ($conn->query($sql) === TRUE) {
        $mesaj = "<div class='alert alert-success'>Kullanıcı başarıyla eklendi.</div>";
    } else {
        $mesaj = "<div class='alert alert-danger'>Hata: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kullanıcı Ekle</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">

    <?php include 'sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <?php include 'topbar.php'; ?>

            <div class="container-fluid">
                <h1 class="h3 mb-4 text-gray-800">Yeni Kullanıcı Ekle</h1>

                <?php echo $mesaj; ?>

                <form method="POST" class="col-md-6">
                    <div class="form-group">
                        <label>Sicil No</label>
                        <input type="text" name="SicilNo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Ad</label>
                        <input type="text" name="Ad" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Soyad</label>
                        <input type="text" name="Soyad" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="Email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Şifre</label>
                        <input type="password" name="Sifre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Rol</label>
                        <select name="Role" class="form-control" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" name="ekle" class="btn btn-success mt-2">Kullanıcı Ekle</button>
                </form>
            </div> <!-- /.container-fluid -->

        </div> <!-- End of Main Content -->

        <?php include 'footer.php'; ?>
    </div> <!-- End of Content Wrapper -->

</div> <!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- JS Dosyaları -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

</body>
</html>

<?php $conn->close(); ?>
