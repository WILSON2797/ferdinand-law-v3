<?php
// Deteksi apakah halaman ini bukan index (misal: articles.php)
// Jika iya, prefix semua anchor dengan path ke halaman utama
$currentScript = basename($_SERVER['SCRIPT_NAME']); // "articles.php" atau "index.php"
$isHome        = ($currentScript === 'index.php');
$baseDir       = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');  // "/ferdinand-law-v3" atau ""
$base          = $isHome ? '' : $baseDir . '/';
?>
<!-- ====== NAVBAR ====== -->
<nav id="navbar">
  <a href="<?= $baseDir ?>/" class="nav-logo">
    <span class="nav-logo-main">Ferdinand Hutahaean & Co</span>
    <span class="nav-logo-sub">Advocates & Legal Consultants</span>
  </a>
  <ul class="nav-links">
    <li><a href="<?= $base ?>#about">Tentang</a></li>
    <li><a href="<?= $base ?>#practice">Layanan</a></li>
    <li><a href="<?= $base ?>#cases">Kasus</a></li>
    <li><a href="<?= $base ?>#team">Tim</a></li>
    <li><a href="<?= $base ?>#testimonials">Klien</a></li>
    <li><a href="<?= $base ?>#news">Berita</a></li>
    <li><a href="<?= $base ?>#contact" class="nav-cta">Konsultasi</a></li>
  </ul>
  <button class="hamburger" onclick="toggleMobileMenu()">
    <span></span><span></span><span></span>
  </button>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
  <button class="mobile-close" onclick="toggleMobileMenu()">✕</button>
  <a href="<?= $base ?>#about"        onclick="toggleMobileMenu()">Tentang</a>
  <a href="<?= $base ?>#practice"     onclick="toggleMobileMenu()">Layanan</a>
  <a href="<?= $base ?>#cases"        onclick="toggleMobileMenu()">Kasus</a>
  <a href="<?= $base ?>#team"         onclick="toggleMobileMenu()">Tim</a>
  <a href="<?= $base ?>#testimonials" onclick="toggleMobileMenu()">Klien</a>
  <a href="<?= $base ?>#news"         onclick="toggleMobileMenu()">Berita</a>
  <a href="<?= $base ?>#contact"      onclick="toggleMobileMenu()">Konsultasi</a>
</div>