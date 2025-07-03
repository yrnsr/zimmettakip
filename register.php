<?php
// Bağlantı bilgileri
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zimmetdb";

// Bağlantı oluştur
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$hata = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sicilNo = $conn->real_escape_string($_POST['sicilNo']);
    $ad = $conn->real_escape_string($_POST['ad']);
    $soyad = $conn->real_escape_string($_POST['soyad']);
    $email = $conn->real_escape_string($_POST['email']);
    $sifre = $_POST['sifre'];
    $sifreHash = password_hash($sifre, PASSWORD_DEFAULT);

    // Email veya SicilNo zaten kayıtlı mı kontrol et
    $kontrolSql = "SELECT * FROM Kullanici WHERE Email='$email' OR SicilNo='$sicilNo'";
    $kontrolSonuc = $conn->query($kontrolSql);
    if ($kontrolSonuc->num_rows > 0) {
        $hata = "Bu Sicil No veya Email zaten kayıtlı.";
    } else {
        $sql = "INSERT INTO Kullanici (SicilNo, Ad, Soyad, Email, Sifre) 
                VALUES ('$sicilNo', '$ad', '$soyad', '$email', '$sifreHash')";
        if ($conn->query($sql) === TRUE) {
            echo "Kayıt başarılı. <a href='login.php'>Giriş yap</a>";
            exit;
        } else {
            $hata = "Kayıt sırasında hata oluştu: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kayıt Ol</title>
</head>
<body>
    <h2>Kayıt Ol</h2>
    <?php if ($hata) echo "<p style='color:red;'>$hata</p>"; ?>
    <form method="POST" action="">
        Sicil No: <input type="text" name="sicilNo" required><br><br>
        Ad: <input type="text" name="ad" required><br><br>
        Soyad: <input type="text" name="soyad" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        Şifre: <input type="password" name="sifre" required><br><br>
        <input type="submit" value="Kayıt Ol">
    </form>
</body>
</html>
