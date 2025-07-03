<?php
include 'baglanti.php';
include 'kontrol.php';

girisKontrolu();
rolKontrolu(['admin', 'user']); // Hem admin hem user görebilir

// EKLEME
if(isset($_POST['ekle'])){
  $markamodel = $_POST['markamodel'];
  $serino = $_POST['serino'];
  $ozellik = $_POST['ozellik'];

  $sql = "INSERT INTO Esya (MarkaModel, SeriNo, Ozellik)
          VALUES ('$markamodel', '$serino', '$ozellik')";

  if ($conn->query($sql) === TRUE) {
    echo "Eşya eklendi.<br>";
  } else {
    echo "Hata: " . $conn->error . "<br>";
  }
}

// SİLME
if(isset($_GET['sil'])){
  $id = $_GET['sil'];
  $sql = "DELETE FROM Esya WHERE EsyaID=$id";
  if ($conn->query($sql) === TRUE) {
    echo "Eşya silindi.<br>";
  } else {
    echo "Hata: " . $conn->error . "<br>";
  }
}

// GÜNCELLEME
if(isset($_POST['guncelle'])){
  $id = $_POST['id'];
  $markamodel = $_POST['markamodel'];
  $serino = $_POST['serino'];
  $ozellik = $_POST['ozellik'];

  $sql = "UPDATE Esya SET 
            MarkaModel='$markamodel',
            SeriNo='$serino',
            Ozellik='$ozellik'
          WHERE EsyaID=$id";

  if ($conn->query($sql) === TRUE) {
    echo "Eşya güncellendi.<br>";
  } else {
    echo "Hata: " . $conn->error . "<br>";
  }
}

// ARAMA
$arama = "";
if(isset($_GET['arama'])){
    $arama = $_GET['arama'];
    $query = "SELECT * FROM Esya WHERE 
        MarkaModel LIKE '%$arama%' OR 
        SeriNo LIKE '%$arama%' OR 
        Ozellik LIKE '%$arama%'";
} else {
    $query = "SELECT * FROM Esya";
}
$result = $conn->query($query);
?>

<!-- 🔍 ARAMA FORMU -->
<form method="GET" action="">
    <input type="text" name="arama" placeholder="Eşya ara..." value="<?php echo $arama; ?>">
    <button type="submit">Ara</button>
</form>

<!-- ➕ EKLEME FORMU -->
<h2>Eşya Ekle</h2>
<form method="POST">
  Marka/Model: <input type="text" name="markamodel" required><br>
  Seri No: <input type="text" name="serino"><br>
  Özellik: <input type="text" name="ozellik"><br>
  <input type="submit" name="ekle" value="Ekle">
</form>

<!-- 📋 EŞYA LİSTESİ -->
<h2>Eşya Listesi</h2>
<table border="1">
  <tr>
    <th>ID</th>
    <th>Marka/Model</th>
    <th>Seri No</th>
    <th>Özellik</th>
    <th>İşlem</th>
  </tr>
  <?php
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){ ?>
    <tr>
      <td><?php echo $row['EsyaID']; ?></td>
      <td><?php echo $row['MarkaModel']; ?></td>
      <td><?php echo $row['SeriNo']; ?></td>
      <td><?php echo $row['Ozellik']; ?></td>
      <td>
        <a href="?sil=<?php echo $row['EsyaID']; ?>">Sil</a> | 
        <a href="?duzenle=<?php echo $row['EsyaID']; ?>">Düzenle</a>
      </td>
    </tr>
  <?php }
  } else { ?>
    <tr>
      <td colspan="5">Sonuç bulunamadı.</td>
    </tr>
  <?php } ?>
</table>

<!-- ✏️ GÜNCELLEME FORMU -->
<?php
if(isset($_GET['duzenle'])){
  $id = $_GET['duzenle'];
  $sql = "SELECT * FROM Esya WHERE EsyaID=$id";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
?>

<hr>
<h2>Eşya Güncelle</h2>
<form method="POST">
  <input type="hidden" name="id" value="<?php echo $row['EsyaID']; ?>">
  Marka/Model: <input type="text" name="markamodel" value="<?php echo $row['MarkaModel']; ?>" required><br>
  Seri No: <input type="text" name="serino" value="<?php echo $row['SeriNo']; ?>"><br>
  Özellik: <input type="text" name="ozellik" value="<?php echo $row['Ozellik']; ?>"><br>
  <input type="submit" name="guncelle" value="Güncelle">
</form>
<?php } ?>

<?php $conn->close(); ?>
