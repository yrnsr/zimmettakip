<?php
include 'kontrol.php';  // burada zaten session_start() var

girisKontrolu(); // Giriş yapılmamışsa login.php'ye yönlendirir

$kullaniciAdi = $_SESSION['KullaniciAdi'] ?? '';
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Ana Sayfa - Zimmet Takip</title>
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
          <h1 class="h3 mb-4 text-gray-800">Hoşgeldin, <?php echo htmlspecialchars($kullaniciAdi); ?>!</h1>

          <p>Bu sistemde aşağıdaki menüden işlemlerinizi yapabilirsiniz.</p>

          <div class="list-group" style="max-width: 400px;">
            <a href="personel.php" class="list-group-item list-group-item-action">
              <i class="fas fa-users"></i> Personel Yönetimi
            </a>
            <a href="esya.php" class="list-group-item list-group-item-action">
              <i class="fas fa-box"></i> Eşya Yönetimi
            </a>
            <a href="zimmet.php" class="list-group-item list-group-item-action">
              <i class="fas fa-hand-holding"></i> Zimmet Yönetimi
            </a>
          </div>

          <hr>
          <a href="logout.php" class="btn btn-danger mt-3"><i class="fas fa-sign-out-alt"></i> Çıkış Yap</a>
        </div>

      </div>

      <?php include 'footer.php'; ?>
    </div>
  </div>

  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/sb-admin-2.min.js"></script>
</body>
</html>
