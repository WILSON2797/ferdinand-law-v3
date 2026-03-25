// ============================================================
// modal.js — Modal Tambah/Edit Konten Admin
// ============================================================

function openModal(type, data = null) {
    modalType = type;
    editingId = data?.id || null;
    document.getElementById('modal-title').textContent = (editingId ? 'Edit' : 'Tambah') + ' ' + {
        practice: 'Area Praktik', team: 'Anggota Tim', testimonials: 'Testimoni', cases: 'Kasus', news: 'Artikel', gallery: 'Foto Galeri'
    }[type];

    const bodies = {
        practice: `
      <div class="admin-field"><label>Icon (emoji)</label><input id="m-icon" value="${esc(data?.icon || '')}" placeholder="⚖️" /></div>
      <div class="admin-field"><label>Judul</label><input id="m-title" value="${esc(data?.title || '')}" /></div>
      <div class="admin-field"><label>Deskripsi</label><textarea id="m-description">${esc(data?.description || '')}</textarea></div>
      <div class="admin-grid-2">
        <div class="admin-field"><label>Urutan</label><input type="number" id="m-sort_order" value="${data?.sort_order || 0}" /></div>
        <div class="admin-field"><label>Status</label>
          <select id="m-is_active"><option value="1" ${data?.is_active != 0 ? 'selected' : ''}>Aktif</option><option value="0" ${data?.is_active == 0 ? 'selected' : ''}>Nonaktif</option></select>
        </div>
      </div>`,
        team: `
      <div class="admin-field"><label>Nama Lengkap</label><input id="m-name" value="${esc(data?.name || '')}" /></div>
      <div class="admin-field"><label>Jabatan</label><input id="m-position" value="${esc(data?.position || '')}" /></div>
      <div class="admin-field"><label>Pendidikan</label><textarea id="m-education">${esc(data?.education || '')}</textarea></div>
      <div class="admin-field">
        <label>Foto</label>
        ${data?.photo ? `<div style="margin-bottom:8px"><img src="${esc(imgUrl(data.photo))}" style="width:80px;height:80px;object-fit:cover;border:2px solid var(--gold)" onerror="this.style.display='none'"></div>` : ''}
        <input type="file" id="m-photo-file" accept="image/jpeg,image/png,image/webp" style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:var(--white);padding:10px 14px;font-size:0.82rem;font-family:var(--font-sans);outline:none;cursor:pointer" />
        <input type="hidden" id="m-photo" value="${esc(data?.photo || '')}" />
        <div id="m-photo-preview" style="margin-top:8px;display:none"><img id="m-photo-preview-img" style="width:80px;height:80px;object-fit:cover;border:2px solid var(--gold)" /></div>
        <div id="m-photo-status" style="font-size:0.7rem;margin-top:6px;color:rgba(255,255,255,0.4)"></div>
      </div>
      <div class="admin-grid-2">
        <div class="admin-field"><label>Urutan</label><input type="number" id="m-sort_order" value="${data?.sort_order || 0}" /></div>
        <div class="admin-field"><label>Status</label>
          <select id="m-is_active"><option value="1" ${data?.is_active != 0 ? 'selected' : ''}>Aktif</option><option value="0" ${data?.is_active == 0 ? 'selected' : ''}>Nonaktif</option></select>
        </div>
      </div>`,
        testimonials: `
      <div class="admin-grid-2">
        <div class="admin-field"><label>Nama Klien</label><input id="m-client_name" value="${esc(data?.client_name || '')}" /></div>
        <div class="admin-field"><label>Perusahaan/Jabatan</label><input id="m-client_company" value="${esc(data?.client_company || '')}" /></div>
      </div>
      <div class="admin-field"><label>Isi Testimoni</label><textarea id="m-content">${esc(data?.content || '')}</textarea></div>
      <div class="admin-grid-2">
        <div class="admin-field"><label>Rating (1-5)</label><input type="number" min="1" max="5" id="m-rating" value="${data?.rating || 5}" /></div>
        <div class="admin-field"><label>Status</label>
          <select id="m-is_active"><option value="1" ${data?.is_active != 0 ? 'selected' : ''}>Aktif</option><option value="0" ${data?.is_active == 0 ? 'selected' : ''}>Nonaktif</option></select>
        </div>
      </div>`,
        cases: `
      <div class="admin-field"><label>Judul Kasus</label><input id="m-title" value="${esc(data?.title || '')}" /></div>
      <div class="admin-grid-2">
        <div class="admin-field"><label>Kategori</label><input id="m-category" value="${esc(data?.category || '')}" /></div>
        <div class="admin-field"><label>Tahun</label><input type="number" id="m-year" value="${data?.year || new Date().getFullYear()}" /></div>
      </div>
      <div class="admin-field"><label>Deskripsi</label><textarea id="m-description">${esc(data?.description || '')}</textarea></div>
      <div class="admin-field"><label>Outcome/Hasil</label><input id="m-outcome" value="${esc(data?.outcome || '')}" /></div>
      <div class="admin-grid-2">
        <div class="admin-field"><label>Unggulan</label>
          <select id="m-is_featured"><option value="0" ${!data?.is_featured ? 'selected' : ''}>Tidak</option><option value="1" ${data?.is_featured ? 'selected' : ''}>Ya</option></select>
        </div>
        <div class="admin-field"><label>Status</label>
          <select id="m-is_active"><option value="1" ${data?.is_active != 0 ? 'selected' : ''}>Aktif</option><option value="0" ${data?.is_active == 0 ? 'selected' : ''}>Nonaktif</option></select>
        </div>
      </div>`,
        news: `
      <div class="admin-field"><label>Judul Artikel</label><input id="m-title" value="${esc(data?.title || '')}" /></div>
      <div class="admin-field"><label>Penulis</label><input id="m-author" value="${esc(data?.author || 'Admin')}" /></div>
      <div class="admin-field"><label>Ringkasan Singkat</label><textarea id="m-excerpt" style="min-height:70px" placeholder="Tulis ringkasan artikel...">${esc(data?.excerpt || '')}</textarea></div>
      <div class="admin-field">
        <label>Isi Artikel <span style="color:rgba(255,255,255,0.3);font-weight:400;text-transform:none;letter-spacing:0">(tulis biasa, enter untuk paragraf baru)</span></label>
        <textarea id="m-content" style="min-height:220px;line-height:1.8" placeholder="Tulis isi artikel di sini...&#10;&#10;Tekan Enter 2x untuk paragraf baru.">${esc(plainFromHtml(data?.content || ''))}</textarea>
      </div>
      <div class="admin-field">
        <label>Foto Artikel</label>
        ${data?.image ? `<div style="margin-bottom:8px"><img src="${esc(imgUrl(data.image))}" style="width:100%;max-height:120px;object-fit:cover;border:2px solid var(--gold)" onerror="this.style.display='none'"></div>` : ''}
        <input type="file" id="m-image-file" accept="image/jpeg,image/png,image/webp,image/gif" style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:var(--white);padding:10px 14px;font-size:0.82rem;font-family:var(--font-sans);outline:none;cursor:pointer" />
        <input type="hidden" id="m-image" value="${esc(data?.image || '')}" />
        <div id="m-image-preview" style="margin-top:8px;display:none"><img id="m-image-preview-img" style="width:100%;max-height:120px;object-fit:cover;border:2px solid var(--gold)" /></div>
        <div id="m-image-status" style="font-size:0.7rem;margin-top:6px;color:rgba(255,255,255,0.4)"></div>
      </div>
      <div class="admin-field"><label>Status</label>
        <select id="m-is_published"><option value="0" ${!data?.is_published ? 'selected' : ''}>Draft</option><option value="1" ${data?.is_published ? 'selected' : ''}>Terbit</option></select>
      </div>`,
        gallery: `
      <div class="admin-field">
        <label>Upload Foto</label>
        ${data?.image_url ? `<div style="margin-bottom:8px"><img src="${esc(imgUrl(data.image_url))}" style="width:100%;max-height:160px;object-fit:cover;border-radius:8px;border:1px solid rgba(201,168,76,0.3)" onerror="this.style.display='none'"></div>` : ''}
        <input type="file" id="m-gallery-file" accept="image/jpeg,image/png,image/webp" style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:var(--white);padding:10px 14px;font-size:0.82rem;font-family:var(--font-sans);outline:none;cursor:pointer" />
        <input type="hidden" id="m-image_url" value="${esc(data?.image_url || '')}" />
        <div id="m-gallery-preview" style="margin-top:8px;display:none"><img id="m-gallery-preview-img" style="width:100%;max-height:160px;object-fit:cover;border-radius:8px;border:1px solid rgba(201,168,76,0.3)" /></div>
        <div id="m-gallery-status" style="font-size:0.7rem;margin-top:6px;color:rgba(255,255,255,0.4)"></div>
      </div>
      <div class="admin-field"><label>Deskripsi</label><textarea id="m-description" placeholder="Keterangan foto...">${esc(data?.description || '')}</textarea></div>
      <div class="admin-field"><label>Tanggal</label><input type="date" id="m-date" value="${esc((data?.date || new Date().toISOString()).substring(0, 10))}" /></div>`
    };

    document.getElementById('modal-body').innerHTML = `
    ${bodies[type]}
    <div style="display:flex;gap:1rem;margin-top:1.5rem;padding-top:1rem;border-top:1px solid rgba(255,255,255,0.06)">
      <button class="btn-admin" onclick="saveModal()">💾 Simpan</button>
      <button class="btn-admin btn-admin-outline" onclick="closeModal()">Batal</button>
    </div>`;
    document.getElementById('modalBg').classList.add('open');

    // Preview foto (khusus team)
    if (type === 'team') {
        const fileInput = document.getElementById('m-photo-file');
        if (fileInput) {
            fileInput.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;
                const preview    = document.getElementById('m-photo-preview');
                const previewImg = document.getElementById('m-photo-preview-img');
                const reader     = new FileReader();
                reader.onload    = e => { previewImg.src = e.target.result; preview.style.display = 'block'; };
                reader.readAsDataURL(file);
                document.getElementById('m-photo-status').textContent = '📎 ' + file.name;
            });
        }
    }

    // Preview gambar artikel (khusus news)
    if (type === 'news') {
        const fileInput = document.getElementById('m-image-file');
        if (fileInput) {
            fileInput.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;
                const preview    = document.getElementById('m-image-preview');
                const previewImg = document.getElementById('m-image-preview-img');
                const reader     = new FileReader();
                reader.onload    = e => { previewImg.src = e.target.result; preview.style.display = 'block'; };
                reader.readAsDataURL(file);
                document.getElementById('m-image-status').textContent = '📎 ' + file.name;
            });
        }
    }

    // Preview foto gallery
    if (type === 'gallery') {
        const fileInput = document.getElementById('m-gallery-file');
        if (fileInput) {
            fileInput.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;
                const preview    = document.getElementById('m-gallery-preview');
                const previewImg = document.getElementById('m-gallery-preview-img');
                const reader     = new FileReader();
                reader.onload    = e => { previewImg.src = e.target.result; preview.style.display = 'block'; };
                reader.readAsDataURL(file);
                document.getElementById('m-gallery-status').textContent = '📎 ' + file.name;
            });
        }
    }
}

async function editItem(type, id) {
    const endpoints = { practice: '/practice-areas/', team: '/team/', testimonials: '/testimonials/', cases: '/cases/', news: '/news/', gallery: '/gallery/' };
    const data = await apiGet(endpoints[type] + id, true);
    openModal(type, data);
    editingId = id;
}

function closeModal() {
    document.getElementById('modalBg').classList.remove('open');
    editingId = null; modalType = null;
}

function getModalData() {
    const g    = id => { const el = document.getElementById(id); return el ? el.value : undefined; };
    const maps = {
        practice:     { icon: 'm-icon', title: 'm-title', description: 'm-description', sort_order: 'm-sort_order', is_active: 'm-is_active' },
        team:         { name: 'm-name', position: 'm-position', education: 'm-education', photo: 'm-photo', sort_order: 'm-sort_order', is_active: 'm-is_active' },
        testimonials: { client_name: 'm-client_name', client_company: 'm-client_company', content: 'm-content', rating: 'm-rating', is_active: 'm-is_active' },
        cases:        { title: 'm-title', category: 'm-category', year: 'm-year', description: 'm-description', outcome: 'm-outcome', is_featured: 'm-is_featured', is_active: 'm-is_active' },
        news:         { title: 'm-title', author: 'm-author', excerpt: 'm-excerpt', content: 'm-content', image: 'm-image', is_published: 'm-is_published' },
        gallery:      { image_url: 'm-image_url', description: 'm-description', date: 'm-date' }
    };
    const data = {};
    Object.entries(maps[modalType] || {}).forEach(([key, elId]) => { const v = g(elId); if (v !== undefined) data[key] = v; });
    return data;
}

async function saveModal() {
    // Upload foto team
    if (modalType === 'team') {
        const fileInput = document.getElementById('m-photo-file');
        if (fileInput && fileInput.files[0]) {
            const url = await uploadFile(fileInput.files[0], 'm-photo-status');
            if (!url) return;
            document.getElementById('m-photo').value = url;
        }
    }

    // Upload gambar artikel news + konversi plain→HTML
    if (modalType === 'news') {
        const fileInput = document.getElementById('m-image-file');
        if (fileInput && fileInput.files[0]) {
            const url = await uploadFile(fileInput.files[0], 'm-image-status');
            if (!url) return;
            document.getElementById('m-image').value = url;
        }
        const contentEl = document.getElementById('m-content');
        if (contentEl) contentEl.value = htmlFromPlain(contentEl.value);
    }

    // Upload foto gallery
    if (modalType === 'gallery') {
        const fileInput = document.getElementById('m-gallery-file');
        if (fileInput && fileInput.files[0]) {
            const url = await uploadFile(fileInput.files[0], 'm-gallery-status');
            if (!url) return;
            document.getElementById('m-image_url').value = url;
        }
    }

    const data      = getModalData();
    const endpoints = { practice: '/practice-areas', team: '/team', testimonials: '/testimonials', cases: '/cases', news: '/news', gallery: '/gallery' };
    const ep        = endpoints[modalType];
    let res;
    if (editingId) res = await apiPost(ep + '/' + editingId, data, 'PUT');
    else           res = await apiPost(ep, data, 'POST');

    if (res && !res.error) {
        showToast('Berhasil disimpan!');
        closeModal();
        const loaders = { practice: loadAdminPractice, team: loadAdminTeam, testimonials: loadAdminTestimonials, cases: loadAdminCases, news: loadAdminNews, gallery: loadAdminGallery };
        if (loaders[modalType]) loaders[modalType]();
        if (modalType === 'practice')     loadPractice();
        if (modalType === 'team')         { loadTeam(); setTimeout(() => location.reload(), 1200); }
        if (modalType === 'testimonials') loadTestimonials();
        if (modalType === 'news')         loadNews();
        if (modalType === 'gallery')      { if (typeof loadGallery === 'function') loadGallery(); }
    } else {
        showToast('Gagal menyimpan: ' + (res?.error || 'Cek koneksi server'), true);
    }
}

async function deleteItem(type, id) {
    if (!confirm('Yakin ingin menghapus item ini?')) return;
    const endpoints = { practice: '/practice-areas/', team: '/team/', testimonials: '/testimonials/', cases: '/cases/', news: '/news/', gallery: '/gallery/' };
    const res = await apiDelete(endpoints[type] + id);
    if (res && !res.error) {
        showToast('Berhasil dihapus');
        const loaders = { practice: loadAdminPractice, team: loadAdminTeam, testimonials: loadAdminTestimonials, cases: loadAdminCases, news: loadAdminNews, gallery: loadAdminGallery };
        if (loaders[type]) loaders[type]();
        if (type === 'practice')     loadPractice();
        if (type === 'team')         loadTeam();
        if (type === 'testimonials') loadTestimonials();
        if (type === 'news')         loadNews();
        if (type === 'gallery')      { if (typeof loadGallery === 'function') loadGallery(); }
    } else {
        showToast('Gagal hapus: ' + (res?.error || ''), true);
    }
}