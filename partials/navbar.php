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
    <li><a href="<?= $base ?>#about">About</a></li>
    <li><a href="<?= $base ?>#practice">Legal Service</a></li>
    <li><a href="<?= $base ?>#cases">Cases</a></li>
    <li><a href="<?= $base ?>#team">Team</a></li>
    <li><a href="<?= $base ?>#testimonials">Client</a></li>
    <li><a href="<?= $base ?>#news">Article</a></li>
    <li><a href="<?= $base ?>#gallery">Gallery</a></li>
    <li><a href="<?= $base ?>#contact" class="nav-cta">Contact</a></li>
  </ul>
  <button class="hamburger" onclick="toggleMobileMenu()">
    <span></span><span></span><span></span>
  </button>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
  <button class="mobile-close" onclick="toggleMobileMenu()">✕</button>
  <a href="<?= $base ?>#about"        onclick="toggleMobileMenu()">About</a>
  <a href="<?= $base ?>#practice"     onclick="toggleMobileMenu()">Legal Service</a>
  <a href="<?= $base ?>#cases"        onclick="toggleMobileMenu()">Cases</a>
  <a href="<?= $base ?>#team"         onclick="toggleMobileMenu()">Team</a>
  <a href="<?= $base ?>#testimonials" onclick="toggleMobileMenu()">Client</a>
  <a href="<?= $base ?>#news"         onclick="toggleMobileMenu()">Article</a>
  <a href="<?= $base ?>#gallery"         onclick="toggleMobileMenu()">Gallery</a>
  <a href="<?= $base ?>#contact"      onclick="toggleMobileMenu()">Contact</a>
</div>