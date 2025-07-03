<?php
session_start();
if (!isset($_SESSION['KullaniciID'])) {
    header("Location: login.php");
    exit();
}
?>

<h1>Hoşgeldiniz, <?php echo $_SESSION['Ad'] . " " . $_SESSION['Soyad']; ?></h1>
<p>Rolünüz: <?php echo $_SESSION['Role']; ?></p>

<nav>
    <ul>
        <li><a href="personel.php">Personel İşlemleri</a></li>
        <li><a href="esya.php">Eşya İşlemleri</a></li>
        <li><a href="zimmet.php">Zimmet İşlemleri</a></li>
        <li><a href="logout.php">Çıkış Yap</a></li>
    </ul>
</nav>
