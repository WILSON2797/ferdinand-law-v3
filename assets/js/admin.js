// ============================================================
// admin.js — Panel Admin (Login, Dashboard, Settings, Tabel)
// ============================================================

// ===== ADMIN LOGIN =====
function showAdminLogin() {
    if (authToken) { loadAdminPanel(); return; }
    document.getElementById('adminLogin').style.display = 'flex';
}

function hideAdminLogin() {
    document.getElementById('adminLogin').style.display = 'none';
}

async function doLogin() {
    const user  = document.getElementById('login-user').value.trim();
    const pass  = document.getElementById('login-pass').value;
    const errEl = document.getElementById('login-error');
    errEl.style.display = 'none';
    if (!user || !pass) { errEl.textContent = 'Isi username dan password'; errEl.style.display = 'block'; return; }
    const res  = await fetch(buildUrl('/auth/login'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username: user, password: pass })
    });
    const data = await res.json();
    if (data.token) {
        authToken = data.token;
        adminData = data.admin;
        localStorage.setItem('fh_token', authToken);
        hideAdminLogin();
        loadAdminPanel();
    } else {
        errEl.textContent = data.error || 'Login gagal';
        errEl.style.display = 'block';
    }
}

// ===== ADMIN PANEL =====
async function loadAdminPanel() {
    const overlay = document.getElementById('admin-overlay');
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
    if (adminData) document.getElementById('admin-username').textContent = adminData.name || adminData.username;

    const [pAreas, teams, newsRes, contacts] = await Promise.all([
        apiGet('/practice-areas?all=1', true),
        apiGet('/team?all=1', true),
        apiGet('/news?all=1', true),
        apiGet('/contacts', true)
    ]);

    document.getElementById('d-practice').textContent = pAreas?.length || 0;
    document.getElementById('d-team').textContent     = teams?.length || 0;
    document.getElementById('d-news').textContent     = newsRes?.total || 0;
    document.getElementById('d-contacts').textContent = contacts?.length || 0;

    const unread = contacts?.filter(c => c.is_read == 0).length || 0;
    const badge  = document.getElementById('unread-count');
    if (unread > 0) { badge.textContent = unread; badge.style.display = 'inline'; }
}

function exitAdmin() {
    document.getElementById('admin-overlay').classList.remove('active');
    document.body.style.overflow = '';
}

function adminLogout() {
    authToken = null; adminData = null;
    localStorage.removeItem('fh_token');
    exitAdmin();
    showToast('Berhasil keluar');
}

function showAdminPanel(name, btn) {
    document.querySelectorAll('.admin-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.admin-nav-item').forEach(b => b.classList.remove('active'));
    document.getElementById('panel-' + name).classList.add('active');
    if (btn) btn.classList.add('active');
    const titles = {
        dashboard: 'Dashboard', settings: 'Pengaturan Situs', practice: 'Area Praktik',
        team: 'Tim', testimonials: 'Testimoni', cases: 'Kasus', news: 'Berita & Artikel', contacts: 'Pesan Masuk'
    };
    document.getElementById('admin-page-title').textContent = titles[name] || name;
    const loaders = {
        settings: loadAdminSettings, practice: loadAdminPractice,
        team: loadAdminTeam, testimonials: loadAdminTestimonials,
        cases: loadAdminCases, news: loadAdminNews, contacts: loadAdminContacts
    };
    if (loaders[name]) loaders[name]();
}

// ===== ADMIN SETTINGS =====
async function loadAdminSettings() {
    const s = await apiGet('/settings');
    if (!s) return;
    Object.keys(s).forEach(k => {
        const el = document.getElementById('s-' + k);
        if (el) el.value = s[k] || '';
    });
}

async function saveSettings() {
    const fields = ['hero_title', 'hero_subtitle', 'about_title', 'about_content', 'about_vision',
        'stats_cases', 'stats_clients', 'stats_years', 'stats_lawyers',
        'phone', 'email', 'whatsapp', 'address', 'office_hours', 'linkedin', 'instagram', 'footer_text'];
    const data = {};
    fields.forEach(k => { const el = document.getElementById('s-' + k); if (el) data[k] = el.value; });
    const res = await apiPost('/settings', data, 'PUT');
    if (res && !res.error) { showToast('Pengaturan berhasil disimpan'); loadSiteData(); }
    else showToast('Gagal menyimpan: ' + (res?.error || ''), true);
}

// ===== CHANGE PASSWORD =====
async function changePassword() {
    const current = document.getElementById('sec-current').value;
    const newPass = document.getElementById('sec-new').value;
    const confirm = document.getElementById('sec-confirm').value;
    const errEl   = document.getElementById('sec-error');
    const sucEl   = document.getElementById('sec-success');
 
    errEl.style.display = 'none';
    sucEl.style.display = 'none';
 
    // Validasi
    if (!current || !newPass || !confirm) {
        errEl.textContent = 'Semua field wajib diisi.';
        errEl.style.display = 'block'; return;
    }
    if (newPass.length < 6) {
        errEl.textContent = 'Password baru minimal 6 karakter.';
        errEl.style.display = 'block'; return;
    }
    if (newPass !== confirm) {
        errEl.textContent = 'Konfirmasi password tidak cocok.';
        errEl.style.display = 'block'; return;
    }
 
    const res = await apiPost('/auth/change-password', {
        current_password: current,
        new_password:     newPass
    });
 
    if (res && !res.error) {
        sucEl.style.display = 'block';
        // Reset form
        ['sec-current', 'sec-new', 'sec-confirm'].forEach(id => document.getElementById(id).value = '');
        showToast('Password berhasil diubah!');
    } else {
        errEl.textContent   = res?.error || 'Gagal mengubah password. Cek password saat ini.';
        errEl.style.display = 'block';
    }
}

// ===== ADMIN PRACTICE AREAS =====
async function loadAdminPractice() {
    const items = await apiGet('/practice-areas?all=1', true);
    const tbody = document.getElementById('practice-tbody');
    tbody.innerHTML = (items || []).map(a => `
    <tr>
      <td style="font-size:1.4rem">${esc(a.icon)}</td>
      <td style="font-weight:600;color:#fff">${esc(a.title)}</td>
      <td style="max-width:250px">${trunc(a.description)}</td>
      <td>${a.sort_order}</td>
      <td><span class="badge ${a.is_active ? 'badge-active' : 'badge-inactive'}">${a.is_active ? 'Aktif' : 'Nonaktif'}</span></td>
      <td>
        <button class="btn-admin btn-admin-outline btn-admin-sm" onclick="editItem('practice',${a.id})">Edit</button>
        <button class="btn-admin btn-admin-danger btn-admin-sm" onclick="deleteItem('practice',${a.id})">Hapus</button>
      </td>
    </tr>`).join('');
}

// ===== ADMIN TEAM =====
async function loadAdminTeam() {
    const items = await apiGet('/team?all=1', true);
    const tbody = document.getElementById('team-tbody');
    tbody.innerHTML = (items || []).map(m => `
    <tr>
      <td style="font-weight:600;color:#fff">${esc(m.name)}</td>
      <td>${esc(m.position)}</td>
      <td style="max-width:200px">${trunc(m.education, 80)}</td>
      <td>${m.sort_order}</td>
      <td><span class="badge ${m.is_active ? 'badge-active' : 'badge-inactive'}">${m.is_active ? 'Aktif' : 'Nonaktif'}</span></td>
      <td>
        <button class="btn-admin btn-admin-outline btn-admin-sm" onclick="editItem('team',${m.id})">Edit</button>
        <button class="btn-admin btn-admin-danger btn-admin-sm" onclick="deleteItem('team',${m.id})">Hapus</button>
      </td>
    </tr>`).join('');
}

// ===== ADMIN TESTIMONIALS =====
async function loadAdminTestimonials() {
    const items = await apiGet('/testimonials?all=1', true);
    const tbody = document.getElementById('testimonials-tbody');
    tbody.innerHTML = (items || []).map(t => `
    <tr>
      <td style="font-weight:600;color:#fff">${esc(t.client_name)}</td>
      <td>${esc(t.client_company)}</td>
      <td style="max-width:200px">${trunc(t.content)}</td>
      <td>${'★'.repeat(parseInt(t.rating || 5))}</td>
      <td><span class="badge ${t.is_active ? 'badge-active' : 'badge-inactive'}">${t.is_active ? 'Aktif' : 'Nonaktif'}</span></td>
      <td>
        <button class="btn-admin btn-admin-outline btn-admin-sm" onclick="editItem('testimonials',${t.id})">Edit</button>
        <button class="btn-admin btn-admin-danger btn-admin-sm" onclick="deleteItem('testimonials',${t.id})">Hapus</button>
      </td>
    </tr>`).join('');
}

// ===== ADMIN CASES =====
async function loadAdminCases() {
    const items = await apiGet('/cases?all=1', true);
    const tbody = document.getElementById('cases-tbody');
    tbody.innerHTML = (items || []).map(c => `
    <tr>
      <td style="font-weight:600;color:#fff">${trunc(c.title, 50)}</td>
      <td>${esc(c.category)}</td>
      <td>${c.year}</td>
      <td>${c.is_featured ? '<span class="badge badge-active">Ya</span>' : 'Tidak'}</td>
      <td><span class="badge ${c.is_active ? 'badge-active' : 'badge-inactive'}">${c.is_active ? 'Aktif' : 'Nonaktif'}</span></td>
      <td>
        <button class="btn-admin btn-admin-outline btn-admin-sm" onclick="editItem('cases',${c.id})">Edit</button>
        <button class="btn-admin btn-admin-danger btn-admin-sm" onclick="deleteItem('cases',${c.id})">Hapus</button>
      </td>
    </tr>`).join('');
}

// ===== ADMIN NEWS =====
async function loadAdminNews() {
    const res   = await apiGet('/news?all=1', true);
    const items = res?.items || [];
    const tbody = document.getElementById('news-tbody');
    tbody.innerHTML = items.map(n => `
    <tr>
      <td style="font-weight:600;color:#fff;max-width:250px">${trunc(n.title, 50)}</td>
      <td>${esc(n.author)}</td>
      <td>${fmtDate(n.published_at || n.created_at)}</td>
      <td><span class="badge ${n.is_published ? 'badge-published' : 'badge-draft'}">${n.is_published ? 'Terbit' : 'Draft'}</span></td>
      <td>
        <button class="btn-admin btn-admin-outline btn-admin-sm" onclick="editItem('news',${n.id})">Edit</button>
        <button class="btn-admin btn-admin-danger btn-admin-sm" onclick="deleteItem('news',${n.id})">Hapus</button>
      </td>
    </tr>`).join('');
}

// ===== ADMIN CONTACTS =====
async function loadAdminContacts() {
    const items = await apiGet('/contacts', true);
    const tbody = document.getElementById('contacts-tbody');
    tbody.innerHTML = (items || []).map(c => `
    <tr>
      <td style="font-weight:600;color:#fff">${esc(c.name)}</td>
      <td>${esc(c.email)}</td>
      <td>${esc(c.subject || '-')}</td>
      <td style="max-width:200px">${trunc(c.message)}</td>
      <td>${fmtDate(c.created_at)}</td>
      <td><span class="badge ${c.is_read ? 'badge-read' : 'badge-unread'}">${c.is_read ? 'Dibaca' : 'Baru'}</span></td>
      <td>
        ${!c.is_read ? `<button class="btn-admin btn-admin-outline btn-admin-sm" onclick="markRead(${c.id})">Baca</button>` : ''}
        <button class="btn-admin btn-admin-danger btn-admin-sm" onclick="deleteContact(${c.id})">Hapus</button>
      </td>
    </tr>`).join('');
}

async function markRead(id) {
    await fetch(buildUrl('/contacts/' + id), { method: 'PUT', headers: { 'Authorization': 'Bearer ' + authToken } });
    loadAdminContacts();
}

async function deleteContact(id) {
    if (!confirm('Hapus pesan ini?')) return;
    await apiDelete('/contacts/' + id);
    loadAdminContacts();
}