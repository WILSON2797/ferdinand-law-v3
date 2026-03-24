<?php
/* ============================================================
   backend/api/gallery.php
   CRUD Gallery — Ferdinand Hutahaean & Co
   ============================================================ */

require_once __DIR__ . '/../config.php';

$method = $_SERVER['REQUEST_METHOD'];
$id     = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch ($method) {

  /* ── GET: ambil semua / satu ── */
  case 'GET':
    if ($id) {
      $stmt = $pdo->prepare("SELECT * FROM gallery WHERE id = ?");
      $stmt->execute([$id]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if (!$row) { http_response_code(404); echo json_encode(['error' => 'Not found']); exit; }
      echo json_encode($row);
    } else {
      $stmt = $pdo->query("SELECT * FROM gallery ORDER BY date DESC, id DESC");
      echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    break;

  /* ── POST: tambah (admin) ── */
  case 'POST':
    requireAuth();
    $data = json_decode(file_get_contents('php://input'), true);
    if (empty($data['image_url'])) {
      http_response_code(422);
      echo json_encode(['error' => 'image_url wajib diisi']);
      exit;
    }
    $stmt = $pdo->prepare(
      "INSERT INTO gallery (image_url, description, date, created_at)
       VALUES (:image_url, :description, :date, NOW())"
    );
    $stmt->execute([
      ':image_url'   => trim($data['image_url']),
      ':description' => trim($data['description'] ?? ''),
      ':date'        => $data['date'] ?? date('Y-m-d'),
    ]);
    http_response_code(201);
    echo json_encode(['id' => $pdo->lastInsertId(), 'message' => 'Berhasil ditambahkan']);
    break;

  /* ── PUT: update (admin) ── */
  case 'PUT':
    requireAuth();
    if (!$id) { http_response_code(400); echo json_encode(['error' => 'ID diperlukan']); exit; }
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare(
      "UPDATE gallery
       SET image_url = :image_url, description = :description, date = :date
       WHERE id = :id"
    );
    $stmt->execute([
      ':image_url'   => trim($data['image_url'] ?? ''),
      ':description' => trim($data['description'] ?? ''),
      ':date'        => $data['date'] ?? date('Y-m-d'),
      ':id'          => $id,
    ]);
    echo json_encode(['message' => 'Berhasil diperbarui']);
    break;

  /* ── DELETE (admin) ── */
  case 'DELETE':
    requireAuth();
    if (!$id) { http_response_code(400); echo json_encode(['error' => 'ID diperlukan']); exit; }
    $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['message' => 'Berhasil dihapus']);
    break;

  default:
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}