<?php
session_start();
include 'baglanti.php';

// Giriş kontrolü
if (!isset($_SESSION['KullaniciID'])) {
    header("Location: login.php");
    exit;
}

// Rol kontrolü
$role = $_SESSION['Role'] ?? '';

if($role != 'admin'){
    // Admin değilse engelle
    header("Location: esya.php");
    exit;
}

$error = '';

// Ekleme işlemi
if(isset($_POST['ekle'])){
    $esyaID = $conn->real_escape_string($_POST['EsyaID']);
    $esyaAdi = $conn->real_escape_string($_POST['EsyaAdi']);
    $marka = $conn->real_escape_string($_POST['Marka']);
    $model = $conn->real_escape_string($_POST['Model']);
    $seriNo = $conn->real_escape_string($_POST['SeriNo']);
    $aciklama = $conn->real_escape_string($_POST['Aciklama']);

    $sql = "INSERT INTO Esya (EsyaID, EsyaAdi, Marka, Model, SeriNo, Aciklama) 
            VALUES ('$esyaID', '$esyaAdi', '$marka', '$model', '$seriNo', '$aciklama')";

    if ($conn->query($sql) === TRUE) {
        header("Location: esya.php");
        exit;
    } else {
        $error = "Hata: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Yeni Eşya Ekle</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />
    <style>
      /* Sola yaslama ve container genişliği */
      .form-container {
        max-width: 600px;
        padding-left: 0;
        margin-top: 20px;
      }
    </style>
</head>
<body id="page-top">
<div id="wrapper">

  <?php include 'sidebar.php'; ?>

  <div id="content-wrapper" class="d-flex flex-column">
    <div id="content">
      <?php include 'topbar.php'; ?>

      <div class="container form-container">
        <h3 class="text-primary mb-4">Yeni Eşya Ekle</h3>

        <?php if($error): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
          <div class="mb-3">
            <label for="EsyaID" class="form-label">Eşya ID</label>
            <input type="text" name="EsyaID" id="EsyaID" class="form-control" required />
          </div>

          <div class="mb-3">
            <label for="EsyaAdi" class="form-label">Eşya Adı</label>
            <input type="text" name="EsyaAdi" id="EsyaAdi" class="form-control" required />
          </div>

          <div class="mb-3">
            <label for="Marka" class="form-label">Marka</label>
            <input type="text" name="Marka" id="Marka" class="form-control" />
          </div>

          <div class="mb-3">
            <label for="Model" class="form-label">Model</label>
            <input type="text" name="Model" id="Model" class="form-control" />
          </div>

          <div class="mb-3">
            <label for="SeriNo" class="form-label">Seri No</label>
            <input type="text" name="SeriNo" id="SeriNo" class="form-control" />
          </div>

          <div class="mb-3">
            <label for="Aciklama" class="form-label">Açıklama</label>
            <input type="text" name="Aciklama" id="Aciklama" class="form-control" />
          </div>

          <button type="submit" name="ekle" class="btn btn-primary"><i class="fas fa-plus"></i> Ekle</button>
          <a href="esya.php" class="btn btn-secondary ms-2">İptal</a>
        </form>

      </div>

    </div> <!-- End of Content -->
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
