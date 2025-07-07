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

// GET ile gelen kullanıcı id kontrolü
if (!isset($_GET['id'])) {
    echo "Kullanıcı ID belirtilmedi.";
    exit;
}

$kullaniciID = (int)$_GET['id'];

// Güncelleme işlemi
if (isset($_POST['guncelle'])) {
    $yeniRole = $conn->real_escape_string($_POST['Role']);

    $sql = "UPDATE Kullanici SET Role='$yeniRole' WHERE KullaniciID=$kullaniciID";

    if ($conn->query($sql) === TRUE) {
        header("Location: kullanicilar.php");
        exit;
    } else {
        echo "Hata: " . $conn->error;
    }
}

// Kullanıcı bilgilerini çekme
$sql = "SELECT * FROM Kullanici WHERE KullaniciID = $kullaniciID";
$result = $conn->query($sql);

if ($result->num_rows != 1) {
    echo "Kullanıcı bulunamadı.";
    exit;
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Yetki Güncelle</title>
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
          <h2 class="mb-4">Yetki Güncelle</h2>

          <form method="POST">
            <div class="form-group">
              <label>Ad Soyad</label>
              <input type="text" class="form-control" value="<?php echo $row['Ad'].' '.$row['Soyad']; ?>" disabled>
            </div>
            <div class="form-group">
              <label>Mevcut Rol</label>
              <input type="text" class="form-control" value="<?php echo $row['Role']; ?>" disabled>
            </div>
            <div class="form-group">
              <label>Yeni Rol</label>
              <select name="Role" class="form-control" required>
                <option value="user" <?php if($row['Role']=='user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if($row['Role']=='admin') echo 'selected'; ?>>Admin</option>
              </select>
            </div>
            <button type="submit" name="guncelle" class="btn btn-primary">Yetki Güncelle</button>
            <a href="kullanicilar.php" class="btn btn-secondary">İptal</a>
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
