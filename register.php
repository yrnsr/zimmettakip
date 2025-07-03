<?php
session_start();
include 'baglanti.php';

$error = "";
$success = "";

if (isset($_POST['kayit'])) {
    $sicilNo = $conn->real_escape_string($_POST['sicilNo']);
    $ad = $conn->real_escape_string($_POST['ad']);
    $soyad = $conn->real_escape_string($_POST['soyad']);
    $email = $conn->real_escape_string($_POST['email']);
    $sifre = $_POST['sifre'];
    $sifreTekrar = $_POST['sifreTekrar'];

    // Basit doğrulamalar
    if ($sifre !== $sifreTekrar) {
        $error = "Şifreler eşleşmiyor.";
    } else {
        // SicilNo veya Email zaten kayıtlı mı kontrol et
        $kontrol = $conn->query("SELECT * FROM kullanici WHERE SicilNo='$sicilNo' OR Email='$email' LIMIT 1");
        if ($kontrol && $kontrol->num_rows > 0) {
            $error = "Sicil No veya Email zaten kayıtlı.";
        } else {
            // Şifreyi hashle
            $hashliSifre = password_hash($sifre, PASSWORD_DEFAULT);

            // Kayıt ekle (default role = user)
            $sql = "INSERT INTO kullanici (SicilNo, Ad, Soyad, Email, Sifre, Role) VALUES 
                    ('$sicilNo', '$ad', '$soyad', '$email', '$hashliSifre', 'user')";

            if ($conn->query($sql) === TRUE) {
                $success = "Kayıt başarılı. Giriş yapabilirsiniz.";
            } else {
                $error = "Kayıt sırasında hata oluştu: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kayıt Ol</title>

  <!-- Bootstrap CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

  <!-- SB Admin 2 CSS -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet" />
</head>
<body class="bg-gradient-primary">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-6 col-lg-7 col-md-8">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-5">
            <div class="text-center">
              <h1 class="h4 text-gray-900 mb-4">Kayıt Ol</h1>
            </div>

            <?php if($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="post" action="">
              <div class="form-group">
                <input type="text" name="sicilNo" class="form-control form-control-user" placeholder="Sicil No" required>
              </div>
              <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                  <input type="text" name="ad" class="form-control form-control-user" placeholder="Ad" required>
                </div>
                <div class="col-sm-6">
                  <input type="text" name="soyad" class="form-control form-control-user" placeholder="Soyad" required>
                </div>
              </div>
              <div class="form-group">
                <input type="email" name="email" class="form-control form-control-user" placeholder="Email" required>
              </div>
              <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                  <input type="password" name="sifre" class="form-control form-control-user" placeholder="Şifre" required>
                </div>
                <div class="col-sm-6">
                  <input type="password" name="sifreTekrar" class="form-control form-control-user" placeholder="Şifre Tekrar" required>
                </div>
              </div>
              <button type="submit" name="kayit" class="btn btn-success btn-user btn-block">Kayıt Ol</button>
            </form>

            <hr>
            <div class="text-center">
              <a class="small" href="login.php">Zaten hesabınız var mı? Giriş Yap</a>
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
