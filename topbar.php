<!-- topbar.php -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Bildirimler -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 1.5rem; position: relative;">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Bildirim sayısı -->
                <span class="badge badge-danger badge-counter" 
                      style="font-size: 1.2rem; top: -8px; right: -6px; position: absolute; padding: 0 6px;">
                    3+
                </span>
            </a>
            <!-- Dropdown - Bildirimler -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="alertsDropdown" style="min-width: 300px;">
                <h6 class="dropdown-header">
                    Bildirimler Merkezi
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="mr-3">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-file-alt text-white"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">Temmuz 3, 2025</div>
                        <span class="font-weight-bold">Yeni rapor yüklendi!</span>
                    </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Tüm bildirimleri gör</a>
            </div>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Kullanıcı Bilgisi -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    <?php echo htmlspecialchars($_SESSION['Ad']) . ' ' . htmlspecialchars($_SESSION['Soyad']); ?>
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
