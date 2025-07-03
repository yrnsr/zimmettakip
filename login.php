<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zimmetdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Bağlantı hatası: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sicilNo = $_POST["sicilNo"];
    $sifre = $_POST["sifre"];

    // Kullanıcıyı bul
    $stmt = $conn->prepare("SELECT KullaniciID, Ad, Soyad, Sifre, Role FROM Kullanici WHERE SicilNo = ?");
    $stmt->bind_param("s", $sicilNo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Şifre kontrolü (hashlenmiş şifre kullanıyorsak)
        if (password_verify($sifre, $user['Sifre'])) {
            // Giriş başarılı, session başlat
            $_SESSION['KullaniciID'] = $user['KullaniciID'];
            $_SESSION['Ad'] = $user['Ad'];
            $_SESSION['Soyad'] = $user['Soyad'];
            $_SESSION['Role'] = $user['Role'];
            header("Location: anasayfa.php");
            exit();
        } else {
            $error = "Hatalı şifre.";
        }
    } else {
        $error = "Kullanıcı bulunamadı.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head><meta charset="UTF-8"><title>Giriş Yap</title></head>
<body>
<h2>Giriş Yap</h2>
<form method="post" action="">
    Sicil No: <input type="text" name="sicilNo" required><br><br>
    Şifre: <input type="password" name="sifre" required><br><br>
    <input type="submit" value="Giriş Yap">
</form>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
