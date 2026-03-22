<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Database Setup — Ferdinand Hutahaean & Co</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'Segoe UI', system-ui, sans-serif;
    background: #0A1628;
    color: #fff;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
  }
  .box {
    background: #161b22;
    border: 1px solid rgba(201,168,76,0.25);
    padding: 2.5rem;
    width: 100%;
    max-width: 680px;
  }
  .logo { font-size: 1.3rem; color: #C9A84C; font-weight: 600; letter-spacing: 0.5px; margin-bottom: 0.3rem; }
  .logo-sub { font-size: 0.65rem; color: rgba(255,255,255,0.3); letter-spacing: 3px; text-transform: uppercase; margin-bottom: 2rem; }
  h1 { font-size: 1rem; font-weight: 700; color: rgba(255,255,255,0.8); margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid rgba(255,255,255,0.06); }
  .section-label { font-size: 0.6rem; letter-spacing: 3px; text-transform: uppercase; color: rgba(201,168,76,0.6); margin: 1.5rem 0 0.5rem; font-weight: 600; }
  .step { display: flex; align-items: flex-start; gap: 1rem; padding: 0.8rem 0; border-bottom: 1px solid rgba(255,255,255,0.04); font-size: 0.82rem; }
  .step:last-child { border-bottom: none; }
  .step-icon { width: 26px; height: 26px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; margin-top: 1px; }
  .step-text { flex: 1; color: rgba(255,255,255,0.75); }
  .step-text b { color: #fff; }
  .detail { color: rgba(255,255,255,0.35); font-size: 0.74rem; margin-top: 2px; }
  .err-msg { color: #ff8080; font-size: 0.74rem; margin-top: 2px; font-family: monospace; }
  .summary { margin-top: 1.5rem; padding: 1.25rem 1.5rem; border: 1px solid; font-size: 0.82rem; line-height: 1.8; }
  .summary.success { border-color: rgba(80,200,120,0.3); background: rgba(80,200,120,0.07); }
  .summary.error { border-color: rgba(255,80,80,0.3); background: rgba(255,80,80,0.07); }
  .summary h2 { font-size: 0.9rem; margin-bottom: 0.6rem; }
  .summary.success h2 { color: #50c878; }
  .summary.error h2 { color: #ff6b6b; }
  .cred { background: rgba(201,168,76,0.08); border: 1px solid rgba(201,168,76,0.2); padding: 0.85rem 1rem; margin-top: 1rem; font-size: 0.82rem; }
  .cred b { color: #C9A84C; }
  .warn { margin-top: 1rem; padding: 0.75rem 1rem; background: rgba(255,160,0,0.1); border: 1px solid rgba(255,160,0,0.25); font-size: 0.78rem; color: rgba(255,200,80,0.9); }
  a.btn { display: inline-block; margin-top: 1.5rem; background: #C9A84C; color: #0A1628; padding: 12px 28px; font-weight: 700; font-size: 0.75rem; letter-spacing: 1.5px; text-transform: uppercase; text-decoration: none; }
  a.btn:hover { background: #E8C97A; }
</style>
</head>
<body>
<div class="box">
  <div class="logo">Ferdinand Hutahaean & Co</div>
  <div class="logo-sub">Database Installation Script</div>
  <h1>&#9881;&#65039; Setup Database Otomatis</h1>

<?php
// ─── KONFIGURASI — Sesuaikan dengan hosting Anda ──────────
$DB_HOST = 'localhost';
$DB_USER = 'root';          // Ganti dengan username MySQL Anda
$DB_PASS = '';              // Ganti dengan password MySQL Anda
$DB_NAME = 'ferdinand_law';
// ──────────────────────────────────────────────────────────

$success = true;
$output  = '';

function renderStep($icon, $label, $detail = '', $isErr = false) {
    global $output;
    $detailEl = $detail ? "<div class='" . ($isErr ? 'err-msg' : 'detail') . "'>$detail</div>" : '';
    $output .= "<div class='step'><div class='step-icon'>$icon</div><div class='step-text'><div>$label</div>$detailEl</div></div>";
}

function sectionLabel($title) {
    global $output;
    $output .= "<div class='section-label'>$title</div>";
}

// 1. Koneksi MySQL
$conn = @new mysqli($DB_HOST, $DB_USER, $DB_PASS);
if ($conn->connect_error) {
    renderStep('&#10060;', 'Koneksi ke MySQL <b>gagal</b>', htmlspecialchars($conn->connect_error), true);
    $success = false;
} else {
    renderStep('&#9989;', 'Koneksi ke MySQL server <b>berhasil</b>', "Host: $DB_HOST | User: $DB_USER");
    $conn->set_charset('utf8mb4');
}

// 2. Buat Database
if ($success) {
    if ($conn->query("CREATE DATABASE IF NOT EXISTS `$DB_NAME` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
        renderStep('&#9989;', "Database <b>$DB_NAME</b> dibuat / sudah ada");
        $conn->select_db($DB_NAME);
    } else {
        renderStep('&#10060;', 'Gagal membuat database', htmlspecialchars($conn->error), true);
        $success = false;
    }
}

// 3. Buat Tabel
if ($success) {
    sectionLabel('Membuat Tabel');
    $tables = [
        'admins' => "CREATE TABLE IF NOT EXISTS `admins` (
            `id` INT NOT NULL AUTO_INCREMENT, `username` VARCHAR(100) NOT NULL,
            `password` VARCHAR(255) NOT NULL, `name` VARCHAR(200) DEFAULT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`), UNIQUE KEY `username` (`username`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        'site_settings' => "CREATE TABLE IF NOT EXISTS `site_settings` (
            `id` INT NOT NULL AUTO_INCREMENT, `key` VARCHAR(100) NOT NULL,
            `value` TEXT DEFAULT NULL,
            `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`), UNIQUE KEY `key` (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        'practice_areas' => "CREATE TABLE IF NOT EXISTS `practice_areas` (
            `id` INT NOT NULL AUTO_INCREMENT, `icon` VARCHAR(100) DEFAULT NULL,
            `title` VARCHAR(200) NOT NULL, `description` TEXT DEFAULT NULL,
            `sort_order` INT NOT NULL DEFAULT 0, `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`), KEY `idx_active_sort` (`is_active`,`sort_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        'team_members' => "CREATE TABLE IF NOT EXISTS `team_members` (
            `id` INT NOT NULL AUTO_INCREMENT, `name` VARCHAR(200) NOT NULL,
            `position` VARCHAR(200) DEFAULT NULL, `education` TEXT DEFAULT NULL,
            `photo` VARCHAR(255) DEFAULT NULL,
            `sort_order` INT NOT NULL DEFAULT 0, `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`), KEY `idx_active_sort` (`is_active`,`sort_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        'cases' => "CREATE TABLE IF NOT EXISTS `cases` (
            `id` INT NOT NULL AUTO_INCREMENT, `title` VARCHAR(300) NOT NULL,
            `category` VARCHAR(100) DEFAULT NULL, `description` TEXT DEFAULT NULL,
            `outcome` VARCHAR(255) DEFAULT NULL, `year` INT DEFAULT NULL,
            `is_featured` TINYINT(1) NOT NULL DEFAULT 0, `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`), KEY `idx_active_year` (`is_active`,`year`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        'testimonials' => "CREATE TABLE IF NOT EXISTS `testimonials` (
            `id` INT NOT NULL AUTO_INCREMENT, `client_name` VARCHAR(200) NOT NULL,
            `client_company` VARCHAR(200) DEFAULT NULL, `content` TEXT NOT NULL,
            `rating` INT NOT NULL DEFAULT 5, `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`), KEY `idx_active` (`is_active`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        'news_articles' => "CREATE TABLE IF NOT EXISTS `news_articles` (
            `id` INT NOT NULL AUTO_INCREMENT, `title` VARCHAR(300) NOT NULL,
            `slug` VARCHAR(300) NOT NULL, `excerpt` TEXT DEFAULT NULL,
            `content` LONGTEXT DEFAULT NULL, `image` VARCHAR(255) DEFAULT NULL,
            `author` VARCHAR(200) DEFAULT NULL,
            `is_published` TINYINT(1) NOT NULL DEFAULT 0, `published_at` DATETIME DEFAULT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`), UNIQUE KEY `slug` (`slug`),
            KEY `idx_published` (`is_published`,`published_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

        'contacts' => "CREATE TABLE IF NOT EXISTS `contacts` (
            `id` INT NOT NULL AUTO_INCREMENT, `name` VARCHAR(200) NOT NULL,
            `email` VARCHAR(200) NOT NULL, `phone` VARCHAR(50) DEFAULT NULL,
            `subject` VARCHAR(300) DEFAULT NULL, `message` TEXT NOT NULL,
            `is_read` TINYINT(1) NOT NULL DEFAULT 0,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`), KEY `idx_read` (`is_read`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    ];

    foreach ($tables as $name => $sql) {
        if ($conn->query($sql)) {
            renderStep('&#9989;', "Tabel <b>$name</b>");
        } else {
            renderStep('&#10060;', "Gagal buat tabel <b>$name</b>", htmlspecialchars($conn->error), true);
            $success = false;
        }
    }
}

// 4. Seed Data
if ($success) {
    sectionLabel('Mengisi Data Awal (Seed)');

    // Admin
    $pass = password_hash('admin123', PASSWORD_BCRYPT);
    $conn->query("INSERT IGNORE INTO `admins` (username, password, name) VALUES ('admin', '$pass', 'Administrator')");
    $rows = $conn->affected_rows;
    renderStep('&#9989;', 'Akun admin default', $rows > 0 ? 'Dibuat baru — username: admin | password: admin123' : 'Sudah ada, dilewati');

    // Settings
    $settings = [
        ['site_name','FERDINAND HUTAHAEAN & Co'],
        ['site_tagline','Advocates & Legal Consultants'],
        ['hero_title',"Excellence in\nLegal Advocacy"],
        ['hero_subtitle','Memberikan nasihat dan representasi hukum terpercaya dengan integritas, keahlian, dan dedikasi penuh kepada keadilan untuk setiap klien kami.'],
        ['about_title','Tentang Firma Kami'],
        ['about_content','Ferdinand Hutahaean & Co adalah firma hukum terkemuka yang berpengalaman dalam berbagai bidang hukum. Didirikan dengan komitmen kuat terhadap keadilan dan profesionalisme, kami telah melayani klien korporasi dan individu selama lebih dari dua dekade.'],
        ['about_vision','Menjadi firma hukum pilihan utama yang dikenal atas keunggulan, integritas, dan komitmen tak tergoyahkan terhadap keadilan.'],
        ['phone','+62 21 1234 5678'],
        ['email','info@ferdinandlaw.co.id'],
        ['address','Jl. Jend. Sudirman No. 123, Jakarta Selatan 12190'],
        ['office_hours','Senin - Jumat: 08:00 - 17:00 WIB'],
        ['whatsapp','+6281234567890'],
        ['linkedin','https://linkedin.com/company/ferdinand-law'],
        ['instagram','https://instagram.com/ferdinandlaw'],
        ['footer_text','© 2024 Ferdinand Hutahaean & Co. All rights reserved.'],
        ['stats_cases','500+'],
        ['stats_clients','350+'],
        ['stats_years','20+'],
        ['stats_lawyers','15+'],
    ];
    $c = 0;
    foreach ($settings as [$k,$v]) {
        $k = $conn->real_escape_string($k); $v = $conn->real_escape_string($v);
        if ($conn->query("INSERT IGNORE INTO `site_settings` (`key`,value) VALUES ('$k','$v')")) $c++;
    }
    renderStep('&#9989;', 'Pengaturan situs', "$c item pengaturan");

    // Practice areas
    $areas = [
        ['&#9878;&#65039;','Hukum Korporasi','Konsultasi dan pendampingan hukum untuk perusahaan, merger & akuisisi, perjanjian bisnis, pendirian PT/CV, serta kepatuhan terhadap regulasi perusahaan.',1],
        ['&#127963;&#65039;','Litigasi & Arbitrase','Representasi profesional di pengadilan negeri, pengadilan tinggi, dan Mahkamah Agung, serta forum arbitrase nasional (BANI) dan internasional.',2],
        ['&#128203;','Hukum Ketenagakerjaan','Perlindungan hak-hak tenaga kerja dan pengusaha, penyelesaian perselisihan hubungan industrial, dan kepatuhan UU Ketenagakerjaan.',3],
        ['&#127968;','Hukum Properti & Real Estate','Pengurusan sertifikat, due diligence properti, transaksi real estate, perizinan bangunan, dan penyelesaian sengketa pertanahan.',4],
        ['&#128188;','Hukum Bisnis & Investasi','Struktur investasi, perjanjian komersial, perlindungan aset, restrukturisasi utang, dan hukum persaingan usaha.',5],
        ['&#127760;','Hukum Internasional','Transaksi lintas batas, arbitrase internasional, kepatuhan regulasi ekspor-impor, dan konsultasi investasi asing langsung.',6],
    ];
    $c = 0;
    foreach ($areas as [$icon,$title,$desc,$sort]) {
        $icon=$conn->real_escape_string($icon);$title=$conn->real_escape_string($title);$desc=$conn->real_escape_string($desc);
        if ($conn->query("INSERT IGNORE INTO `practice_areas` (icon,title,description,sort_order) VALUES ('$icon','$title','$desc',$sort)")) $c++;
    }
    renderStep('&#9989;', 'Area praktik hukum', "$c area");

    // Team
    $team = [
        ['Ferdinand Hutahaean, S.H., M.H.','Founding Partner & Senior Advocate',"S.H. - Universitas Indonesia\nM.H. - Universitas Gadjah Mada\nSpesialisasi: Hukum Korporasi & Litigasi",1],
        ['Maria Sihotang, S.H., LL.M.','Senior Partner - Corporate & International',"S.H. - Universitas Airlangga\nLL.M. - National University of Singapore\nSpesialisasi: Hukum Bisnis Internasional",2],
        ['Budi Santoso, S.H.','Associate Advocate - Labor & Employment',"S.H. - Universitas Padjadjaran\nSpesialisasi: Hukum Ketenagakerjaan & Hubungan Industrial",3],
        ['Reni Purwanti, S.H., M.Kn.','Associate - Property & Land Affairs',"S.H. - Universitas Diponegoro\nM.Kn. - Magister Kenotariatan UI\nSpesialisasi: Hukum Properti & Pertanahan",4],
    ];
    $c = 0;
    foreach ($team as [$name,$pos,$edu,$sort]) {
        $name=$conn->real_escape_string($name);$pos=$conn->real_escape_string($pos);$edu=$conn->real_escape_string($edu);
        if ($conn->query("INSERT IGNORE INTO `team_members` (name,position,education,sort_order) VALUES ('$name','$pos','$edu',$sort)")) $c++;
    }
    renderStep('&#9989;', 'Anggota tim', "$c anggota");

    // Testimonials
    $tms = [
        ['Direktur Utama, PT. Mega Konstruksi','PT. Mega Konstruksi Indonesia','Ferdinand Hutahaean & Co telah membantu menyelesaikan sengketa kontrak senilai ratusan miliar rupiah. Profesionalisme dan dedikasi tim mereka benar-benar luar biasa.',5],
        ['Hendra Wijaya','Pengusaha & Investor Properti','Layanan hukum yang sangat komprehensif. Tim mereka selalu responsif dan memberikan solusi tepat untuk setiap permasalahan hukum bisnis saya.',5],
        ['Sari Dewi, S.Psi.','HR Manager, PT. Nusantara Indah','Konsultasi ketenagakerjaan mereka sangat membantu dalam mematuhi regulasi dan menyelesaikan kasus perselisihan industrial secara damai.',5],
        ['Komisaris, PT. Tirta Abadi Jaya','PT. Tirta Abadi Jaya','Due diligence akuisisi berjalan sangat lancar. Mereka detail, cermat, dan selalu selangkah lebih maju dalam mengantisipasi risiko hukum.',5],
    ];
    $c = 0;
    foreach ($tms as [$name,$company,$content,$rating]) {
        $name=$conn->real_escape_string($name);$company=$conn->real_escape_string($company);$content=$conn->real_escape_string($content);
        if ($conn->query("INSERT IGNORE INTO `testimonials` (client_name,client_company,content,rating) VALUES ('$name','$company','$content',$rating)")) $c++;
    }
    renderStep('&#9989;', 'Testimoni klien', "$c testimoni");

    // Cases
    $cases = [
        ['Sengketa Kontrak Konstruksi Rp 450 Miliar','Litigasi Komersial','Mewakili kontraktor dalam sengketa wanprestasi proyek infrastruktur jalan tol.','Menang - Klien mendapat ganti rugi penuh + bunga',2023,1],
        ['Akuisisi Perusahaan Manufaktur Multinasional','Hukum Korporasi','Pendampingan end-to-end akuisisi 100% saham perusahaan manufaktur oleh investor asing.','Transaksi berhasil tanpa hambatan regulasi',2023,1],
        ['Penyelesaian PHK Massal 1.200 Karyawan','Hukum Ketenagakerjaan','Menangani PHK massal akibat restrukturisasi bisnis perusahaan retail nasional.','100% karyawan terima hak pesangon penuh',2022,1],
        ['Sengketa Tanah 15 Hektar di Bekasi','Hukum Properti','Penanganan sengketa tumpang tindih kepemilikan tanah antara pengembang dan ahli waris.','Sertifikat klien dinyatakan sah oleh PTUN',2022,0],
        ['Arbitrase Internasional SIAC Singapura','Hukum Internasional','Representasi produsen dalam arbitrase SIAC melawan distributor Malaysia.','Award arbitrase memihak klien - USD 2.8 juta',2024,1],
        ['Restrukturisasi Utang Perusahaan Rp 1.2 Triliun','Hukum Bisnis','Pendampingan restrukturisasi utang kepada 7 kreditur perbankan termasuk PKPU sukarela.','Perjanjian restrukturisasi disepakati semua kreditur',2024,1],
    ];
    $c = 0;
    foreach ($cases as [$title,$cat,$desc,$outcome,$year,$featured]) {
        $title=$conn->real_escape_string($title);$cat=$conn->real_escape_string($cat);
        $desc=$conn->real_escape_string($desc);$outcome=$conn->real_escape_string($outcome);
        if ($conn->query("INSERT IGNORE INTO `cases` (title,category,description,outcome,year,is_featured) VALUES ('$title','$cat','$desc','$outcome',$year,$featured)")) $c++;
    }
    renderStep('&#9989;', 'Portofolio kasus', "$c kasus");

    // News
    $news = [
        ['Memahami Hak Karyawan dalam Proses PHK: Panduan Lengkap 2024','memahami-hak-karyawan-phk-2024','Pemutusan Hubungan Kerja (PHK) kerap menjadi situasi penuh ketidakpastian. Artikel ini mengulas hak-hak karyawan yang wajib dipenuhi sesuai UU Ketenagakerjaan terbaru.','Ferdinand Hutahaean, S.H., M.H.','2024-03-15 08:00:00'],
        ['Pentingnya Due Diligence Hukum Sebelum Akuisisi Perusahaan','due-diligence-hukum-akuisisi-perusahaan','Sebelum mengakuisisi sebuah perusahaan, due diligence hukum adalah langkah krusial yang tidak boleh dilewatkan untuk melindungi investasi Anda.','Maria Sihotang, S.H., LL.M.','2024-04-01 09:00:00'],
        ['Update Regulasi: Perubahan Ketentuan Investasi Asing di Indonesia 2024','update-regulasi-investasi-asing-indonesia-2024','Pemerintah terus memperbarui regulasi investasi asing untuk meningkatkan daya saing. Berikut perkembangan terbaru yang perlu diketahui investor.','Ferdinand Hutahaean, S.H., M.H.','2024-04-20 10:00:00'],
        ['Panduan Lengkap Sertifikasi Tanah: Dari AJB hingga SHM','panduan-sertifikasi-tanah-ajb-shm','Proses sertifikasi tanah sering dianggap rumit. Artikel ini memandu Anda langkah demi langkah dari Akta Jual Beli hingga terbitnya Sertifikat Hak Milik.','Reni Purwanti, S.H., M.Kn.','2024-05-05 08:30:00'],
    ];
    $c = 0;
    foreach ($news as [$title,$slug,$excerpt,$author,$pubdate]) {
        $title=$conn->real_escape_string($title);$slug=$conn->real_escape_string($slug);
        $excerpt=$conn->real_escape_string($excerpt);$author=$conn->real_escape_string($author);
        if ($conn->query("INSERT IGNORE INTO `news_articles` (title,slug,excerpt,author,is_published,published_at) VALUES ('$title','$slug','$excerpt','$author',1,'$pubdate')")) $c++;
    }
    renderStep('&#9989;', 'Artikel berita', "$c artikel");
}

// Render semua output
echo $output;
?>

<?php if ($success): ?>
<div class="summary success">
  <h2>&#9989; Instalasi Database Berhasil!</h2>
  Database <b><?= $DB_NAME ?></b> berhasil dibuat dengan semua tabel dan data awal.
  <div class="cred">
    <b>Kredensial Admin Default:</b><br>
    &#128273; Username: <b>admin</b><br>
    &#128274; Password: <b>admin123</b>
  </div>
  <div class="warn">
    &#9888;&#65039; <b>PENTING:</b> Segera ganti password setelah login pertama!<br>
    &#9888;&#65039; <b>HAPUS</b> file <code>install.php</code> ini setelah selesai untuk keamanan server.
  </div>
</div>
<a class="btn" href="../index.html">&#8594; Buka Website</a>
<?php else: ?>
<div class="summary error">
  <h2>&#10060; Instalasi Gagal</h2>
  Terjadi kesalahan. Periksa konfigurasi di bagian atas file <code>install.php</code>:<br><br>
  &bull; Pastikan MySQL/MariaDB server sedang berjalan<br>
  &bull; Periksa username dan password database<br>
  &bull; Pastikan user MySQL memiliki hak CREATE DATABASE<br>
  &bull; Hubungi hosting provider jika masalah berlanjut
</div>
<?php endif; ?>

</div>
</body>
</html>
<?php if (isset($conn)) $conn->close(); ?>
