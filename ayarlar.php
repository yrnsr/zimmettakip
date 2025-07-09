<?php
include 'kontrol.php';
girisKontrolu();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zimmetdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Bağlantı hatası: " . $conn->connect_error);
}

$kullaniciID = $_SESSION['KullaniciID'];

// Kullanıcı bilgilerini çek
$sql = "SELECT * FROM kullanici WHERE KullaniciID = $kullaniciID";
$result = $conn->query($sql);
$kullanici = $result->fetch_assoc();

// PROFİL GÜNCELLE
if(isset($_POST['profilGuncelle'])){
    $ad = $conn->real_escape_string($_POST['ad']);
    $soyad = $conn->real_escape_string($_POST['soyad']);
    $email = $conn->real_escape_string($_POST['email']);

    $sql_update = "UPDATE kullanici SET Ad='$ad', Soyad='$soyad', Email='$email' WHERE KullaniciID=$kullaniciID";
    $conn->query($sql_update);

    header("Location: ayarlar.php");
    exit;
}

// ŞİFRE GÜNCELLE
if(isset($_POST['sifreGuncelle'])){
    $sifre = $conn->real_escape_string($_POST['sifre']);
    $hashliSifre = password_hash($sifre, PASSWORD_DEFAULT);

    $sql_update = "UPDATE kullanici SET Sifre='$hashliSifre' WHERE KullaniciID=$kullaniciID";
    $conn->query($sql_update);

    header("Location: ayarlar.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Ayarlar</title>
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
        <h1 class="h3 mb-4 text-gray-800">Ayarlar</h1>

        <div class="row">

          <!-- Profil Bilgileri -->
          <div class="col-lg-6">
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Profil Bilgileri</h6>
              </div>
              <div class="card-body">
                <form method="post">
                  <div class="form-group">
                    <label>Ad</label>
                    <input type="text" name="ad" class="form-control" value="<?php echo htmlspecialchars($kullanici['Ad']); ?>" required>
                  </div>
                  <div class="form-group">
                    <label>Soyad</label>
                    <input type="text" name="soyad" class="form-control" value="<?php echo htmlspecialchars($kullanici['Soyad']); ?>" required>
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($kullanici['Email']); ?>" required>
                  </div>
                  <button type="submit" name="profilGuncelle" class="btn btn-success">Güncelle</button>
                </form>
              </div>
            </div>
          </div>

          <!-- Şifre Güncelle -->
          <div class="col-lg-6">
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Şifre Güncelle</h6>
              </div>
              <div class="card-body">
                <form method="post">
                  <div class="form-group">
                    <label>Yeni Şifre</label>
                    <input type="password" name="sifre" class="form-control" required>
                  </div>
                  <button type="submit" name="sifreGuncelle" class="btn btn-warning">Şifreyi Güncelle</button>
                </form>
              </div>
            </div>
          </div>

        </div> <!-- /.row -->

      </div> <!-- /.container-fluid -->

    </div> <!-- End of Main Content -->
    <?php include 'footer.php'; ?>
  </div> <!-- End of Content Wrapper -->

</div> <!-- End of Page Wrapper -->

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

</body>
</html>

<?php $conn->close(); ?>
