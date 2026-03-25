<?php
require_once __DIR__ . '/../config.php';

$method = $_SERVER['REQUEST_METHOD'];
$db     = getDB();
$path   = getCleanPath();
$id     = getIdFromPath($path);

if ($method === 'GET') {
    if ($id) {
        $stmt = $db->prepare("SELECT * FROM gallery WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        respond($row ?: ['error' => 'Not found']);
    }
    $result = $db->query("SELECT * FROM gallery ORDER BY date DESC, id DESC");
    $items  = [];
    while ($row = $result->fetch_assoc()) $items[] = $row;
    respond($items);
}

if ($method === 'POST') {
    requireAuth();
    $body  = json_decode(file_get_contents('php://input'), true) ?? [];
    if (empty($body['image_url'])) respondError('image_url wajib diisi', 422);
    $stmt  = $db->prepare("INSERT INTO gallery (image_url, description, date, created_at) VALUES (?, ?, ?, NOW())");
    $img   = trim($body['image_url']);
    $desc  = trim($body['description'] ?? '');
    $date  = $body['date'] ?? date('Y-m-d');
    $stmt->bind_param('sss', $img, $desc, $date);
    $stmt->execute();
    respond(['message' => 'Berhasil ditambahkan', 'id' => $db->insert_id], 201);
}

if ($method === 'PUT' && $id) {
    requireAuth();
    $body  = json_decode(file_get_contents('php://input'), true) ?? [];
    $stmt  = $db->prepare("UPDATE gallery SET image_url=?, description=?, date=? WHERE id=?");
    $img   = trim($body['image_url'] ?? '');
    $desc  = trim($body['description'] ?? '');
    $date  = $body['date'] ?? date('Y-m-d');
    $stmt->bind_param('sssi', $img, $desc, $date, $id);
    $stmt->execute();
    respond(['message' => 'Berhasil diperbarui']);
}

if ($method === 'DELETE' && $id) {
    requireAuth();
    $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    respond(['message' => 'Berhasil dihapus']);
}

respondError('Method not allowed or missing ID', 405);