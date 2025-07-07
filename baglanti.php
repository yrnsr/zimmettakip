<?php
// Veritabanı bağlantı ayarları
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zimmetdb";

// Veritabanı bağlantısı oluştur
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantı hatası kontrolü
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

/**
 * Sayfa erişim kontrolü fonksiyonu
 * Kullanım:
 * if (!sayfa_erisim_kontrol($conn, $_SESSION['RoleID'], 'personel.php')) { ... }
 */
function sayfa_erisim_kontrol($conn, $roleID, $sayfaAdi) {
    // Güvenlik için escape
    $sayfaAdi = $conn->real_escape_string($sayfaAdi);

    // Erisimler tablosundan kontrol et
    $sql = "SELECT * FROM Erisimler WHERE RoleID = $roleID AND Sayfa = '$sayfaAdi'";
    $result = $conn->query($sql);

    return ($result && $result->num_rows > 0);
}

/**
 * RoleID'yi RoleName'den çekmek için yardımcı fonksiyon (opsiyonel)
 * Kullanım:
 * $roleID = getRoleID($conn, 'admin');
 */
function getRoleID($conn, $roleName) {
    $roleName = $conn->real_escape_string($roleName);
    $sql = "SELECT RoleID FROM Roller WHERE RoleName = '$roleName' LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['RoleID'];
    }
    return null;
}
?>
