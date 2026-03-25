<?php
/**
 * api.php — Single entry point TANPA mod_rewrite
 * Dipanggil dengan: /backend/api.php?route=/auth/login
 */
require_once __DIR__ . '/config.php';

// Ambil route dari query string
// PERBAIKAN: Jangan gunakan strtok() karena akan memotong query string
// sehingga parameter seperti ?limit=4 hilang dari $_GET.
// Cukup ambil bagian path-nya saja (sebelum '?'), sedangkan
// query string lain (?limit=4, ?all=1, dst) sudah otomatis
// diparse oleh PHP ke dalam $_GET karena dikirim sebagai bagian URL.
$rawRoute = $_GET['route'] ?? '/';
$route    = '/' . ltrim($rawRoute, '/');

// Hapus hanya bagian path (sebelum '?'), query string dibiarkan di $_GET
if (($qpos = strpos($route, '?')) !== false) {
    $route = substr($route, 0, $qpos);
}
$route = rtrim($route, '/') ?: '/';

// Expose ke semua handler melalui SERVER superglobal
$_SERVER['_CLEAN_PATH'] = $route;
$_SERVER['REQUEST_URI'] = $route;

$method = $_SERVER['REQUEST_METHOD'];

// Tabel routing: prefix path → file handler
$routes = [
    '/auth'           => 'auth',
    '/settings'       => 'settings',
    '/practice-areas' => 'practice_areas',
    '/team'           => 'team',
    '/testimonials'   => 'testimonials',
    '/cases'          => 'cases',
    '/news'           => 'news',
    '/contacts'       => 'contacts',
    '/upload'         => 'upload',
    '/gallery'        => 'gallery',
];

foreach ($routes as $prefix => $handler) {
    if ($route === $prefix || str_starts_with($route, $prefix . '/')) {
        require_once __DIR__ . "/api/{$handler}.php";
        exit();
    }
}

respond([
    'error'  => 'Route not found',
    'route'  => $route,
    'hint'   => 'Available: /auth/login, /settings, /practice-areas, /team, /testimonials, /cases, /news, /contacts'
], 404);
