<?php
session_start();
include 'baglanti.php';

// Giriş ve admin kontrolü
if (!isset($_SESSION['KullaniciID']) || $_SESSION['Role'] != 'admin') {
  header("Location: login.php");
  exit;
}

// RoleID kontrolü
if (!isset($_GET['roleid'])) {
  header("Location: rol_yonetimi.php");
  exit;
}

$roleID = (int)$_GET['roleid'];

// Rol bilgisi
$rolQuery = $conn->query("SELECT * FROM Roller WHERE RoleID = $roleID");
if($rolQuery->num_rows != 1){
  echo "Rol bulunamadı.";
  exit;
}
$rol = $rolQuery->fetch_assoc();

// Sayfa listesi (örnek)
$sayfalar = [
  'index.php' => 'Dashboard',
  'personel.php' => 'Personel Yönetimi',
  'esya.php' => 'Eşya Yönetimi',
  'kullanici.php' => 'Kullanıcılar',
  'rol_yonetimi.php' => 'Rol Yönetimi'
];

// Güncelleme işlemi
$mesaj = "";
if(isset($_POST['kaydet'])){
  // Eski izinleri sil
  $conn->query("DELETE FROM Erisimler WHERE RoleID = $roleID");

  // Yeni izinleri ekle
  if(isset($_POST['sayfa'])){
    foreach($_POST['sayfa'] as $page){
      $page = $conn->real_escape_string($page);
      $conn->query("INSERT INTO Erisimler (RoleID, Sayfa) VALUES ($roleID, '$page')");
    }
  }

  $mesaj = "Erişim izinleri güncellendi.";
}

// Mevcut izinler
$izinlerQuery = $conn->query("SELECT Sayfa FROM Erisimler WHERE RoleID = $roleID");
$mevcutIzinler = [];
while($row = $izinlerQuery->fetch_assoc()){
  $mevcutIzinler[] = $row['Sayfa'];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Rol Erişim Yönetimi</title>

  <!-- Bootstrap ve SB Admin 2 CSS -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" />
  <link href="css/sb-admin-2.min.css" rel="stylesheet" />
</head>
<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php include 'topbar.php'; ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid mt-4">

          <h3>Rol Erişim Yönetimi: <?php echo htmlspecialchars($rol['RoleName']); ?></h3>

          <?php if($mesaj): ?>
            <div class="alert alert-success"><?php echo $mesaj; ?></div>
          <?php endif; ?>

          <form method="POST">
            <div class="card">
              <div class="card-header">Erişebileceği Sayfalar</div>
              <div class="card-body">

                <?php foreach($sayfalar as $file => $name): ?>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="sayfa[]" value="<?php echo $file; ?>"
                      <?php if(in_array($file, $mevcutIzinler)) echo "checked"; ?> />
                    <label class="form-check-label">
                      <?php echo $name . " ($file)"; ?>
                    </label>
                  </div>
                <?php endforeach; ?>

              </div>
            </div>

            <button type="submit" name="kaydet" class="btn btn-primary mt-3">Kaydet</button>
          </form>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php include 'footer.php'; ?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

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
