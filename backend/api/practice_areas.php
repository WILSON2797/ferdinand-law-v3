<?php
require_once __DIR__ . '/../config.php';

$method = $_SERVER['REQUEST_METHOD'];
$db     = getDB();
$path   = getCleanPath();
$id     = getIdFromPath($path);

if ($method === 'GET') {
    if ($id) {
        $stmt = $db->prepare("SELECT * FROM practice_areas WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $item = $stmt->get_result()->fetch_assoc();
        respond($item ?: ['error' => 'Not found']);
    }
    $where  = isset($_GET['all']) ? '' : 'WHERE is_active = 1';
    $result = $db->query("SELECT * FROM practice_areas $where ORDER BY sort_order ASC, id ASC");
    $items  = [];
    while ($row = $result->fetch_assoc()) $items[] = $row;
    respond($items);
}

if ($method === 'POST') {
    requireAuth();
    $body  = json_decode(file_get_contents('php://input'), true) ?? [];
    $stmt  = $db->prepare("INSERT INTO practice_areas (icon, title, description, sort_order) VALUES (?, ?, ?, ?)");
    $icon  = $body['icon'] ?? '';
    $title = $body['title'] ?? '';
    $desc  = $body['description'] ?? '';
    $sort  = (int)($body['sort_order'] ?? 0);
    $stmt->bind_param('sssi', $icon, $title, $desc, $sort);
    $stmt->execute();
    respond(['message' => 'Created', 'id' => $db->insert_id], 201);
}

if ($method === 'PUT' && $id) {
    requireAuth();
    $body   = json_decode(file_get_contents('php://input'), true) ?? [];
    $stmt   = $db->prepare("UPDATE practice_areas SET icon=?, title=?, description=?, sort_order=?, is_active=? WHERE id=?");
    $icon   = $body['icon'] ?? '';
    $title  = $body['title'] ?? '';
    $desc   = $body['description'] ?? '';
    $sort   = (int)($body['sort_order'] ?? 0);
    $active = (int)($body['is_active'] ?? 1);
    $stmt->bind_param('sssiii', $icon, $title, $desc, $sort, $active, $id);
    $stmt->execute();
    respond(['message' => 'Updated']);
}

if ($method === 'DELETE' && $id) {
    requireAuth();
    $stmt = $db->prepare("DELETE FROM practice_areas WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    respond(['message' => 'Deleted']);
}

respondError('Method not allowed or missing ID', 405);
