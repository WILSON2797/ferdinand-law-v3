<?php
require_once __DIR__ . '/../config.php';

// ============================================================
// KONFIGURASI EMAIL ADMIN
// Ganti nilai-nilai di bawah ini sesuai akun Gmail Anda
// ============================================================
define('MAIL_HOST',       'smtp.gmail.com');
define('MAIL_PORT',       587);
define('MAIL_USERNAME',   'lawfirmferdinand@gmail.com');      // Gmail pengirim (akun Gmail Anda)
define('MAIL_PASSWORD',   'lptc lejz khlw kihf');      // Gmail App Password (bukan password biasa!)
define('MAIL_FROM_NAME',  'Ferdinand Hutahaean & Co'); // Nama pengirim
define('MAIL_ADMIN',      'official@lawfirmferdinandnco.my.id');   // Email tujuan notifikasi admin
// ============================================================

/**
 * Kirim email notifikasi ke admin menggunakan PHPMailer (tanpa Composer).
 * Pastikan folder PHPMailer sudah ada di: /path/to/project/lib/PHPMailer/
 *
 * Struktur folder yang dibutuhkan:
 *   lib/
 *   └── PHPMailer/
 *       ├── src/
 *       │   ├── PHPMailer.php
 *       │   ├── SMTP.php
 *       │   └── Exception.php
 */
function sendAdminNotification(array $data): bool
{
    // Path ke library PHPMailer (sesuaikan jika perlu)
    $libPath = __DIR__ . '/../lib/PHPMailer/src/';

    if (!file_exists($libPath . 'PHPMailer.php')) {
        error_log('[Mail] PHPMailer tidak ditemukan di: ' . $libPath);
        return false;
    }

    require_once $libPath . 'Exception.php';
    require_once $libPath . 'PHPMailer.php';
    require_once $libPath . 'SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // -- Pengaturan Server SMTP --
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = MAIL_PORT;
        $mail->CharSet    = 'UTF-8';

        // -- Pengirim & Penerima --
        $mail->setFrom(MAIL_USERNAME, MAIL_FROM_NAME);
        $mail->addAddress(MAIL_ADMIN);

        // -- Reply-To: agar admin bisa langsung balas ke user --
        if (!empty($data['email'])) {
            $mail->addReplyTo($data['email'], $data['name'] ?? 'User');
        }

        // -- Konten Email --
        $mail->isHTML(true);
        $mail->Subject = '📩 Pesan Konsultasi Baru dari ' . htmlspecialchars($data['name'] ?? '-');
        $mail->Body    = buildEmailBody($data);
        $mail->AltBody = buildEmailBodyText($data); // Versi plain text

        $mail->send();
        return true;

    } catch (\Exception $e) {
        error_log('[Mail] Gagal mengirim email: ' . $mail->ErrorInfo);
        return false;
    }
}

/**
 * Template email HTML yang dikirim ke admin.
 */
function buildEmailBody(array $d): string
{
    $name    = htmlspecialchars($d['name']    ?? '-');
    $email   = htmlspecialchars($d['email']   ?? '-');
    $phone   = htmlspecialchars($d['phone']   ?? '-');
    $subject = htmlspecialchars($d['subject'] ?? '-');
    $message = nl2br(htmlspecialchars($d['message'] ?? '-'));
    $time    = date('d M Y, H:i') . ' WIB';

    return <<<HTML
    <!DOCTYPE html>
    <html lang="id">
    <head>
      <meta charset="UTF-8">
      <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: #1a1a2e; color: #c9a84c; padding: 24px 32px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; letter-spacing: 1px; }
        .header p  { margin: 4px 0 0; font-size: 13px; color: rgba(255,255,255,0.6); }
        .body { padding: 28px 32px; }
        .badge { display: inline-block; background: #c9a84c; color: #1a1a2e; font-size: 11px; font-weight: bold; padding: 4px 10px; border-radius: 20px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        td { padding: 10px 12px; font-size: 14px; vertical-align: top; }
        td:first-child { width: 130px; color: #888; font-weight: bold; white-space: nowrap; }
        tr:nth-child(even) td { background: #f9f9f9; }
        .msg-box { background: #f0f0f0; border-left: 4px solid #c9a84c; padding: 14px 16px; margin-top: 20px; border-radius: 4px; font-size: 14px; line-height: 1.7; color: #333; }
        .footer { background: #f4f4f4; text-align: center; padding: 16px; font-size: 12px; color: #aaa; border-top: 1px solid #e0e0e0; }
        .reply-btn { display: inline-block; margin-top: 20px; padding: 10px 24px; background: #c9a84c; color: #1a1a2e; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 13px; }
      </style>
    </head>
    <body>
      <div class="container">
        <div class="header">
          <h1>⚖️ Ferdinand Hutahaean &amp; Co</h1>
          <p>Notifikasi Pesan Konsultasi Baru</p>
        </div>
        <div class="body">
          <span class="badge">PESAN BARU</span>
          <table>
            <tr><td>Nama</td><td><strong>{$name}</strong></td></tr>
            <tr><td>Email</td><td>{$email}</td></tr>
            <tr><td>Telepon</td><td>{$phone}</td></tr>
            <tr><td>Bidang Hukum</td><td>{$subject}</td></tr>
            <tr><td>Waktu</td><td>{$time}</td></tr>
          </table>

          <div class="msg-box">
            <strong>📝 Pesan:</strong><br><br>
            {$message}
          </div>

          <a href="mailto:{$email}" class="reply-btn">✉️ Balas ke {$name}</a>
        </div>
        <div class="footer">
          Email ini dikirim otomatis oleh sistem Ferdinand Hutahaean &amp; Co.<br>
          Harap tidak membalas email ini secara langsung.
        </div>
      </div>
    </body>
    </html>
    HTML;
}

/**
 * Versi plain text dari email (fallback untuk client yang tidak support HTML).
 */
function buildEmailBodyText(array $d): string
{
    $time = date('d M Y, H:i') . ' WIB';
    return
        "PESAN KONSULTASI BARU\n" .
        "=====================\n\n" .
        "Nama     : " . ($d['name']    ?? '-') . "\n" .
        "Email    : " . ($d['email']   ?? '-') . "\n" .
        "Telepon  : " . ($d['phone']   ?? '-') . "\n" .
        "Bidang   : " . ($d['subject'] ?? '-') . "\n" .
        "Waktu    : {$time}\n\n" .
        "Pesan:\n" . ($d['message'] ?? '-') . "\n\n" .
        "---\nFerdinand Hutahaean & Co";
}

// ============================================================
// ROUTING API
// ============================================================

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
    $name    = trim($body['name']    ?? '');
    $email   = trim($body['email']   ?? '');
    $phone   = trim($body['phone']   ?? '');
    $subject = trim($body['subject'] ?? '');
    $message = trim($body['message'] ?? '');

    if (!$name || !$email || !$message) {
        respondError('Nama, email, dan pesan wajib diisi');
    }

    // Simpan ke database
    $stmt = $db->prepare("INSERT INTO contacts (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sssss', $name, $email, $phone, $subject, $message);
    $stmt->execute();

    // Kirim notifikasi email ke admin
    sendAdminNotification([
        'name'    => $name,
        'email'   => $email,
        'phone'   => $phone,
        'subject' => $subject,
        'message' => $message,
    ]);

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