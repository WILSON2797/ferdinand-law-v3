<?php
require_once __DIR__ . '/../config.php';

$method = $_SERVER['REQUEST_METHOD'];
$db     = getDB();
$path   = getCleanPath();
$id     = getIdFromPath($path);

$lastSeg = basename(rtrim($path, '/'));
$slug    = (!$id && $lastSeg !== 'news') ? $lastSeg : null;

if ($method === 'GET') {
    if ($id) {
        $stmt = $db->prepare("SELECT * FROM news_articles WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        respond($stmt->get_result()->fetch_assoc() ?: []);
    }
    if ($slug) {
        $stmt = $db->prepare("SELECT * FROM news_articles WHERE slug = ? AND is_published = 1");
        $stmt->bind_param('s', $slug);
        $stmt->execute();
        respond($stmt->get_result()->fetch_assoc() ?: []);
    }
    $limit  = min((int)($_GET['limit'] ?? 10), 50);
    $offset = (int)($_GET['offset'] ?? 0);
    $where  = isset($_GET['all']) ? '' : 'WHERE is_published = 1';
    $result = $db->query("SELECT id, title, slug, excerpt, image, author, published_at, created_at, is_published FROM news_articles $where ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
    $items  = [];
    while ($row = $result->fetch_assoc()) $items[] = $row;
    $total  = $db->query("SELECT COUNT(*) AS c FROM news_articles $where")->fetch_assoc()['c'];
    respond(['items' => $items, 'total' => (int)$total]);
}

function makeSlug(string $title, mysqli $db): string {
    $s    = mb_strtolower(trim($title));
    $s    = preg_replace('/[^a-z0-9\s\-]/u', '', $s);
    $s    = preg_replace('/[\s\-]+/', '-', $s);
    $base = substr($s, 0, 250) ?: 'article';
    $slug = $base;
    $i    = 1;
    while (true) {
        $check = $db->real_escape_string($slug);
        if ($db->query("SELECT id FROM news_articles WHERE slug='$check'")->num_rows === 0) break;
        $slug = $base . '-' . $i++;
    }
    return $slug;
}

if ($method === 'POST') {
    requireAuth();
    $body      = json_decode(file_get_contents('php://input'), true) ?? [];
    $title     = $body['title'] ?? '';
    $slug      = makeSlug($title, $db);
    $excerpt   = $body['excerpt'] ?? '';
    $content   = $body['content'] ?? '';
    $image     = $body['image'] ?? '';
    $author    = $body['author'] ?? 'Admin';
    $published = (int)($body['is_published'] ?? 0);
    $pubDate   = $published ? date('Y-m-d H:i:s') : null;

    $stmt = $db->prepare("INSERT INTO news_articles (title, slug, excerpt, content, image, author, is_published, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssssss', $title, $slug, $excerpt, $content, $image, $author, $published, $pubDate);
    $stmt->execute();
    respond(['message' => 'Created', 'id' => $db->insert_id, 'slug' => $slug], 201);
}

if ($method === 'PUT' && $id) {
    requireAuth();
    $body      = json_decode(file_get_contents('php://input'), true) ?? [];
    $title     = $body['title'] ?? '';
    $excerpt   = $body['excerpt'] ?? '';
    $content   = $body['content'] ?? '';
    $image     = $body['image'] ?? '';
    $author    = $body['author'] ?? '';
    $published = (int)($body['is_published'] ?? 0);
    $pubDate   = $published ? date('Y-m-d H:i:s') : null;

    $stmt = $db->prepare("UPDATE news_articles SET title=?, excerpt=?, content=?, image=?, author=?, is_published=?, published_at=? WHERE id=?");
    $stmt->bind_param('sssssisi', $title, $excerpt, $content, $image, $author, $published, $pubDate, $id);
    $stmt->execute();
    respond(['message' => 'Updated']);
}

if ($method === 'DELETE' && $id) {
    requireAuth();
    $stmt = $db->prepare("DELETE FROM news_articles WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    respond(['message' => 'Deleted']);
}

respondError('Method not allowed', 405);
