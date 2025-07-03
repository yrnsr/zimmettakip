<?php
session_start();
include 'baglanti.php';

$error = "";  // Hata mesajı için değişken tanımlandı

if (isset($_POST['giris'])) {
    $sicilNo = $conn->real_escape_string($_POST['sicilNo']);
    $sifre = $_POST['sifre'];

    $result = $conn->query("SELECT * FROM kullanici WHERE SicilNo='$sicilNo' LIMIT 1");

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();

        if (password_verify($sifre, $row['Sifre'])) {
            // Giriş başarılı
            $_SESSION['KullaniciID'] = $row['KullaniciID'];
            $_SESSION['Role'] = $row['Role'];
            $_SESSION['Ad'] = $row['Ad'];
            $_SESSION['Soyad'] = $row['Soyad'];
            header("Location: anasayfa.php");
            exit;
        } else {
            $error = "Şifre yanlış.";
        }
    } else {
        $error = "Kullanıcı bulunamadı.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Giriş Yap</title>

  <!-- Bootstrap CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

  <!-- SB Admin 2 CSS -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet" />
</head>
<body class="bg-gradient-primary">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-5 col-lg-6 col-md-8">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-5">
            <div class="text-center">
              <h1 class="h4 text-gray-900 mb-4">Giriş Yap</h1>
            </div>

            <?php if($error): ?>
            <div class="alert alert-danger" role="alert">
              <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <form method="post" action="">
              <div class="form-group">
                <input type="text" name="sicilNo" class="form-control form-control-user" placeholder="Sicil No" required>
              </div>
              <div class="form-group">
                <input type="password" name="sifre" class="form-control form-control-user" placeholder="Şifre" required>
              </div>
              <button type="submit" name="giris" class="btn btn-primary btn-user btn-block">
                Giriş Yap
              </button>
            </form>

            <hr>
            <div class="text-center">
              <a class="small" href="register.php">Hesabınız yok mu? Kayıt Ol</a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <!-- Bootstrap Bundle -->
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- SB Admin 2 JS -->
  <script src="js/sb-admin-2.min.js"></script>

</body>
</html>
