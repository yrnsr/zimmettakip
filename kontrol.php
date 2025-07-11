<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Giriş kontrolü
 */
function girisKontrolu() {
    if (!isset($_SESSION['KullaniciID'])) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Roller kontrolü – admin, user vs.
 */
function rolKontrolu(array $roller) {
    if (!isset($_SESSION['Role'])) {
        exit("Rol bilgisi bulunamadı, giriş yapınız.");
    }
    if (!in_array($_SESSION['Role'], $roller)) {
        exit("Bu sayfaya erişim yetkiniz yok.");
    }
}

/**
 * Sadece admin erişsin
 */
function adminKontrol() {
    if (!isset($_SESSION['Role']) || $_SESSION['Role'] != 'admin') {
        exit("Bu sayfaya erişim yetkiniz yok.");
    }
}
?>
