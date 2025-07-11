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
 * RoleID'den RoleName çekmek için fonksiyon
 * Kullanım:
 * $roleName = getRoleName($conn, 1);
 */
function getRoleName($conn, $roleID) {
    $sql = "SELECT RoleName FROM Roller WHERE RoleID = $roleID LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['RoleName'];
    }
    return null;
}

/**
 * RoleName'den RoleID çekmek için fonksiyon
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

/**
 * Kullanıcıya ait tüm yetkileri çekme fonksiyonu
 * Kullanım:
 * $yetkiler = getUserPermissions($conn, $kullaniciID);
 */
function getUserPermissions($conn, $kullaniciID) {
    $sql = "SELECT y.PermissionCode
            FROM KullaniciRolleri kr
            JOIN RolYetkileri ry ON kr.RoleID = ry.RoleID
            JOIN Yetkiler y ON ry.YetkiID = y.YetkiID
            WHERE kr.KullaniciID = $kullaniciID";
    $result = $conn->query($sql);
    $permissions = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $permissions[] = $row['PermissionCode'];
        }
    }
    return $permissions;
}
?>
