<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$ad = isset($_SESSION['Ad']) ? htmlspecialchars($_SESSION['Ad']) : '';
$soyad = isset($_SESSION['Soyad']) ? htmlspecialchars($_SESSION['Soyad']) : '';
?>

<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Kullanıcı Bilgisi -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    <?php echo $ad . ' ' . $soyad; ?>
                </span>
                <img class="img-profile rounded-circle" src="img/undraw_profile.svg" alt="Profil Resmi">
            </a>
            <!-- Dropdown - Kullanıcı Bilgisi -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="profil.php">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profilim
                </a>
                <a class="dropdown-item" href="ayarlar.php">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Ayarlar
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Çıkış Yap
                </a>
            </div>
        </li>
    </ul>
</nav>
