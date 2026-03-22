<?php
// ─── Database ─────────────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // ← Ganti sesuai hosting
define('DB_PASS', '');          // ← Ganti sesuai hosting
define('DB_NAME', 'ferdinand_law');

// ─── JWT ──────────────────────────────────────────────────
define('JWT_SECRET', 'fh_law_secret_2024_ganti_di_production');

// ─── Upload ───────────────────────────────────────────────
define('UPLOAD_PATH', __DIR__ . '/uploads/');
define('UPLOAD_URL', '/backend/uploads/');

// ─── CORS + Headers ───────────────────────────────────────
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ─── Helper: Clean Path ───────────────────────────────────
// Mendukung 2 mode:
//   1) api.php?route=/auth/login  → $_SERVER['_CLEAN_PATH'] sudah diset oleh api.php
//   2) mod_rewrite → parse dari REQUEST_URI
function getCleanPath(): string {
    // Mode 1: sudah diset oleh api.php
    if (!empty($_SERVER['_CLEAN_PATH'])) {
        return $_SERVER['_CLEAN_PATH'];
    }
    // Mode 2: parse dari REQUEST_URI, strip semua prefix
    $raw  = $_SERVER['REQUEST_URI'] ?? '/';
    $path = parse_url($raw, PHP_URL_PATH) ?: '/';
    $path = preg_replace('#^(.*?)/backend/api(\.php)?#', '', $path);
    $path = preg_replace('#^/api(\.php)?#', '', $path);
    $path = '/' . ltrim($path, '/');
    return rtrim($path, '/') ?: '/';
}

// ─── Helper: ID dari path ─────────────────────────────────
// /resource/123 → 123, /resource → null
function getIdFromPath(string $path): ?int {
    $last = basename($path);
    return is_numeric($last) ? (int)$last : null;
}

// ─── Database ─────────────────────────────────────────────
function getDB(): mysqli {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(['error' => 'DB Error: ' . $conn->connect_error]);
        exit();
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

// ─── Response ─────────────────────────────────────────────
function respond(mixed $data, int $code = 200): never {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit();
}

function respondError(string $msg, int $code = 400): never {
    http_response_code($code);
    echo json_encode(['error' => $msg]);
    exit();
}

// ─── JWT ──────────────────────────────────────────────────
function generateToken(int $userId): string {
    $b64 = fn($v) => rtrim(strtr(base64_encode($v), '+/', '-_'), '=');
    $h   = $b64(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $p   = $b64(json_encode(['sub' => $userId, 'iat' => time(), 'exp' => time() + 86400]));
    $s   = $b64(hash_hmac('sha256', "$h.$p", JWT_SECRET, true));
    return "$h.$p.$s";
}

function verifyToken(string $token): array|false {
    $parts = explode('.', $token);
    if (count($parts) !== 3) return false;
    [$h, $p, $s] = $parts;
    $b64   = fn($v) => rtrim(strtr(base64_encode($v), '+/', '-_'), '=');
    $valid = $b64(hash_hmac('sha256', "$h.$p", JWT_SECRET, true));
    if (!hash_equals($valid, $s)) return false;
    $data  = json_decode(base64_decode(strtr($p, '-_', '+/')), true);
    if (!$data || ($data['exp'] ?? 0) < time()) return false;
    return $data;
}

function requireAuth(): array {
    $all  = getallheaders();
    $auth = $all['Authorization'] ?? $all['authorization'] ?? '';
    if (!str_starts_with($auth, 'Bearer ')) respondError('Unauthorized', 401);
    $data = verifyToken(substr($auth, 7));
    if (!$data) respondError('Token invalid atau expired', 401);
    return $data;
}
