<?php

$role = $_SESSION['Role'] ?? ''; // Rol bilgisini session’dan al
?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="anasayfa.php">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fas fa-cogs"></i>
    </div>
    <div class="sidebar-brand-text mx-3">Zimmet Takip</div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0" />

  <!-- Nav Item - Dashboard -->
  <li class="nav-item">
    <a class="nav-link" href="anasayfa.php">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Anasayfa</span>
    </a>
  </li>

  <hr class="sidebar-divider" />

  <!-- Heading -->
  <div class="sidebar-heading">İşlemler</div>

  <!-- Personel -->
  <li class="nav-item">
    <a class="nav-link" href="personel.php">
      <i class="fas fa-fw fa-user"></i>
      <span>Personel İşlemleri</span>
    </a>
  </li>

  <!-- Eşya -->
  <li class="nav-item">
    <a class="nav-link" href="esya.php">
      <i class="fas fa-fw fa-box"></i>
      <span>Eşya İşlemleri</span>
    </a>
  </li>

  <!-- Zimmet -->
  <li class="nav-item">
    <a class="nav-link" href="zimmet.php">
      <i class="fas fa-fw fa-clipboard-list"></i>
      <span>Zimmet İşlemleri</span>
    </a>
  </li>

  <?php if ($role != 'user'): // Eğer user değilse diğer menüler görünür ?>

  <hr class="sidebar-divider d-none d-md-block" />
  <div class="sidebar-heading">Stok İşlemleri</div>

  <li class="nav-item">
    <a class="nav-link" href="stoklar.php">
      <i class="fas fa-boxes"></i>
      <span>Stoklar</span>
    </a>
  </li>

  <hr class="sidebar-divider d-none d-md-block" />
  <div class="sidebar-heading">Yönetim</div>

  <li class="nav-item">
    <a class="nav-link" href="kullanicilar.php">
      <i class="fas fa-user"></i>
      <span>Kullanıcılar</span>
    </a>
  </li>


  <?php endif; ?>

  <hr class="sidebar-divider d-none d-md-block" />

  <!-- Logout -->
  <li class="nav-item">
    <a class="nav-link" href="logout.php">
      <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
      <span>Çıkış Yap</span>
    </a>
  </li>

</ul>
