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

// Kullanıcıları listeleme
$sql = "SELECT * FROM Kullanici";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Kullanıcılar</title>
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
      <h2>Kullanıcılar</h2>

      <a href="kullaniciekle.php" class="btn btn-success mb-3">Kullanıcı Ekle</a>

      <table class="table table-bordered table-striped">
        <thead class="thead-dark">
          <tr>
            <th>ID</th>
            <th>Sicil No</th>
            <th>Ad</th>
            <th>Soyad</th>
            <th>Email</th>
            <th>Rol</th>
            <th>İşlem</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo $row['KullaniciID']; ?></td>
              <td><?php echo $row['SicilNo']; ?></td>
              <td><?php echo $row['Ad']; ?></td>
              <td><?php echo $row['Soyad']; ?></td>
              <td><?php echo $row['Email']; ?></td>
              <td><?php echo $row['Role']; ?></td>
              <td>
                <a href="kullanicidetay.php?id=<?php echo $row['KullaniciID']; ?>" class="btn btn-info btn-sm">Detay</a>
                <a href="kullaniciyetki.php?id=<?php echo $row['KullaniciID']; ?>" class="btn btn-warning btn-sm">Yetki Güncelle</a>
              </td>
            </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="7">Kayıt bulunamadı.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>
  <?php include 'footer.php'; ?>
</div>

</div>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
