<?php
session_start();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Erişim Yetkiniz Yok</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" />
  <link href="css/sb-admin-2.min.css" rel="stylesheet" />
</head>
<body class="bg-gradient-danger">

  <div class="container mt-5">
    <div class="card shadow border-left-danger">
      <div class="card-body text-center">
        <h1 class="text-danger"><i class="fas fa-ban"></i> Erişim Reddedildi</h1>
        <p class="lead">Bu sayfaya erişim yetkiniz bulunmamaktadır.</p>
        <a href="anasayfa.php" class="btn btn-primary mt-3"><i class="fas fa-home"></i> Ana Sayfaya Dön</a>
      </div>
    </div>
  </div>

</body>
</html>
