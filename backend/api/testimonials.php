<?php
require_once __DIR__ . '/../config.php';

$method = $_SERVER['REQUEST_METHOD'];
$db     = getDB();
$path   = getCleanPath();
$id     = getIdFromPath($path);

if ($method === 'GET') {
    if ($id) {
        $stmt = $db->prepare("SELECT * FROM testimonials WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        respond($stmt->get_result()->fetch_assoc() ?: []);
    }
    $where  = isset($_GET['all']) ? '' : 'WHERE is_active = 1';
    $result = $db->query("SELECT * FROM testimonials $where ORDER BY created_at DESC");
    $items  = [];
    while ($row = $result->fetch_assoc()) $items[] = $row;
    respond($items);
}

// POST — publik (tanpa auth), langsung aktif
if ($method === 'POST') {
    $body    = json_decode(file_get_contents('php://input'), true) ?? [];
    $name    = trim($body['client_name']    ?? '');
    $company = trim($body['client_company'] ?? '');
    $content = trim($body['content']        ?? '');
    $rating  = max(1, min(5, (int)($body['rating'] ?? 5)));

    if (!$name || !$content) {
        respondError('Nama dan isi testimoni wajib diisi', 422);
    }

    $active = 1; // langsung tampil
    $stmt   = $db->prepare("INSERT INTO testimonials (client_name, client_company, content, rating, is_active) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sssii', $name, $company, $content, $rating, $active);
    $stmt->execute();
    respond(['message' => 'Created', 'id' => $db->insert_id], 201);
}

// PUT & DELETE — tetap butuh auth (admin only)
if ($method === 'PUT' && $id) {
    requireAuth();
    $body    = json_decode(file_get_contents('php://input'), true) ?? [];
    $name    = $body['client_name']    ?? '';
    $company = $body['client_company'] ?? '';
    $content = $body['content']        ?? '';
    $rating  = (int)($body['rating']    ?? 5);
    $active  = (int)($body['is_active'] ?? 1);
    $stmt    = $db->prepare("UPDATE testimonials SET client_name=?, client_company=?, content=?, rating=?, is_active=? WHERE id=?");
    $stmt->bind_param('sssiii', $name, $company, $content, $rating, $active, $id);
    $stmt->execute();
    respond(['message' => 'Updated']);
}

if ($method === 'DELETE' && $id) {
    requireAuth();
    $stmt = $db->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    respond(['message' => 'Deleted']);
}

respondError('Method not allowed or missing ID', 405);