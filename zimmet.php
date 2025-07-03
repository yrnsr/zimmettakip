<?php
include 'kontrol.php';

girisKontrolu();
rolKontrolu(['admin', 'user']);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zimmetdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Bağlantı hatası: " . $conn->connect_error);
}

// ARAMA DEĞİŞKENİ
$arama = "";
if (isset($_GET["arama"])) {
  $arama = $_GET["arama"];
}

// EKLEME
if (isset($_POST["ekle"])) {
  $personelID = $_POST["personelID"];
  $esyaID = $_POST["esyaID"];
  $zimmetTarihi = $_POST["zimmetTarihi"];
  $iadeTarihi = $_POST["iadeTarihi"];
  $aciklama = $_POST["aciklama"];

  $sql = "INSERT INTO zimmet (PersonelID, EsyaID, ZimmetTarihi, IadeTarihi, Aciklama)
          VALUES ('$personelID', '$esyaID', '$zimmetTarihi', '$iadeTarihi', '$aciklama')";
  $conn->query($sql);
}

// SİLME
if (isset($_GET["sil"])) {
  $id = $_GET["sil"];
  $sql = "DELETE FROM zimmet WHERE ZimmetID=$id";
  $conn->query($sql);
}

// GÜNCELLEME
if (isset($_POST["guncelle"])) {
  $id = $_POST["zimmetID"];
  $personelID = $_POST["personelID"];
  $esyaID = $_POST["esyaID"];
  $zimmetTarihi = $_POST["zimmetTarihi"];
  $iadeTarihi = $_POST["iadeTarihi"];
  $aciklama = $_POST["aciklama"];

  $sql = "UPDATE zimmet SET PersonelID='$personelID', EsyaID='$esyaID',
          ZimmetTarihi='$zimmetTarihi', IadeTarihi='$iadeTarihi', Aciklama='$aciklama'
          WHERE ZimmetID=$id";
  $conn->query($sql);
}
?>

<!-- 🔍 ARAMA FORMU -->
<form method="get" action="">
  <input type="text" name="arama" placeholder="Zimmet ara..." value="<?php echo $arama; ?>">
  <button type="submit">Ara</button>
</form>

<h2>Zimmet Ekle / Güncelle</h2>
<form method="post" action="">
  <input type="hidden" name="zimmetID" value="<?php if(isset($_GET['duzenle'])) echo $_GET['duzenle']; ?>">
  PersonelID: <input type="text" name="personelID" required><br><br>
  EsyaID: <input type="text" name="esyaID" required><br><br>
  Zimmet Tarihi: <input type="date" name="zimmetTarihi" required><br><br>
  İade Tarihi: <input type="date" name="iadeTarihi"><br><br>
  Açıklama: <input type="text" name="aciklama"><br><br>
  <button type="submit" name="ekle">Ekle</button>
  <button type="submit" name="guncelle">Güncelle</button>
</form>

<hr>

<h2>Zimmet Listesi</h2>
<table border="1">
  <tr>
    <th>ID</th>
    <th>PersonelID</th>
    <th>EsyaID</th>
    <th>Zimmet Tarihi</th>
    <th>İade Tarihi</th>
    <th>Açıklama</th>
    <th>İşlem</th>
  </tr>

<?php
// LİSTELEME + ARAMA
$sql = "SELECT * FROM zimmet";

if (!empty($arama)) {
  $sql .= " WHERE PersonelID LIKE '%$arama%' OR EsyaID LIKE '%$arama%' OR 
            ZimmetTarihi LIKE '%$arama%' OR IadeTarihi LIKE '%$arama%' OR Aciklama LIKE '%$arama%'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    echo "<tr>
      <td>".$row["ZimmetID"]."</td>
      <td>".$row["PersonelID"]."</td>
      <td>".$row["EsyaID"]."</td>
      <td>".$row["ZimmetTarihi"]."</td>
      <td>".$row["IadeTarihi"]."</td>
      <td>".$row["Aciklama"]."</td>
      <td>
        <a href='?sil=".$row["ZimmetID"]."'>Sil</a> |
        <a href='?duzenle=".$row["ZimmetID"]."'>Düzenle</a>
      </td>
    </tr>";
  }
} else {
  echo "<tr><td colspan='7'>Kayıt yok.</td></tr>";
}
$conn->close();
?>
</table>
