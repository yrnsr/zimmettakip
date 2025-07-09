  <?php
  session_start();
  include 'baglanti.php';

  // Giriş kontrolü
  if (!isset($_SESSION['KullaniciID'])) {
      header("Location: login.php");
      exit;
  }

  // Rol kontrolü
  $role = $_SESSION['Role'] ?? '';

  $arama = "";
  if(isset($_GET['arama'])){
      $arama = $conn->real_escape_string($_GET['arama']);
      $query = "SELECT * FROM Esya WHERE 
          EsyaID LIKE '%$arama%' OR
          MarkaModel LIKE '%$arama%' OR
          SeriNo LIKE '%$arama%' OR
          Ozellik LIKE '%$arama%'";
  } else {
      $query = "SELECT * FROM Esya";
  }
  $result = $conn->query($query);

  // Ekleme (sadece admin)
  if($role == 'admin' && isset($_POST['ekle'])){
    $esyaID = $conn->real_escape_string($_POST['EsyaID']);
    $markaModel = $conn->real_escape_string($_POST['MarkaModel']);
    $seriNo = $conn->real_escape_string($_POST['SeriNo']);
    $ozellik = $conn->real_escape_string($_POST['Ozellik']);

    $sql = "INSERT INTO Esya (EsyaID, MarkaModel, SeriNo, Ozellik)
            VALUES ('$esyaID', '$markaModel', '$seriNo', '$ozellik')";

    if ($conn->query($sql) === TRUE) {
      header("Location: esya.php");
      exit;
    } else {
      $error = "Hata: " . $conn->error;
    }
  }

  // Silme (sadece admin)
  if($role == 'admin' && isset($_GET['sil'])){
    $id = $conn->real_escape_string($_GET['sil']);
    $sql = "DELETE FROM Esya WHERE EsyaID='$id'";
    if ($conn->query($sql) === TRUE) {
      header("Location: esya.php");
      exit;
    } else {
      $error = "Hata: " . $conn->error;
    }
  }

  // Güncelleme (sadece admin)
  if($role == 'admin' && isset($_POST['guncelle'])){
    $id = $conn->real_escape_string($_POST['id']);
    $esyaID = $conn->real_escape_string($_POST['EsyaID']);
    $markaModel = $conn->real_escape_string($_POST['MarkaModel']);
    $seriNo = $conn->real_escape_string($_POST['SeriNo']);
    $ozellik = $conn->real_escape_string($_POST['Ozellik']);

    $sql = "UPDATE Esya SET 
              EsyaID='$esyaID',
              MarkaModel='$markaModel',
              SeriNo='$seriNo',
              Ozellik='$ozellik'
            WHERE EsyaID='$id'";

    if ($conn->query($sql) === TRUE) {
      header("Location: esya.php");
      exit;
    } else {
      $error = "Hata: " . $conn->error;
    }
  }
  ?>

  <!DOCTYPE html>
  <html lang="tr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Eşya Yönetimi</title>
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
          <h2 class="mb-4">Eşya İşlemleri (<?php echo htmlspecialchars(strtoupper($role)); ?>)</h2>

          <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>

          <!-- Arama Formu -->
          <form method="GET" action="" class="form-inline mb-4">
            <div class="input-group" style="max-width: 400px;">
              <input type="text" name="arama" class="form-control" placeholder="Eşya ara..." value="<?php echo htmlspecialchars($arama); ?>">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Ara</button>
              </div>
            </div>
          </form>

          <?php if($role == 'admin'): ?>
          <!-- Ekleme Formu -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h5 class="m-0 font-weight-bold text-success">Yeni Eşya Ekle</h5>
            </div>
            <div class="card-body">
              <form method="POST" class="row g-3">
                <div class="col-md-3">
                  <label>Eşya ID</label>
                  <input type="text" name="EsyaID" class="form-control" required>
                </div>
                <div class="col-md-3">
                  <label>Eşya Adi</label>
                  <input type="text" name="EsyaAdi" class="form-control" required>
                </div>
                <div class="col-md-3">
                  <label>Marka</label>
                  <input type="text" name="MarkaModel" class="form-control">
                </div>
              <div class="col-md-3">
                  <label>Model</label>
                  <input type="text" name="MarkaModel" class="form-control">
                </div>
                <div class="col-md-3">
                  <label>Seri No</label>
                  <input type="text" name="SeriNo" class="form-control">
                </div>
                <div class="col-md-3">
                  <label>Özellik</label>
                  <input type="text" name="Ozellik" class="form-control">
                </div>
                <div class="col-12 mt-3">
                  <button type="submit" name="ekle" class="btn btn-success"><i class="fas fa-plus"></i> Ekle</button>
                </div>
              </form>
            </div>
          </div>
          <?php endif; ?>

          <!-- Listeleme -->
          <div class="card shadow">
            <div class="card-header py-3">
              <h5 class="m-0 font-weight-bold text-primary">Eşya Listesi</h5>
            </div>
            <div class="card-body table-responsive">
              <table class="table table-bordered table-hover">
                <thead class="thead-light">
                  <tr>
                    <th>ID</th>
                    <th>Adı</th>
                    <th>Marka</th>
                    <th>Model</th>
                    <th>Seri No</th>
                    <th>Özellik</th>
                    <?php if($role == 'admin'): ?><th>İşlem</th><?php endif; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($row['EsyaID']); ?></td>
                      <td><?php echo htmlspecialchars($row['EsyaAdi']); ?></td>
                      <td><?php echo htmlspecialchars($row['Marka']); ?></td>
                      <td><?php echo htmlspecialchars($row['Model']); ?></td>
                      <td><?php echo htmlspecialchars($row['SeriNo']); ?></td>
                      <td><?php echo htmlspecialchars($row['Ozellik']); ?></td>
                      <?php if($role == 'admin'): ?>
                      <td>
                        <a href="?duzenle=<?php echo urlencode($row['EsyaID']); ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Düzenle</a>
                        <a href="?sil=<?php echo urlencode($row['EsyaID']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Silmek istediğinize emin misiniz?')"><i class="fas fa-trash"></i> Sil</a>
                      </td>
                      <?php endif; ?>
                    </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr><td colspan="<?php echo ($role == 'admin') ? '5' : '4'; ?>" class="text-center">Sonuç bulunamadı.</td></tr>
                  <?php endif; ?>
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
