<?php
require_once __DIR__ . '/../config.php';

$method = $_SERVER['REQUEST_METHOD'];
$db     = getDB();
$path   = getCleanPath();
$id     = getIdFromPath($path);

if ($method === 'GET') {
    if ($id) {
        $stmt = $db->prepare("SELECT * FROM team_members WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        respond($stmt->get_result()->fetch_assoc() ?: ['error' => 'Not found']);
    }
    $where  = isset($_GET['all']) ? '' : 'WHERE is_active = 1';
    $result = $db->query("SELECT * FROM team_members $where ORDER BY sort_order ASC, id ASC");
    $items  = [];
    while ($row = $result->fetch_assoc()) $items[] = $row;
    respond($items);
}

if ($method === 'POST') {
    requireAuth();
    $body  = json_decode(file_get_contents('php://input'), true) ?? [];
    $stmt  = $db->prepare("INSERT INTO team_members (name, position, education, photo, sort_order) VALUES (?, ?, ?, ?, ?)");
    $name  = $body['name'] ?? '';
    $pos   = $body['position'] ?? '';
    $edu   = $body['education'] ?? '';
    $photo = $body['photo'] ?? '';
    $sort  = (int)($body['sort_order'] ?? 0);
    $stmt->bind_param('ssssi', $name, $pos, $edu, $photo, $sort);
    $stmt->execute();
    respond(['message' => 'Created', 'id' => $db->insert_id], 201);
}

if ($method === 'PUT' && $id) {
    requireAuth();
    $body   = json_decode(file_get_contents('php://input'), true) ?? [];
    $stmt   = $db->prepare("UPDATE team_members SET name=?, position=?, education=?, photo=?, sort_order=?, is_active=? WHERE id=?");
    $name   = $body['name'] ?? '';
    $pos    = $body['position'] ?? '';
    $edu    = $body['education'] ?? '';
    $photo  = $body['photo'] ?? '';
    $sort   = (int)($body['sort_order'] ?? 0);
    $active = (int)($body['is_active'] ?? 1);
    $stmt->bind_param('ssssiii', $name, $pos, $edu, $photo, $sort, $active, $id);
    $stmt->execute();
    respond(['message' => 'Updated']);
}

if ($method === 'DELETE' && $id) {
    requireAuth();
    $stmt = $db->prepare("DELETE FROM team_members WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    respond(['message' => 'Deleted']);
}

respondError('Method not allowed or missing ID', 405);