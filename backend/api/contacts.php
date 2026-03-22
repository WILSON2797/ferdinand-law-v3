<?php
require_once __DIR__ . '/../config.php';

$method = $_SERVER['REQUEST_METHOD'];
$db     = getDB();
$path   = getCleanPath();
$id     = getIdFromPath($path);

if ($method === 'GET') {
    requireAuth();
    $result = $db->query("SELECT * FROM contacts ORDER BY created_at DESC");
    $items  = [];
    while ($row = $result->fetch_assoc()) $items[] = $row;
    respond($items);
}

if ($method === 'POST') {
    $body    = json_decode(file_get_contents('php://input'), true) ?? [];
    $name    = trim($body['name'] ?? '');
    $email   = trim($body['email'] ?? '');
    $phone   = trim($body['phone'] ?? '');
    $subject = trim($body['subject'] ?? '');
    $message = trim($body['message'] ?? '');
    if (!$name || !$email || !$message) respondError('Nama, email, dan pesan wajib diisi');
    $stmt = $db->prepare("INSERT INTO contacts (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sssss', $name, $email, $phone, $subject, $message);
    $stmt->execute();
    respond(['message' => 'Pesan berhasil dikirim'], 201);
}

if ($method === 'PUT' && $id) {
    requireAuth();
    $stmt = $db->prepare("UPDATE contacts SET is_read = 1 WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    respond(['message' => 'Marked as read']);
}

if ($method === 'DELETE' && $id) {
    requireAuth();
    $stmt = $db->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    respond(['message' => 'Deleted']);
}

respondError('Method not allowed', 405);
