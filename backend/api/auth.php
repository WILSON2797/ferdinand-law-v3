<?php
require_once __DIR__ . '/../config.php';

$path   = getCleanPath(); // contoh: /auth/login atau /login
$method = $_SERVER['REQUEST_METHOD'];

// Normalisasi: /auth/login dan /login sama saja
$sub = preg_replace('#^/auth#', '', $path); // /login, /me, /change-password

// POST → Login
if ($method === 'POST' && ($sub === '/login' || $sub === '' || $sub === '/')) {
    $body = json_decode(file_get_contents('php://input'), true) ?? [];
    $user = trim($body['username'] ?? '');
    $pass = trim($body['password'] ?? '');

    if (!$user || !$pass) respondError('Username dan password wajib diisi');

    $db   = getDB();
    $stmt = $db->prepare("SELECT id, username, password, name FROM admins WHERE username = ?");
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();

    if (!$admin || !password_verify($pass, $admin['password'])) {
        respondError('Username atau password salah', 401);
    }

    respond([
        'token' => generateToken($admin['id']),
        'admin' => ['id' => $admin['id'], 'username' => $admin['username'], 'name' => $admin['name']]
    ]);
}

// GET → Cek user login
if ($method === 'GET' && $sub === '/me') {
    $data  = requireAuth();
    $db    = getDB();
    $stmt  = $db->prepare("SELECT id, username, name FROM admins WHERE id = ?");
    $stmt->bind_param('i', $data['sub']);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();
    if (!$admin) respondError('Admin tidak ditemukan', 404);
    respond($admin);
}

// POST → Ganti password
if ($method === 'POST' && $sub === '/change-password') {
    $auth = requireAuth();
    $body = json_decode(file_get_contents('php://input'), true) ?? [];
    $cur  = $body['current_password'] ?? '';
    $new  = $body['new_password'] ?? '';
    if (!$cur || !$new) respondError('Semua field wajib diisi');
    if (strlen($new) < 6) respondError('Password baru minimal 6 karakter');

    $db   = getDB();
    $stmt = $db->prepare("SELECT password FROM admins WHERE id = ?");
    $stmt->bind_param('i', $auth['sub']);
    $stmt->execute();
    $row  = $stmt->get_result()->fetch_assoc();
    if (!$row || !password_verify($cur, $row['password'])) respondError('Password saat ini salah');

    $hash = password_hash($new, PASSWORD_BCRYPT);
    $stmt = $db->prepare("UPDATE admins SET password = ? WHERE id = ?");
    $stmt->bind_param('si', $hash, $auth['sub']);
    $stmt->execute();
    respond(['message' => 'Password berhasil diubah']);
}

respond(['error' => 'Auth endpoint tidak ditemukan', 'path' => $path, 'sub' => $sub, 'method' => $method], 404);
