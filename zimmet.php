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
  $arama = $conn->real_escape_string($_GET["arama"]);
}

// Düzenleme için var olan kaydın bilgilerini alma
$editZimmet = null;
if (isset($_GET['duzenle'])) {
    $duzenleID = (int)$_GET['duzenle'];
    $sql_edit = "SELECT z.*, p.Ad AS PersonelAd, p.Soyad AS PersonelSoyad, e.MarkaModel AS EsyaMarkaModel
                 FROM zimmet z
                 JOIN Personel p ON z.PersonelID = p.PersonelID
                 JOIN Esya e ON z.EsyaID = e.EsyaID
                 WHERE ZimmetID = $duzenleID";
    $res_edit = $conn->query($sql_edit);
    if ($res_edit && $res_edit->num_rows === 1) {
        $editZimmet = $res_edit->fetch_assoc();
    }
}

// EKLEME
if (isset($_POST["ekle"])) {
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
if (isset($_POST["guncelle"])) {
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
if (isset($_GET["sil"])) {
  $id = (int)$_GET["sil"];
  $sql = "DELETE FROM zimmet WHERE ZimmetID=$id";
  $conn->query($sql);
  header("Location: zimmet.php");
  exit;
}

// LİSTELEME + ARAMA
$sql = "SELECT z.*, p.Ad AS PersonelAd, p.Soyad AS PersonelSoyad, e.MarkaModel AS EsyaMarkaModel
        FROM zimmet z
        JOIN Personel p ON z.PersonelID = p.PersonelID
        JOIN Esya e ON z.EsyaID = e.EsyaID";

if (!empty($arama)) {
  $sql .= " WHERE p.Ad LIKE '%$arama%' OR p.Soyad LIKE '%$arama%' OR 
            e.MarkaModel LIKE '%$arama%' OR z.ZimmetTarihi LIKE '%$arama%' OR z.IadeTarihi LIKE '%$arama%' OR z.Aciklama LIKE '%$arama%'";
}

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Zimmet Yönetimi</title>
<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" />
<link href="css/sb-admin-2.min.css" rel="stylesheet" />
<style>
  .autocomplete-suggestions {
    border: 1px solid #ccc;
    max-height: 150px;
    overflow-y: auto;
    background: #fff;
    position: absolute;
    z-index: 1000;
    width: 400px;
  }
  .autocomplete-suggestion {
    padding: 5px;
    cursor: pointer;
  }
  .autocomplete-suggestion:hover {
    background: #ddd;
  }
</style>
</head>
<body id="page-top">
<div id="wrapper">
<?php include 'sidebar.php'; ?>
<div id="content-wrapper" class="d-flex flex-column">
  <div id="content">
    <?php include 'topbar.php'; ?>

    <div class="container-fluid">
      <h2 class="mb-4">Zimmet İşlemleri</h2>

      <!-- Arama Formu -->
      <form method="get" action="" class="form-inline mb-4">
        <div class="input-group" style="max-width: 400px;">
          <input type="text" name="arama" placeholder="Zimmet ara..." value="<?php echo htmlspecialchars($arama); ?>" class="form-control">
          <div class="input-group-append">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Ara</button>
          </div>
        </div>
      </form>

      <!-- Ekleme / Güncelleme Formu -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h5 class="m-0 font-weight-bold text-<?php echo $editZimmet ? 'warning' : 'success'; ?>">
            <?php echo $editZimmet ? "Zimmet Güncelle" : "Yeni Zimmet Ekle"; ?>
          </h5>
        </div>
        <div class="card-body">
          <form method="post" action="" autocomplete="off" class="row g-3">
            <input type="hidden" name="zimmetID" value="<?php echo $editZimmet ? $editZimmet['ZimmetID'] : ''; ?>">

            <div class="col-md-6 position-relative">
              <label for="personelInput" class="form-label">Personel</label>
              <input type="text" id="personelInput" placeholder="Personel ara..." class="form-control" style="width: 100%;"
                value="<?php echo $editZimmet ? htmlspecialchars($editZimmet['PersonelAd'] . ' ' . $editZimmet['PersonelSoyad']) : ''; ?>" required>
              <input type="hidden" name="personelID" id="personelID" value="<?php echo $editZimmet ? $editZimmet['PersonelID'] : ''; ?>" required>
              <div id="personelSuggestions" class="autocomplete-suggestions" style="display:none; max-width: 100%;"></div>
            </div>

            <div class="col-md-6 position-relative">
              <label for="esyaInput" class="form-label">Eşya</label>
              <input type="text" id="esyaInput" placeholder="Eşya ara..." class="form-control" style="width: 100%;"
                value="<?php echo $editZimmet ? htmlspecialchars($editZimmet['EsyaMarkaModel']) : ''; ?>" required>
              <input type="hidden" name="esyaID" id="esyaID" value="<?php echo $editZimmet ? $editZimmet['EsyaID'] : ''; ?>" required>
              <div id="esyaSuggestions" class="autocomplete-suggestions" style="display:none; max-width: 100%;"></div>
            </div>

            <div class="col-md-3">
              <label for="zimmetTarihi" class="form-label">Zimmet Tarihi</label>
              <input type="date" name="zimmetTarihi" id="zimmetTarihi" class="form-control" required
                value="<?php echo $editZimmet ? $editZimmet['ZimmetTarihi'] : ''; ?>">
            </div>

            <div class="col-md-3">
              <label for="iadeTarihi" class="form-label">İade Tarihi</label>
              <input type="date" name="iadeTarihi" id="iadeTarihi" class="form-control"
                value="<?php echo $editZimmet ? $editZimmet['IadeTarihi'] : ''; ?>">
            </div>

            <div class="col-md-6">
              <label for="aciklama" class="form-label">Açıklama</label>
              <input type="text" name="aciklama" id="aciklama" class="form-control"
                value="<?php echo $editZimmet ? htmlspecialchars($editZimmet['Aciklama']) : ''; ?>">
            </div>

            <div class="col-12">
              <?php if($editZimmet): ?>
                <button type="submit" name="guncelle" class="btn btn-warning"><i class="fas fa-edit"></i> Güncelle</button>
                <a href="zimmet.php" class="btn btn-secondary">İptal</a>
              <?php else: ?>
              <button type="submit" name="ekle" class="btn btn-success mt-3"><i class="fas fa-plus"></i> Ekle</button>

              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>

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
                <th>İşlem</th>
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
                      <td>".htmlspecialchars($row['Aciklama'])."</td>
                      <td>
                        <a href='?duzenle=".urlencode($row['ZimmetID'])."' class='btn btn-warning btn-sm'><i class='fas fa-edit'></i> Düzenle</a> 
                        <a href='?sil=".urlencode($row['ZimmetID'])."' class='btn btn-danger btn-sm' onclick=\"return confirm('Silmek istediğinize emin misiniz?');\"><i class='fas fa-trash'></i> Sil</a>
                      </td>
                    </tr>";
                  }
                } else {
                  echo "<tr><td colspan='7' class='text-center'>Sonuç bulunamadı.</td></tr>";
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

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>

<script>
// Basit autocomplete örneği
function autocomplete(inputEl, hiddenInputEl, suggestionsEl, apiEndpoint) {
  inputEl.addEventListener('input', function() {
    const val = this.value.trim();
    if (!val) {
      suggestionsEl.style.display = 'none';
      hiddenInputEl.value = '';
      return;
    }
    fetch(apiEndpoint + '?q=' + encodeURIComponent(val))
      .then(res => res.json())
      .then(data => {
        if(data.length){
          suggestionsEl.innerHTML = data.map(item => 
            `<div class="autocomplete-suggestion" data-id="${item.id}">${item.name}</div>`
          ).join('');
          suggestionsEl.style.display = 'block';
        } else {
          suggestionsEl.style.display = 'none';
          hiddenInputEl.value = '';
        }
      });
  });

  suggestionsEl.addEventListener('click', function(e){
    if(e.target && e.target.matches('.autocomplete-suggestion')){
      inputEl.value = e.target.textContent;
      hiddenInputEl.value = e.target.getAttribute('data-id');
      suggestionsEl.style.display = 'none';
    }
  });

  document.addEventListener('click', function(e){
    if(e.target !== inputEl && e.target !== suggestionsEl){
      suggestionsEl.style.display = 'none';
    }
  });
}

// Kullanıcılar için autocomplete (personel)
autocomplete(
  document.getElementById('personelInput'),
  document.getElementById('personelID'),
  document.getElementById('personelSuggestions'),
  'autocomplete_personel.php'
);

// Eşyalar için autocomplete
autocomplete(
  document.getElementById('esyaInput'),
  document.getElementById('esyaID'),
  document.getElementById('esyaSuggestions'),
  'autocomplete_esya.php'
);
</script>
</body>
</html>

<?php $conn->close(); ?>
