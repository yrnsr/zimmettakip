<?php
session_start();

/**
 * Giriş kontrolü: Kullanıcı giriş yapmamışsa login sayfasına yönlendir.
 */
function girisKontrolu() {
    if (!isset($_SESSION['KullaniciID'])) {
        header("Location: login.php");
        exit;
    }
}

/**
 * Rol kontrolü: Kullanıcının yetkisi yoksa erişimi engelle.
 * @param array|string $izinliRoller İzin verilen roller (admin, user)
 */
function rolKontrolu($izinliRoller = []) {
    if (!isset($_SESSION['Role'])) {
        header("HTTP/1.1 403 Forbidden");
        exit("Bu sayfaya erişim yetkiniz yok.");
    }

    if (is_string($izinliRoller)) {
        $izinliRoller = [$izinliRoller];
    }

    if (!in_array($_SESSION['Role'], $izinliRoller)) {
        header("HTTP/1.1 403 Forbidden");
        exit("Bu sayfaya erişim yetkiniz yok.");
    }
}
