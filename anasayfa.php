<?php
include 'kontrol.php';
include 'baglanti.php';

girisKontrolu();

$kullaniciAdi = $_SESSION['KullaniciAdi'] ?? '';

// Personel sayısı
$sql_personel = "SELECT COUNT(*) AS toplam_personel FROM personel";
$result_personel = $conn->query($sql_personel);
$row_personel = $result_personel->fetch_assoc();
$personel_sayisi = $row_personel['toplam_personel'] ?? 0;

// Eşya sayısı
$sql_esya = "SELECT COUNT(*) AS toplam_esya FROM esya";
$result_esya = $conn->query($sql_esya);
$row_esya = $result_esya->fetch_assoc();
$esya_sayisi = $row_esya['toplam_esya'] ?? 0;

// Zimmet sayısı
$sql_zimmet = "SELECT COUNT(*) AS toplam_zimmet FROM zimmet";
$result_zimmet = $conn->query($sql_zimmet);
$row_zimmet = $result_zimmet->fetch_assoc();
$zimmet_sayisi = $row_zimmet['toplam_zimmet'] ?? 0;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Ana Sayfa - Zimmet Takip</title>
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

          <!-- Sayfa Başlığı -->
          <h1 class="h3 mb-4 text-gray-800">Hoşgeldiniz, <?php echo htmlspecialchars($kullaniciAdi); ?>!</h1>

          <div class="row">

            <!-- Personel Sayısı Card -->
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card shadow h-100 py-4" style="background: linear-gradient(135deg, #36b9cc, #2c9faf); color: white;">
                <div class="card-body text-center">
                  <i class="fas fa-users fa-3x mb-3"></i>
                  <h4 class="font-weight-bold">Personel Sayısı</h4>
                  <h2 class="font-weight-bold"><?php echo $personel_sayisi; ?></h2>
                </div>
              </div>
            </div>

            <!-- Eşya Sayısı Card -->
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card shadow h-100 py-4" style="background: linear-gradient(135deg, #1cc88a, #17a673); color: white;">
                <div class="card-body text-center">
                  <i class="fas fa-box fa-3x mb-3"></i>
                  <h4 class="font-weight-bold">Eşya Sayısı</h4>
                  <h2 class="font-weight-bold"><?php echo $esya_sayisi; ?></h2>
                </div>
              </div>
            </div>

            <!-- Zimmet Sayısı Card -->
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card shadow h-100 py-4" style="background: linear-gradient(135deg, #f6c23e, #dda20a); color: white;">
                <div class="card-body text-center">
                  <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                  <h4 class="font-weight-bold">Zimmet Sayısı</h4>
                  <h2 class="font-weight-bold"><?php echo $zimmet_sayisi; ?></h2>
                </div>
              </div>
            </div>

          </div>

          <!-- Hoşgeldin Kartı -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card shadow mb-4">
                <div class="card-body">
                  <h5 class="card-title">Zimmet Takip Sistemine Hoşgeldiniz</h5>
                  <p class="card-text">Bu sistem üzerinden personel, eşya ve zimmet işlemlerinizi kolayca yönetebilirsiniz.</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Yönetim Kartları -->
          <div class="row">

            <!-- Personel Yönetimi -->
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <a href="personel.php" class="card-body text-decoration-none text-dark">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Personel Yönetimi</div>
                      <div class="h5 mb-0 font-weight-bold">Personel İşlemleri</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </a>
              </div>
            </div>

            <!-- Eşya Yönetimi -->
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <a href="esya.php" class="card-body text-decoration-none text-dark">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Eşya Yönetimi</div>
                      <div class="h5 mb-0 font-weight-bold">Eşya İşlemleri</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </a>
              </div>
            </div>

            <!-- Zimmet Yönetimi -->
            <div class="col-xl-4 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <a href="zimmet.php" class="card-body text-decoration-none text-dark">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Zimmet Yönetimi</div>
                      <div class="h5 mb-0 font-weight-bold">Zimmet İşlemleri</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </a>
              </div>
            </div>

          </div>

          <!-- Çıkış Yap Butonu -->
          <div class="text-center">
            <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Çıkış Yap</a>
          </div>

        </div>

      </div>

      <?php include 'footer.php'; ?>
    </div>
  </div>

  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/sb-admin-2.min.js"></script>
</body>
</html>
