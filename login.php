<?php
session_start();
include 'baglanti.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sicilNo = $conn->real_escape_string($_POST['sicilno']);
    $sifre = $_POST['sifre'];

    $sql = "SELECT KullaniciID, SicilNo, Sifre, Role, RoleID, Ad, Soyad 
            FROM Kullanici 
            WHERE SicilNo = '$sicilNo' 
            LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($sifre, $user['Sifre'])) {
            $_SESSION['KullaniciID'] = $user['KullaniciID'];
            $_SESSION['SicilNo'] = $user['SicilNo'];
            $_SESSION['Role'] = $user['Role'];
            $_SESSION['RoleID'] = $user['RoleID'];
            $_SESSION['RoleName'] = $user['Role'];
            $_SESSION['Ad'] = $user['Ad'];
            $_SESSION['Soyad'] = $user['Soyad'];

            header("Location: anasayfa.php");
            exit;
        } else {
            $error = "Şifre hatalı.";
        }
    } else {
        $error = "Kullanıcı bulunamadı.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Giriş Yap - Tümosan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet" />
  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Poppins', sans-serif;
      overflow: hidden;
    }

    .background {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background-size: contain;
      filter: blur(6px);
      z-index: 0;
      user-select: none;
    }

    .overlay {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: #004c99;
      z-index: 1;
    }

    .login-container {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 2;
      max-width: 380px;
      width: 90%;
      padding: 35px 45px;
      background: rgba(255, 255, 255, 0.98);
      border-radius: 18px;
      box-shadow:
        0 8px 30px rgba(0, 0, 0, 0.3),
        0 0 0 4px #0059b3;
      border: 3px solid #004c99;
    }

    .login-logo {
      text-align: center;
      margin-bottom: 25px;
    }

    .login-logo img {
      max-width: 150px;
      user-select: none;
      filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
    }

    h2.login-title {
      text-align: center;
      font-weight: 700;
      font-size: 1.9rem;
      margin-bottom: 30px;
      color: #004c99;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
    }

    .form-control {
      border-radius: 8px;
      border: 1.8px solid #004c99;
      padding: 10px 15px;
      font-size: 1rem;
      transition: border-color 0.3s ease;
    }

    .form-control:focus {
      border-color: #007acc;
      box-shadow: 0 0 8px #007acc;
      outline: none;
    }

    .btn-primary {
      width: 100%;
      font-weight: 700;
      font-size: 1.1rem;
      padding: 12px;
      border-radius: 10px;
      border: none;
      background: linear-gradient(90deg, #007acc, #004c99);
      box-shadow: 0 6px 12px rgba(0, 122, 204, 0.7);
      transition: background 0.3s ease;
    }

    .btn-primary:hover {
      background: linear-gradient(90deg, #004c99, #007acc);
      box-shadow: 0 8px 16px rgba(0, 76, 153, 0.8);
    }
  </style>
</head>
<body>

  <div class="background"></div>
  <div class="overlay"></div>

  <div class="login-container shadow">
    <div class="login-logo">
      <img src="img/tumosan.webp" alt="Tümosan Logo" />
    </div>

    <h2 class="login-title">ZİMMEP</h2>

    <?php if (isset($error)): ?>
      <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="" autocomplete="off">
      <div class="mb-4 text-start">
        <label for="sicilno" class="form-label">Sicil No</label>
        <input type="text" id="sicilno" name="sicilno" class="form-control" placeholder="Sicil numaranızı girin" required autofocus />
      </div>
      <div class="mb-4 text-start">
        <label for="sifre" class="form-label">Şifre</label>
        <input type="password" id="sifre" name="sifre" class="form-control" placeholder="Şifrenizi girin" required />
      </div>
      <button type="submit" class="btn btn-primary">Giriş Yap</button>
    </form>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
