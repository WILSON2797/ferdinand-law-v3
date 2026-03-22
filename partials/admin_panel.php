<!-- ====== ADMIN PANEL ====== -->
<div id="admin-overlay">
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-logo">
      <div class="admin-logo-main">Ferdinand Hutahaean</div>
      <div class="admin-logo-sub">Admin Panel</div>
    </div>
    <nav class="admin-nav">
      <div class="admin-nav-section">Utama</div>
      <button class="admin-nav-item active" onclick="showAdminPanel('dashboard', this)">
        <span class="nav-icon">📊</span> Dashboard
      </button>
      <button class="admin-nav-item" onclick="showAdminPanel('settings', this)">
        <span class="nav-icon">⚙️</span> Pengaturan
      </button>

      <div class="admin-nav-section">Konten</div>
      <button class="admin-nav-item" onclick="showAdminPanel('practice', this)">
        <span class="nav-icon">⚖️</span> Area Praktik
      </button>
      <button class="admin-nav-item" onclick="showAdminPanel('team', this)">
        <span class="nav-icon">👥</span> Tim
      </button>
      <button class="admin-nav-item" onclick="showAdminPanel('testimonials', this)">
        <span class="nav-icon">💬</span> Testimoni
      </button>
      <button class="admin-nav-item" onclick="showAdminPanel('cases', this)">
        <span class="nav-icon">📁</span> Kasus
      </button>
      <button class="admin-nav-item" onclick="showAdminPanel('news', this)">
        <span class="nav-icon">📰</span> Berita
      </button>

      <div class="admin-nav-section">Lainnya</div>
      <button class="admin-nav-item" onclick="showAdminPanel('contacts', this)">
        <span class="nav-icon">📬</span> Pesan Masuk
        <span class="unread-badge" id="unread-count" style="display:none">0</span>
      </button>
      
      <button class="admin-nav-item" onclick="showAdminPanel('security', this)">
        <span class="nav-icon">🔐</span> Keamanan
      </button>
    </nav>
    <div class="admin-sidebar-footer">
      <div class="admin-user">
        <strong id="admin-username">Admin</strong>
        Administrator
      </div>
      <button class="admin-logout" onclick="adminLogout()">Keluar</button>
    </div>
  </aside>

  <main class="admin-main">
    <div class="admin-topbar">
      <div class="admin-topbar-title">
        <span id="admin-page-title">Dashboard</span>
      </div>
      <button class="admin-view-site" onclick="exitAdmin()">← Lihat Website</button>
    </div>
    <div class="admin-content">

      <!-- DASHBOARD -->
      <div class="admin-panel active" id="panel-dashboard">
        <div class="dash-stats" id="dashStats">
          <div class="dash-stat-card">
            <div class="dash-stat-icon">⚖️</div>
            <div><div class="dash-stat-num" id="d-practice">0</div><div class="dash-stat-label">Area Praktik</div></div>
          </div>
          <div class="dash-stat-card">
            <div class="dash-stat-icon">👥</div>
            <div><div class="dash-stat-num" id="d-team">0</div><div class="dash-stat-label">Anggota Tim</div></div>
          </div>
          <div class="dash-stat-card">
            <div class="dash-stat-icon">📰</div>
            <div><div class="dash-stat-num" id="d-news">0</div><div class="dash-stat-label">Artikel</div></div>
          </div>
          <div class="dash-stat-card">
            <div class="dash-stat-icon">📬</div>
            <div><div class="dash-stat-num" id="d-contacts">0</div><div class="dash-stat-label">Pesan Masuk</div></div>
          </div>
        </div>
        <div class="admin-card">
          <div class="admin-card-title">Selamat Datang di Admin Panel</div>
          <p style="color:rgba(255,255,255,0.5);font-size:0.82rem;line-height:1.8">
            Gunakan panel admin ini untuk mengelola seluruh konten website Ferdinand Hutahaean & Co.<br>
            Pilih menu di sidebar untuk mulai mengedit konten.
          </p>
        </div>
      </div>

      <!-- SETTINGS -->
      <div class="admin-panel" id="panel-settings">
        <div class="admin-card">
          <div class="admin-card-title">Pengaturan Hero Section</div>
          <div class="admin-field"><label>Judul Hero</label><input type="text" id="s-hero_title" /></div>
          <div class="admin-field"><label>Subtitle Hero</label><textarea id="s-hero_subtitle" style="min-height:80px"></textarea></div>
          <div class="admin-field"><label>Badge Text</label><input type="text" id="s-hero_badge" /></div>
        </div>
        <div class="admin-card">
          <div class="admin-card-title">Tentang Firma</div>
          <div class="admin-field"><label>Judul Section About</label><input type="text" id="s-about_title" /></div>
          <div class="admin-field"><label>Deskripsi</label><textarea id="s-about_content"></textarea></div>
          <div class="admin-field"><label>Visi Firma</label><textarea id="s-about_vision" style="min-height:80px"></textarea></div>
        </div>
        <div class="admin-card">
          <div class="admin-card-title">Statistik</div>
          <div class="admin-grid-2">
            <div class="admin-field"><label>Kasus Selesai</label><input type="text" id="s-stats_cases" /></div>
            <div class="admin-field"><label>Jumlah Klien</label><input type="text" id="s-stats_clients" /></div>
            <div class="admin-field"><label>Tahun Pengalaman</label><input type="text" id="s-stats_years" /></div>
            <div class="admin-field"><label>Jumlah Lawyer</label><input type="text" id="s-stats_lawyers" /></div>
          </div>
        </div>
        <div class="admin-card">
          <div class="admin-card-title">Kontak & Info</div>
          <div class="admin-grid-2">
            <div class="admin-field"><label>Telepon</label><input type="text" id="s-phone" /></div>
            <div class="admin-field"><label>Email</label><input type="email" id="s-email" /></div>
            <div class="admin-field"><label>WhatsApp</label><input type="text" id="s-whatsapp" /></div>
            <div class="admin-field"><label>Jam Operasional</label><input type="text" id="s-office_hours" /></div>
          </div>
          <div class="admin-field"><label>Alamat</label><textarea id="s-address" style="min-height:70px"></textarea></div>
        </div>
        <div class="admin-card">
          <div class="admin-card-title">Media Sosial</div>
          <div class="admin-grid-2">
            <div class="admin-field"><label>LinkedIn URL</label><input type="text" id="s-linkedin" /></div>
            <div class="admin-field"><label>Instagram URL</label><input type="text" id="s-instagram" /></div>
          </div>
        </div>
        <div class="admin-card">
          <div class="admin-card-title">Footer</div>
          <div class="admin-field"><label>Teks Copyright</label><input type="text" id="s-footer_text" /></div>
        </div>
        <button class="btn-admin" onclick="saveSettings()">💾 Simpan Semua Pengaturan</button>
      </div>

      <!-- PRACTICE AREAS -->
      <div class="admin-panel" id="panel-practice">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
          <div style="color:rgba(255,255,255,0.5);font-size:0.8rem">Kelola area praktik hukum</div>
          <button class="btn-admin" onclick="openModal('practice')">+ Tambah Area</button>
        </div>
        <div class="admin-card">
          <div class="admin-table-wrap">
            <table class="admin-table">
              <thead>
                <tr><th>Icon</th><th>Judul</th><th>Deskripsi</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr>
              </thead>
              <tbody id="practice-tbody"></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- TEAM -->
      <div class="admin-panel" id="panel-team">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
          <div style="color:rgba(255,255,255,0.5);font-size:0.8rem">Kelola anggota tim</div>
          <button class="btn-admin" onclick="openModal('team')">+ Tambah Anggota</button>
        </div>
        <div class="admin-card">
          <div class="admin-table-wrap">
            <table class="admin-table">
              <thead>
                <tr><th>Nama</th><th>Jabatan</th><th>Pendidikan</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr>
              </thead>
              <tbody id="team-tbody"></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- TESTIMONIALS -->
      <div class="admin-panel" id="panel-testimonials">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
          <div style="color:rgba(255,255,255,0.5);font-size:0.8rem">Kelola testimoni klien</div>
          <button class="btn-admin" onclick="openModal('testimonials')">+ Tambah Testimoni</button>
        </div>
        <div class="admin-card">
          <div class="admin-table-wrap">
            <table class="admin-table">
              <thead>
                <tr><th>Nama Klien</th><th>Perusahaan</th><th>Isi</th><th>Rating</th><th>Status</th><th>Aksi</th></tr>
              </thead>
              <tbody id="testimonials-tbody"></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- CASES -->
      <div class="admin-panel" id="panel-cases">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
          <div style="color:rgba(255,255,255,0.5);font-size:0.8rem">Kelola daftar kasus</div>
          <button class="btn-admin" onclick="openModal('cases')">+ Tambah Kasus</button>
        </div>
        <div class="admin-card">
          <div class="admin-table-wrap">
            <table class="admin-table">
              <thead>
                <tr><th>Judul</th><th>Kategori</th><th>Tahun</th><th>Unggulan</th><th>Status</th><th>Aksi</th></tr>
              </thead>
              <tbody id="cases-tbody"></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- NEWS -->
      <div class="admin-panel" id="panel-news">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
          <div style="color:rgba(255,255,255,0.5);font-size:0.8rem">Kelola berita & artikel</div>
          <button class="btn-admin" onclick="openModal('news')">+ Buat Artikel</button>
        </div>
        <div class="admin-card">
          <div class="admin-table-wrap">
            <table class="admin-table">
              <thead>
                <tr><th>Judul</th><th>Penulis</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr>
              </thead>
              <tbody id="news-tbody"></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- CONTACTS -->
      <div class="admin-panel" id="panel-contacts">
        <div class="admin-card">
          <div class="admin-table-wrap">
            <table class="admin-table">
              <thead>
                <tr><th>Nama</th><th>Email</th><th>Subjek</th><th>Pesan</th><th>Waktu</th><th>Status</th><th>Aksi</th></tr>
              </thead>
              <tbody id="contacts-tbody"></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- SECURITY -->
      <div class="admin-panel" id="panel-security">
        <div class="admin-card" style="max-width:480px">
          <div class="admin-card-title">🔐 Ganti Password</div>
          <p style="color:rgba(255,255,255,0.4);font-size:0.78rem;line-height:1.7;margin-bottom:1.5rem">
            Gunakan password yang kuat — minimal 6 karakter, kombinasi huruf dan angka.
          </p>
 
          <div class="admin-field">
            <label>Password Saat Ini</label>
            <input type="password" id="sec-current" placeholder="••••••••" />
          </div>
          <div class="admin-field">
            <label>Password Baru</label>
            <input type="password" id="sec-new" placeholder="••••••••" />
          </div>
          <div class="admin-field">
            <label>Konfirmasi Password Baru</label>
            <input type="password" id="sec-confirm" placeholder="••••••••" />
          </div>
 
          <!-- Error -->
          <div id="sec-error" style="display:none;background:rgba(255,80,80,0.1);border:1px solid rgba(255,80,80,0.3);color:#ff9999;padding:10px 14px;font-size:0.75rem;margin-bottom:1rem;"></div>
 
          <!-- Success -->
          <div id="sec-success" style="display:none;background:rgba(201,168,76,0.1);border:1px solid rgba(201,168,76,0.3);color:var(--gold);padding:10px 14px;font-size:0.75rem;margin-bottom:1rem;">
            ✅ Password berhasil diubah!
          </div>
 
          <button class="btn-admin" onclick="changePassword()">🔐 Simpan Password Baru</button>
        </div>
      </div>

    </div><!-- end admin-content -->
  </main>
</div>