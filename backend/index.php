<?php
require_once __DIR__ . '/config.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Strip semua kemungkinan prefix agar fleksibel
$path = preg_replace('#^.*?/backend/api#', '', $path);  // /backend/api/xxx → /xxx
$path = preg_replace('#^/api#', '', $path);              // /api/xxx → /xxx

// Hapus trailing slash
$path = rtrim($path, '/');
if ($path === '') $path = '/';

$method = $_SERVER['REQUEST_METHOD'];

// Route mapping
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
];

foreach ($routes as $prefix => $handler) {
    if ($path === $prefix || str_starts_with($path, $prefix . '/') || str_starts_with($path, $prefix . '?')) {
        require_once __DIR__ . "/api/{$handler}.php";
        exit();
    }
}

respond(['error' => 'Route not found', 'path' => $path, 'original' => $_SERVER['REQUEST_URI']], 404);
