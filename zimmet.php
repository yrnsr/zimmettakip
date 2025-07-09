<?php
include 'kontrol.php';
require 'vendor/autoload.php';

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

// Kullanıcı rolünü sessiondan al
$role = $_SESSION['Role'];
$adminMi = ($role == 'admin');

// ARAMA
$arama = "";
if (isset($_GET["arama"])) {
  $arama = $conn->real_escape_string($_GET["arama"]);
}

// Düzenleme için kayıt bilgileri
$editZimmet = null;
if (isset($_GET['duzenle']) && $adminMi) {
  $duzenleID = (int)$_GET['duzenle'];
  $sql_edit = "SELECT * FROM zimmet WHERE ZimmetID = $duzenleID";
  $res_edit = $conn->query($sql_edit);
  if ($res_edit && $res_edit->num_rows === 1) {
    $editZimmet = $res_edit->fetch_assoc();
  }
}

// EKLEME
if (isset($_POST["ekle"]) && $adminMi) {
  $personelID = (int)$_POST["personelID"];
  $esyaID = (int)$_POST["esyaID"];
  $zimmetTarihi = $conn->real_escape_string($_POST["zimmetTarihi"]);
  $iadeTarihi = $conn->real_escape_string($_POST["iadeTarihi"]);
  $aciklama = $conn->real_escape_string($_POST["aciklama"]);

  $sql = "INSERT INTO zimmet (PersonelID, EsyaID, ZimmetTarihi, IadeTarihi, Aciklama)
          VALUES ($personelID, $esyaID, '$zimmetTarihi', ".($iadeTarihi ? "'$iadeTarihi'" : "NULL").", '$aciklama')";
  $conn->query($sql);
  header("Location: zimmet.php");
  exit;
}

// GÜNCELLEME
if (isset($_POST["guncelle"]) && $adminMi) {
  $id = (int)$_POST["zimmetID"];
  $personelID = (int)$_POST["personelID"];
  $esyaID = (int)$_POST["esyaID"];
  $zimmetTarihi = $conn->real_escape_string($_POST["zimmetTarihi"]);
  $iadeTarihi = $conn->real_escape_string($_POST["iadeTarihi"]);
  $aciklama = $conn->real_escape_string($_POST["aciklama"]);

  $sql = "UPDATE zimmet SET PersonelID=$personelID, EsyaID=$esyaID,
          ZimmetTarihi='$zimmetTarihi', IadeTarihi=".($iadeTarihi ? "'$iadeTarihi'" : "NULL").", Aciklama='$aciklama'
          WHERE ZimmetID=$id";
  $conn->query($sql);
  header("Location: zimmet.php");
  exit;
}

// SİLME
if (isset($_GET["sil"]) && $adminMi) {
  $id = (int)$_GET["sil"];
  $sql = "DELETE FROM zimmet WHERE ZimmetID=$id";
  $conn->query($sql);
  header("Location: zimmet.php");
  exit;
}

// LİSTELEME + ARAMA
$sql = "SELECT z.*, p.Ad AS PersonelAd, p.Soyad AS PersonelSoyad, e.Marka, e.Model AS EsyaMarkaModel
        FROM zimmet z
        JOIN Personel p ON z.PersonelID = p.PersonelID
        JOIN Esya e ON z.EsyaID = e.EsyaID";

if (!empty($arama)) {
  $sql .= " WHERE p.Ad LIKE '%$arama%' OR p.Soyad LIKE '%$arama%' OR 
            e.Marka LIKE '%$arama%' OR e.Model LIKE '%$arama%' OR z.ZimmetTarihi LIKE '%$arama%' OR z.IadeTarihi LIKE '%$arama%' OR z.Aciklama LIKE '%$arama%'";
}

$result = $conn->query($sql);

// Personel ve Esya listeleri (Form dropdown için)
$personeller = $conn->query("SELECT PersonelID, Ad, Soyad FROM Personel");
$esyalar = $conn->query("SELECT EsyaID, Marka, Model FROM Esya");
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Zimmet Yönetimi</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" />
  <link href="css/sb-admin-2.min.css" rel="stylesheet" />
</head>
<body id="page-top">
<div id="wrapper">
<?php include 'sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
  <div id="content">
    <?php include 'topbar.php'; ?>

    <div class="container-fluid">
      <h2 class="mb-4">Zimmet İşlemleri (<?php echo strtoupper($role); ?>)</h2>

      <!-- Arama Formu -->
      <form method="get" action="" class="form-inline mb-4">
        <input type="text" name="arama" placeholder="Zimmet ara..." value="<?php echo htmlspecialchars($arama); ?>" class="form-control mr-2">
        <button type="submit" class="btn btn-primary">Ara</button>
      </form>

      <!-- Ekleme / Güncelleme Formu sadece admin için -->
      <?php if($adminMi): ?>
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h5 class="m-0 font-weight-bold text-<?php echo $editZimmet ? 'warning' : 'success'; ?>">
            <?php echo $editZimmet ? "Zimmet Güncelle" : "Yeni Zimmet Ekle"; ?>
          </h5>
        </div>
        <div class="card-body">
          <form method="post">
            <?php if($editZimmet): ?>
              <input type="hidden" name="zimmetID" value="<?php echo htmlspecialchars($editZimmet['ZimmetID']); ?>">
            <?php endif; ?>
            <div class="form-group">
              <label>Personel</label>
              <select name="personelID" class="form-control" required>
                <option value="">Seçiniz</option>
                <?php while($p = $personeller->fetch_assoc()): ?>
                  <option value="<?php echo $p['PersonelID']; ?>"
                    <?php if($editZimmet && $editZimmet['PersonelID']==$p['PersonelID']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($p['Ad']." ".$p['Soyad']); ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Eşya</label>
              <select name="esyaID" class="form-control" required>
                <option value="">Seçiniz</option>
                <?php while($e = $esyalar->fetch_assoc()): ?>
                  <option value="<?php echo $e['EsyaID']; ?>"
                    <?php if($editZimmet && $editZimmet['EsyaID']==$e['EsyaID']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($e['MarkaModel']); ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="form-group">
              <label>Zimmet Tarihi</label>
              <input type="date" name="zimmetTarihi" class="form-control" required value="<?php echo htmlspecialchars($editZimmet['ZimmetTarihi'] ?? ''); ?>">
            </div>
            <div class="form-group">
              <label>İade Tarihi</label>
              <input type="date" name="iadeTarihi" class="form-control" value="<?php echo htmlspecialchars($editZimmet['IadeTarihi'] ?? ''); ?>">
            </div>
            <div class="form-group">
              <label>Açıklama</label>
              <input type="text" name="aciklama" class="form-control" value="<?php echo htmlspecialchars($editZimmet['Aciklama'] ?? ''); ?>">
            </div>
            <button type="submit" name="<?php echo $editZimmet ? 'guncelle' : 'ekle'; ?>" class="btn btn-<?php echo $editZimmet ? 'warning' : 'success'; ?>">
              <?php echo $editZimmet ? 'Güncelle' : 'Ekle'; ?>
            </button>
            <?php if($editZimmet): ?>
              <a href="zimmet.php" class="btn btn-secondary">İptal</a>
            <?php endif; ?>
          </form>
        </div>
      </div>
      <?php endif; ?>

      <!-- Zimmet Listesi -->
      <div class="card shadow">
        <div class="card-header py-3">
          <h5 class="m-0 font-weight-bold text-primary">Zimmet Listesi</h5>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-bordered table-striped table-hover">
            <thead class="thead-light">
              <tr>
                <th>ID</th>
                <th>Personel</th>
                <th>Eşya</th>
                <th>Zimmet Tarihi</th>
                <th>İade Tarihi</th>
                <th>Açıklama</th>
                <?php if($adminMi): ?><th>İşlem</th><?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php
                if($result && $result->num_rows > 0){
                  while($row = $result->fetch_assoc()){
                    echo "<tr>
                      <td>".htmlspecialchars($row['ZimmetID'])."</td>
                      <td>".htmlspecialchars($row['PersonelAd']." ".$row['PersonelSoyad'])."</td>
                      <td>".htmlspecialchars($row['EsyaMarkaModel'])."</td>
                      <td>".htmlspecialchars($row['ZimmetTarihi'])."</td>
                      <td>".htmlspecialchars($row['IadeTarihi'] ?: '-')."</td>
                      <td>".htmlspecialchars($row['Aciklama'] ?? '')."</td>";
                    if ($adminMi) {
                      echo "<td>
                              <a href='?duzenle=".urlencode($row['ZimmetID'])."' class='btn btn-warning btn-sm'>Düzenle</a> 
                              <a href='?sil=".urlencode($row['ZimmetID'])."' class='btn btn-danger btn-sm' onclick=\"return confirm('Silmek istediğinize emin misiniz?');\">Sil</a>
                              <a href='zimmet_yazdir.php?id={$row['ZimmetID']}' class='btn btn-info btn-sm'>Yazdır</a>
                            </td>";
                    }
                    echo "</tr>";
                  }
                } else {
                  echo "<tr><td colspan='".($adminMi ? '7' : '6')."' class='text-center'>Sonuç bulunamadı.</td></tr>";
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>

    </div> <!-- /.container-fluid -->
  </div> <!-- End of Main Content -->
  <?php include 'footer.php'; ?>
</div> <!-- End of Content Wrapper -->
</div> <!-- End of Page Wrapper -->

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
