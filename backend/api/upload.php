<?php
require_once __DIR__ . '/../config.php';

requireAuth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') respondError('Method not allowed', 405);
if (!isset($_FILES['file'])) respondError('No file uploaded');

$file    = $_FILES['file'];
$allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];

if (!in_array($file['type'], $allowed)) respondError('Tipe file tidak valid. Gunakan JPG, PNG, GIF, atau WEBP.');
if ($file['size'] > 5 * 1024 * 1024) respondError('File terlalu besar. Maksimum 5MB.');
if ($file['error'] !== UPLOAD_ERR_OK) respondError('Upload error: ' . $file['error']);

// Simpan ke /uploads/ di root project (sejajar dengan backend/)
$uploadDir = __DIR__ . '/../../uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

$ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$filename = 'img_' . uniqid() . '.' . $ext;
$dest     = $uploadDir . $filename;

if (!move_uploaded_file($file['tmp_name'], $dest)) respondError('Gagal menyimpan file');

respond(['url' => '/uploads/' . $filename, 'filename' => $filename]);