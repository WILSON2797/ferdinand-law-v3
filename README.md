# Ferdinand Hutahaean & Co — Website

Website firma hukum lengkap dengan fitur Admin Panel.

---

## 📁 Struktur File

```
ferdinand-law/
├── index.php                   ← Website utama (buka ini di browser)
├── .htaccess                   ← URL rewriting root
|
│
├── partials/                   ← Potongan HTML (di-include oleh index.php)
│   ├── head.php                ← <head>, meta tags, CSS & font links
│   ├── navbar.php              ← Navbar + mobile menu
│   ├── hero.php                ← Section Hero
│   ├── about.php               ← Section Tentang Firma
│   ├── practice.php            ← Section Area Praktik
│   ├── stats.php               ← Section Statistik
│   ├── team.php                ← Section Tim
│   ├── testimonials.php        ← Section Testimoni
│   ├── news.php                ← Section Berita & Artikel
│   ├── contact.php             ← Section Kontak & Form
│   ├── footer.php              ← Footer
│   ├── admin_login.php         ← Overlay login admin
│   ├── admin_panel.php         ← Sidebar + semua panel admin
│   ├── modals.php              ← Modal CRUD, article modal, toast
│   └── scripts.php             ← Tag <script> di bagian bawah body
│
├── backend/
│   ├── config.php              ← Konfigurasi database & auth (JWT)
│   ├── index.php               ← Router API utama
│   ├── install.php             ← Script instalasi DB (jalankan sekali!)
│   ├── .htaccess               ← URL rewriting untuk API
│   └── api/
│       ├── auth.php            ← Login / logout admin
│       ├── settings.php        ← Pengaturan konten situs
│       ├── practice_areas.php  ← CRUD area praktik hukum
│       ├── team.php            ← CRUD anggota tim
│       ├── testimonials.php    ← CRUD testimoni klien
│       ├── cases.php           ← CRUD portofolio kasus
│       ├── news.php            ← CRUD berita & artikel
│       ├── contacts.php        ← Pesan masuk dari form kontak
│       └── upload.php          ← Upload gambar (validasi & penyimpanan)
│
└── assets/
    └── img/                    
    └── js/                    
        ├── config.js          
        ├── utils.js          
        ├── public.js          
        ├── admin.js          
        ├── modal.js          
        ├── init.js          
        ├── gallery.js          
```

---

## 🚀 Cara Install

### 1. Persyaratan Server
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- Apache dengan mod_rewrite aktif

### 2. Upload ke Server
Upload **seluruh folder** ke direktori hosting Anda (misal: `public_html/` atau `htdocs/`).

### 3. Konfigurasi Database
Edit file `backend/config.php`:
```php
define('DB_HOST', 'localhost');       // Host MySQL
define('DB_USER', 'username_db');    // Username MySQL
define('DB_PASS', 'password_db');    // Password MySQL
define('DB_NAME', 'ferdinand_law');  // Nama database
```

### 4. Konfigurasi API URL
Edit `config.js`, sesuaikan path API dengan server Anda:
```javascript
const API = '/backend/api';
```

### 5. Inisialisasi Database
Akses di browser: `http://domain-anda.com/backend/install.php`

Proses ini akan:
- Membuat database & semua tabel
- Memasukkan data contoh
- Membuat akun admin default

> **Login default:** `admin` / `admin123`

### 6. Hapus install.php
Setelah setup berhasil, **WAJIB HAPUS** file `backend/install.php` demi keamanan!

---

## 🔐 Akses Admin Panel

1. Buka website
2. Scroll ke bawah, klik **"Admin"** di footer
3. Login dengan kredensial admin

### Fitur Admin:
- 📊 **Dashboard** — statistik ringkasan konten
- ⚙️ **Pengaturan** — ubah semua teks website (hero, about, kontak, dll)
- ⚖️ **Area Praktik** — tambah / edit / hapus layanan hukum
- 👥 **Tim** — kelola profil anggota tim
- 💬 **Testimoni** — kelola ulasan klien
- 📁 **Kasus** — portofolio kasus hukum
- 📰 **Berita** — tulis & kelola artikel
- 📬 **Pesan Masuk** — lihat pesan dari form kontak

---

## 🛠️ Menambah Fitur Baru

Karena HTML sudah dipecah ke dalam folder `partials/`, menambah section baru menjadi lebih mudah:

### Contoh: Menambah Section "FAQ"

**1. Buat file partial baru:**
```
partials/faq.php
```

**2. Isi dengan HTML section-nya:**
```html
<!-- ====== FAQ ====== -->
<section id="faq">
  <div class="faq-header" data-animate>
    <h2 class="section-title">Pertanyaan Umum</h2>
  </div>
  <div class="faq-list" id="faqList">
    <!-- Loaded dynamically -->
  </div>
</section>
```

**3. Include di `index.php`:**
```php
<?php include 'partials/news.php'; ?>
<?php include 'partials/faq.php'; ?>   <!-- tambahkan di sini -->
<?php include 'partials/contact.php'; ?>
```

**4. Tambahkan link navbar di `partials/navbar.php`:**
```html
<li><a href="#faq">FAQ</a></li>
```

**5. Buat endpoint API di `backend/api/faq.php`** (jika datanya dinamis dari database).

---

## 🎨 Kustomisasi Tampilan

### Mengganti Warna Utama
Edit CSS variables di `style.css`:
```css
:root {
  --gold: #C9A84C;
  --navy: #0A1628;
}
```

### Mengganti Font
```css
font-family: 'Cormorant Garamond', Georgia, serif;  /* Judul */
font-family: 'Montserrat', sans-serif;              /* Body */
```

---

## 🔒 Keamanan

- JWT Authentication untuk semua request admin
- Password hashing dengan bcrypt
- Prepared statements untuk mencegah SQL Injection
- Validasi & pembatasan tipe file saat upload gambar
- **Hapus `backend/install.php` setelah instalasi!**

---

## 📦 Teknologi yang Digunakan

| Layer | Teknologi |
|---|---|
| Frontend | HTML5, CSS3, Vanilla JavaScript |
| Backend | PHP 8+ |
| Database | MySQL / MariaDB |
| Auth | JWT (JSON Web Token) |
| Icons | Font Awesome 6 |
| Font | Google Fonts (Cormorant Garamond, Montserrat) |

---

**Catatan:** Seluruh konten website dapat diubah melalui Admin Panel tanpa perlu menyentuh kode.