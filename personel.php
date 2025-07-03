<?php
session_start();
include 'baglanti.php';

// 🔒 GİRİŞ KONTROLÜ
if (!isset($_SESSION['KullaniciID'])) {
    header("Location: login.php");
    exit;
}

// 🔒 YETKİ KONTROLÜ: SADECE ADMIN
if ($_SESSION['Role'] != 'admin') {
    echo "Bu sayfaya erişim yetkiniz yok.";
    exit;
}

// ARAMA
$arama = "";
if(isset($_GET['arama'])){
    $arama = $conn->real_escape_string($_GET['arama']);
    $query = "SELECT * FROM Personel WHERE 
        Sicil LIKE '%$arama%' OR 
        Ad LIKE '%$arama%' OR 
        Soyad LIKE '%$arama%' OR 
        Departman LIKE '%$arama%' OR
        Gorev LIKE '%$arama%'";
} else {
    $query = "SELECT * FROM Personel";
}
$result = $conn->query($query);

// EKLEME
if(isset($_POST['ekle'])){
  $sicil = $_POST['sicil'];
  $ad = $_POST['ad'];
  $soyad = $_POST['soyad'];
  $departman = $_POST['departman'];
  $gorev = $_POST['gorev'];

  $sql = "INSERT INTO Personel (Sicil, Ad, Soyad, Departman, Gorev)
          VALUES ('$sicil', '$ad', '$soyad', '$departman', '$gorev')";

  if ($conn->query($sql) === TRUE) {
    header("Location: personel.php");
    exit;
  } else {
    echo "Hata: " . $conn->error . "<br>";
  }
}

// SİLME
if(isset($_GET['sil'])){
  $id = $_GET['sil'];
  $sql = "DELETE FROM Personel WHERE PersonelID=$id";
  if ($conn->query($sql) === TRUE) {
    header("Location: personel.php");
    exit;
  } else {
    echo "Hata: " . $conn->error . "<br>";
  }
}

// GÜNCELLEME
if(isset($_POST['guncelle'])){
  $id = $_POST['id'];
  $sicil = $_POST['sicil'];
  $ad = $_POST['ad'];
  $soyad = $_POST['soyad'];
  $departman = $_POST['departman'];
  $gorev = $_POST['gorev'];

  $sql = "UPDATE Personel SET 
            Sicil='$sicil',
            Ad='$ad',
            Soyad='$soyad',
            Departman='$departman',
            Gorev='$gorev'
          WHERE PersonelID=$id";

  if ($conn->query($sql) === TRUE) {
    header("Location: personel.php");
    exit;
  } else {
    echo "Hata: " . $conn->error . "<br>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Personel İşlemleri</title>
</head>
<body>

<h2>Personel İşlemleri (Admin)</h2>

<!-- 🔍 ARAMA FORMU -->
<form method="GET" action="">
    <input type="text" name="arama" placeholder="Personel ara..." value="<?php echo htmlspecialchars($arama); ?>">
    <button type="submit">Ara</button>
</form>

<!-- ➕ EKLEME FORMU -->
<h3>Personel Ekle</h3>
<form method="POST">
  Sicil: <input type="text" name="sicil" required><br>
  Ad: <input type="text" name="ad" required><br>
  Soyad: <input type="text" name="soyad" required><br>
  Departman: <input type="text" name="departman"><br>
  Görev: <input type="text" name="gorev"><br>
  <input type="submit" name="ekle" value="Ekle">
</form>

<!-- 📋 PERSONEL LİSTESİ -->
<h3>Personel Listesi</h3>
<table border="1">
  <tr>
    <th>ID</th>
    <th>Sicil</th>
    <th>Ad</th>
    <th>Soyad</th>
    <th>Departman</th>
    <th>Görev</th>
    <th>İşlem</th>
  </tr>
  <?php
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){ ?>
    <tr>
      <td><?php echo $row['PersonelID']; ?></td>
      <td><?php echo $row['Sicil']; ?></td>
      <td><?php echo $row['Ad']; ?></td>
      <td><?php echo $row['Soyad']; ?></td>
      <td><?php echo $row['Departman']; ?></td>
      <td><?php echo $row['Gorev']; ?></td>
      <td>
        <a href="?sil=<?php echo $row['PersonelID']; ?>">Sil</a> | 
        <a href="?duzenle=<?php echo $row['PersonelID']; ?>">Düzenle</a>
      </td>
    </tr>
  <?php } 
  } else { ?>
    <tr><td colspan="7">Sonuç bulunamadı.</td></tr>
  <?php } ?>
</table>

<!-- ✏️ GÜNCELLEME FORMU -->
<?php
if(isset($_GET['duzenle'])){
  $id = $_GET['duzenle'];
  $sql = "SELECT * FROM Personel WHERE PersonelID=$id";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
?>
<hr>
<h3>Personel Güncelle</h3>
<form method="POST">
  <input type="hidden" name="id" value="<?php echo $row['PersonelID']; ?>">
  Sicil: <input type="text" name="sicil" value="<?php echo $row['Sicil']; ?>" required><br>
  Ad: <input type="text" name="ad" value="<?php echo $row['Ad']; ?>" required><br>
  Soyad: <input type="text" name="soyad" value="<?php echo $row['Soyad']; ?>" required><br>
  Departman: <input type="text" name="departman" value="<?php echo $row['Departman']; ?>"><br>
  Görev: <input type="text" name="gorev" value="<?php echo $row['Gorev']; ?>"><br>
  <input type="submit" name="guncelle" value="Güncelle">
</form>
<?php } ?>

</body>
</html>

<?php $conn->close(); ?>
