<?php
session_start();
include 'baglanti.php';

// Giriş ve admin kontrolü
if (!isset($_SESSION['KullaniciID'])) {
    header("Location: login.php");
    exit;
}
// Yetki kontrolü (YENİ: access_denied.php yönlendirmesi)
if ($_SESSION['Role'] != 'admin') {
    header("Location: access_denied.php"); // Burada sayfanın adını kendi dosya adına göre ayarlayabilirsin
    exit;
}

// Rol ekleme işlemi
$mesaj = "";
if(isset($_POST['rol_ekle'])){
  $rol = $conn->real_escape_string($_POST['rol']);
  $sql = "INSERT INTO Roller (RoleName) VALUES ('$rol')";
  if($conn->query($sql)){
    $mesaj = "Rol eklendi.";
  } else {
    $mesaj = "Hata: " . $conn->error;
  }
}

// Roller listesi
$roller = $conn->query("SELECT * FROM Roller");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Rol Yönetimi</title>

  <!-- Bootstrap CSS -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" />
  <link href="css/sb-admin-2.min.css" rel="stylesheet" />
</head>
<body id="page-top">

  <!-- Sayfa Wrapper -->
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

          <h3 class="mb-4">Rol Yönetimi</h3>

          <?php if($mesaj): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($mesaj); ?></div>
          <?php endif; ?>

          <!-- Rol Ekleme Formu -->
          <div class="card mb-4" style="max-width: 500px;">
            <div class="card-header">Yeni Rol Ekle</div>
            <div class="card-body">
              <form method="POST">
                <div class="mb-3">
                  <label for="rol" class="form-label">Rol Adı</label>
                  <input type="text" name="rol" id="rol" class="form-control" required />
                </div>
                <button type="submit" name="rol_ekle" class="btn btn-success">Rol Ekle</button>
              </form>
            </div>
          </div>

          <!-- Roller Tablosu -->
          <div class="card">
            <div class="card-header">Mevcut Roller</div>
            <div class="card-body table-responsive">
              <table class="table table-bordered table-striped">
                <thead class="table-dark">
                  <tr>
                    <th>ID</th>
                    <th>Rol Adı</th>
                    <th>İşlem</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($row = $roller->fetch_assoc()): ?>
                    <tr>
                      <td><?php echo $row['RoleID']; ?></td>
                      <td><?php echo htmlspecialchars($row['RoleName']); ?></td>
                      <td>
                        <a href="rol_erisimi.php?roleid=<?php echo $row['RoleID']; ?>" class="btn btn-primary btn-sm">Erişimleri Düzenle</a>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          </div>

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

<?php
$conn->close();
?>
