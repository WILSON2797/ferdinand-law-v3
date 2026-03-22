<?php
require_once __DIR__ . '/../config.php';

$method = $_SERVER['REQUEST_METHOD'];
$db     = getDB();
$path   = getCleanPath();
$id     = getIdFromPath($path);

if ($method === 'GET') {
    if ($id) {
        $stmt = $db->prepare("SELECT * FROM cases WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        respond($stmt->get_result()->fetch_assoc() ?: []);
    }
    $where  = isset($_GET['all']) ? '' : 'WHERE is_active = 1';
    $result = $db->query("SELECT * FROM cases $where ORDER BY year DESC, created_at DESC");
    $items  = [];
    while ($row = $result->fetch_assoc()) $items[] = $row;
    respond($items);
}

if ($method === 'POST') {
    requireAuth();
    $body     = json_decode(file_get_contents('php://input'), true) ?? [];
    $title    = $body['title'] ?? '';
    $cat      = $body['category'] ?? '';
    $desc     = $body['description'] ?? '';
    $outcome  = $body['outcome'] ?? '';
    $year     = (int)($body['year'] ?? date('Y'));
    $featured = (int)($body['is_featured'] ?? 0);
    $stmt = $db->prepare("INSERT INTO cases (title, category, description, outcome, year, is_featured) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssii', $title, $cat, $desc, $outcome, $year, $featured);
    $stmt->execute();
    respond(['message' => 'Created', 'id' => $db->insert_id], 201);
}

if ($method === 'PUT' && $id) {
    requireAuth();
    $body     = json_decode(file_get_contents('php://input'), true) ?? [];
    $title    = $body['title'] ?? '';
    $cat      = $body['category'] ?? '';
    $desc     = $body['description'] ?? '';
    $outcome  = $body['outcome'] ?? '';
    $year     = (int)($body['year'] ?? date('Y'));
    $featured = (int)($body['is_featured'] ?? 0);
    $active   = (int)($body['is_active'] ?? 1);
    $stmt = $db->prepare("UPDATE cases SET title=?, category=?, description=?, outcome=?, year=?, is_featured=?, is_active=? WHERE id=?");
    $stmt->bind_param('ssssiiii', $title, $cat, $desc, $outcome, $year, $featured, $active, $id);
    $stmt->execute();
    respond(['message' => 'Updated']);
}

if ($method === 'DELETE' && $id) {
    requireAuth();
    $stmt = $db->prepare("DELETE FROM cases WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    respond(['message' => 'Deleted']);
}

respondError('Method not allowed', 405);
