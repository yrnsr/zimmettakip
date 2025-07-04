<?php
session_start();
include 'baglanti.php';

// Giriş kontrolü
if (!isset($_SESSION['KullaniciID'])) {
    header("Location: login.php");
    exit;
}

// Kullanıcı bilgilerini çek
$kullaniciID = $_SESSION['KullaniciID'];
$query = "SELECT * FROM kullanici WHERE KullaniciID = '$kullaniciID' LIMIT 1";
$result = $conn->query($query);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profilim</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
  <div id="wrapper">
    
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        
        <!-- Topbar -->
        <?php include 'topbar.php'; ?>

        <!-- Main Content -->
        <div class="container-fluid">
          <h1 class="h3 mb-4 text-gray-800">Profilim</h1>

          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Kullanıcı Bilgileri</h6>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th>Ad</th>
                  <td><?php echo htmlspecialchars($user['Ad']); ?></td>
                </tr>
                <tr>
                  <th>Soyad</th>
                  <td><?php echo htmlspecialchars($user['Soyad']); ?></td>
                </tr>
                <tr>
                  <th>Sicil No</th>
                  <td><?php echo htmlspecialchars($user['SicilNo']); ?></td>
                </tr>
                <tr>
                  <th>Email</th>
                  <td><?php echo htmlspecialchars($user['Email']); ?></td>
                </tr>
                <tr>
                  <th>Rol</th>
                  <td><?php echo htmlspecialchars($user['Role']); ?></td>
                </tr>
              </table>

              <!-- Şifre değiştirme butonu -->
              <a href="sifre_degistir.php" class="btn btn-primary">Şifre Değiştir</a>
            </div>
          </div>

        </div> <!-- /.container-fluid -->

      </div> <!-- End of Main Content -->

      <!-- Footer -->
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
