<?php
session_start();
include 'baglanti.php';

// Giriş kontrolü
if (!isset($_SESSION['KullaniciID'])) {
    header("Location: login.php");
    exit;
}

// Rol kontrolü
if (!isset($_SESSION['Role'])) {
    echo "Rol tanımlı değil. Giriş yapın.";
    exit;
}

$role = $_SESSION['Role'];
$adminMi = ($role == 'admin');

// Admin ve user dışındakiler erişemesin
if ($role != 'admin' && $role != 'user') {
    echo "Bu sayfaya erişim yetkiniz yok.";
    exit;
}

// Arama sorgusu
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

// Ekleme işlemi (sadece admin)
if(isset($_POST['ekle'])){
  if(!$adminMi){
    echo "Bu işlemi yapmaya yetkiniz yok.";
    exit;
  }

  $sicil = $conn->real_escape_string($_POST['sicil']);
  $ad = $conn->real_escape_string($_POST['ad']);
  $soyad = $conn->real_escape_string($_POST['soyad']);
  $departman = $conn->real_escape_string($_POST['departman']);
  $gorev = $conn->real_escape_string($_POST['gorev']);

  $sql = "INSERT INTO Personel (Sicil, Ad, Soyad, Departman, Gorev)
          VALUES ('$sicil', '$ad', '$soyad', '$departman', '$gorev')";

  if ($conn->query($sql) === TRUE) {
    header("Location: personel.php");
    exit;
  } else {
    echo "Hata: " . $conn->error . "<br>";
  }
}

// Silme işlemi (sadece admin)
if(isset($_GET['sil'])){
  if(!$adminMi){
    echo "Bu işlemi yapmaya yetkiniz yok.";
    exit;
  }

  $id = (int)$_GET['sil'];
  $sql = "DELETE FROM Personel WHERE PersonelID=$id";
  if ($conn->query($sql) === TRUE) {
    header("Location: personel.php");
    exit;
  } else {
    echo "Hata: " . $conn->error . "<br>";
  }
}

// Güncelleme işlemi (sadece admin)
if(isset($_POST['guncelle'])){
  if(!$adminMi){
    echo "Bu işlemi yapmaya yetkiniz yok.";
    exit;
  }

  $id = (int)$_POST['id'];
  $sicil = $conn->real_escape_string($_POST['sicil']);
  $ad = $conn->real_escape_string($_POST['ad']);
  $soyad = $conn->real_escape_string($_POST['soyad']);
  $departman = $conn->real_escape_string($_POST['departman']);
  $gorev = $conn->real_escape_string($_POST['gorev']);

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
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Personel Yönetimi</title>
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
                    <h2>Personel İşlemleri (<?php echo strtoupper($role); ?>)</h2>

                    <!-- Arama Formu -->
                    <form method="GET" action="" class="form-inline mb-3">
                        <input type="text" name="arama" class="form-control mr-2" placeholder="Personel ara..." value="<?php echo htmlspecialchars($arama); ?>">
                        <button type="submit" class="btn btn-primary">Ara</button>
                    </form>

                    <!-- Ekleme Formu sadece admin için -->
                    <?php if ($adminMi): ?>
                    <h3>Personel Ekle</h3>
                    <form method="POST" class="mb-4">
                      <div class="form-group">
                        <label>Sicil:</label>
                        <input type="text" name="sicil" class="form-control" required>
                      </div>
                      <div class="form-group">
                        <label>Ad:</label>
                        <input type="text" name="ad" class="form-control" required>
                      </div>
                      <div class="form-group">
                        <label>Soyad:</label>
                        <input type="text" name="soyad" class="form-control" required>
                      </div>
                      <div class="form-group">
                        <label>Departman:</label>
                        <input type="text" name="departman" class="form-control">
                      </div>
                      <div class="form-group">
                        <label>Görev:</label>
                        <input type="text" name="gorev" class="form-control">
                      </div>
                      <button type="submit" name="ekle" class="btn btn-success mt-2">Ekle</button>
                    </form>
                    <?php endif; ?>

                    <!-- Personel Listesi -->
                    <h3>Personel Listesi</h3>
                    <table class="table table-bordered table-striped">
                      <thead class="thead-dark">
                        <tr>
                          <th>ID</th>
                          <th>Sicil</th>
                          <th>Ad</th>
                          <th>Soyad</th>
                          <th>Departman</th>
                          <th>Görev</th>
                          <th>İşlem</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                          <td><?php echo $row['PersonelID']; ?></td>
                          <td><?php echo $row['Sicil']; ?></td>
                          <td><?php echo $row['Ad']; ?></td>
                          <td><?php echo $row['Soyad']; ?></td>
                          <td><?php echo $row['Departman']; ?></td>
                          <td><?php echo $row['Gorev']; ?></td>
                          <td>
                            <?php if ($adminMi): ?>
                              <a href="?sil=<?php echo $row['PersonelID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</a>
                              <a href="?duzenle=<?php echo $row['PersonelID']; ?>" class="btn btn-warning btn-sm">Düzenle</a>
                            <?php else: ?>
                              <span class="text-muted">Yetki yok</span>
                            <?php endif; ?>
                          </td>
                        </tr>
                        <?php endwhile; ?>
                      <?php else: ?>
                        <tr><td colspan="7">Sonuç bulunamadı.</td></tr>
                      <?php endif; ?>
                      </tbody>
                    </table>

                    <!-- Güncelleme Formu sadece admin için -->
                    <?php
                    if($adminMi && isset($_GET['duzenle'])){
                      $id = (int)$_GET['duzenle'];
                      $sql = "SELECT * FROM Personel WHERE PersonelID=$id";
                      $result2 = $conn->query($sql);
                      if ($result2->num_rows == 1) {
                          $row = $result2->fetch_assoc();
                    ?>
                    <hr>
                    <h3>Personel Güncelle</h3>
                    <form method="POST" class="mb-4">
                      <input type="hidden" name="id" value="<?php echo $row['PersonelID']; ?>">
                      <div class="form-group">
                        <label>Sicil:</label>
                        <input type="text" name="sicil" class="form-control" value="<?php echo htmlspecialchars($row['Sicil']); ?>" required>
                      </div>
                      <div class="form-group">
                        <label>Ad:</label>
                        <input type="text" name="ad" class="form-control" value="<?php echo htmlspecialchars($row['Ad']); ?>" required>
                      </div>
                      <div class="form-group">
                        <label>Soyad:</label>
                        <input type="text" name="soyad" class="form-control" value="<?php echo htmlspecialchars($row['Soyad']); ?>" required>
                      </div>
                      <div class="form-group">
                        <label>Departman:</label>
                        <input type="text" name="departman" class="form-control" value="<?php echo htmlspecialchars($row['Departman']); ?>">
                      </div>
                      <div class="form-group">
                        <label>Görev:</label>
                        <input type="text" name="gorev" class="form-control" value="<?php echo htmlspecialchars($row['Gorev']); ?>">
                      </div>
                      <button type="submit" name="guncelle" class="btn btn-primary mt-2">Güncelle</button>
                    </form>
                    <?php
                      }
                    }
                    ?>

                </div> <!-- /.container-fluid -->

            </div> <!-- End of Main Content -->

            <?php include 'footer.php'; ?>

        </div> <!-- End of Content Wrapper -->
    </div> <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- JS Dosyaları -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
