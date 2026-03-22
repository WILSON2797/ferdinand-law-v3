<?php
require_once __DIR__ . '/../config.php';

$method = $_SERVER['REQUEST_METHOD'];
$db     = getDB();

if ($method === 'GET') {
    $result   = $db->query("SELECT `key`, value FROM site_settings ORDER BY `key`");
    $settings = [];
    while ($row = $result->fetch_assoc()) $settings[$row['key']] = $row['value'];
    respond($settings);
}

if ($method === 'PUT' || $method === 'POST') {
    requireAuth();
    $body = json_decode(file_get_contents('php://input'), true);
    if (!is_array($body)) respondError('Invalid JSON body');

    $stmt = $db->prepare("INSERT INTO site_settings (`key`, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = ?");
    $count = 0;
    foreach ($body as $key => $value) {
        $v = (string)($value ?? '');
        $stmt->bind_param('sss', $key, $v, $v);
        if ($stmt->execute()) $count++;
    }
    respond(['message' => "Settings updated ($count items)"]);
}

respondError('Method not allowed', 405);
