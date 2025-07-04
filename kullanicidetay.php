<?php
session_start();
include 'baglanti.php';

// Giriş kontrolü
if (!isset($_SESSION['KullaniciID'])) {
    header("Location: login.php");
    exit;
}

// Yetki kontrolü
if ($_SESSION['Role'] != 'admin') {
    echo "Bu sayfaya erişim yetkiniz yok.";
    exit;
}

// GET ile gelen kullanıcı id kontrolü
if (!isset($_GET['id'])) {
    echo "Kullanıcı ID belirtilmedi.";
    exit;
}

$kullaniciID = (int)$_GET['id'];

$sql = "SELECT * FROM Kullanici WHERE KullaniciID = $kullaniciID";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
} else {
    echo "Kullanıcı bulunamadı.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Kullanıcı Detayı</title>
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
          <h2 class="mb-4">Kullanıcı Detayı</h2>

          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Kullanıcı Bilgileri</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <tr><th>Kullanıcı ID</th><td><?php echo $row['KullaniciID']; ?></td></tr>
                  <tr><th>Sicil No</th><td><?php echo $row['SicilNo']; ?></td></tr>
                  <tr><th>Ad</th><td><?php echo $row['Ad']; ?></td></tr>
                  <tr><th>Soyad</th><td><?php echo $row['Soyad']; ?></td></tr>
                  <tr><th>Email</th><td><?php echo $row['Email']; ?></td></tr>
                  <tr><th>Rol</th><td><?php echo $row['Role']; ?></td></tr>
                </table>
              </div>
            </div>
          </div>

          <a href="kullanicilar.php" class="btn btn-primary">Geri Dön</a>

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
