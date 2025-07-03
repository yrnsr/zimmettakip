<?php
include 'baglanti.php'; // veritabanı bağlantı dosyan

$hata = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kullaniciAdi = $conn->real_escape_string($_POST["kullaniciAdi"]);
    $sifre = $conn->real_escape_string($_POST["sifre"]);
    $role = $conn->real_escape_string($_POST["role"]);

    // Kullanıcı adı boş mu
    if(empty($kullaniciAdi) || empty($sifre) || empty($role)){
        $hata = "Tüm alanları doldurun.";
    } else {
        // Şifre hashleme (güvenlik için)
        $hashedPassword = password_hash($sifre, PASSWORD_DEFAULT);

        // Aynı kullanıcı var mı
        $sql_check = "SELECT * FROM Kullanici WHERE KullaniciAdi='$kullaniciAdi'";
        $result_check = $conn->query($sql_check);
        if ($result_check->num_rows > 0) {
            $hata = "Bu kullanıcı adı zaten kayıtlı.";
        } else {
            $sql = "INSERT INTO Kullanici (KullaniciAdi, Sifre, Role) VALUES ('$kullaniciAdi', '$hashedPassword', '$role')";
            if ($conn->query($sql) === TRUE) {
                header("Location: login.php");
                exit;
            } else {
                $hata = "Kayıt hatası: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <title>Kayıt Ol</title>
  <link href="css/sb-admin-2.min.css" rel="stylesheet" />
</head>
<body class="bg-gradient-primary">
  <div class="container mt-5">
    <div class="card mx-auto" style="max-width: 400px;">
      <div class="card-body">
        <h4 class="card-title text-center">Kayıt Ol</h4>
        <?php if($hata) echo "<div class='alert alert-danger'>$hata</div>"; ?>
        <form method="post">
          <div class="form-group">
            <label>Kullanıcı Adı</label>
            <input type="text" name="kullaniciAdi" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Şifre</label>
            <input type="password" name="sifre" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Rol</label>
            <select name="role" class="form-control" required>
              <option value="user">User</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Kayıt Ol</button>
        </form>
        <p class="mt-3 text-center">Hesabın var mı? <a href="login.php">Giriş Yap</a></p>
      </div>
    </div>
  </div>
</body>
</html>
