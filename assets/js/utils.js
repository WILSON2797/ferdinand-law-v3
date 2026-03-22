// ============================================================
// utils.js — Fungsi Utilitas & Helper
// ============================================================

function showToast(msg, isError = false) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = 'toast' + (isError ? ' error' : '');
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3500);
}

function buildUrl(path) {
    const [route, qs] = path.split('?');
    return API_BASE + '?route=' + encodeURIComponent(route) + (qs ? '&' + qs : '');
}

async function apiGet(url, auth = false) {
    const headers = { 'Content-Type': 'application/json' };
    if (auth && authToken) headers['Authorization'] = 'Bearer ' + authToken;
    try {
        const r = await fetch(buildUrl(url), { headers });
        return await r.json();
    } catch (e) {
        return null;
    }
}

async function apiPost(url, body, method = 'POST') {
    const headers = { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + authToken };
    try {
        const r = await fetch(buildUrl(url), { method, headers, body: JSON.stringify(body) });
        return await r.json();
    } catch (e) {
        return { error: e.message };
    }
}

async function apiDelete(url) {
    const headers = { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + authToken };
    try {
        const r = await fetch(buildUrl(url), { method: 'DELETE', headers });
        return await r.json();
    } catch (e) {
        return { error: e.message };
    }
}

function esc(s) {
    return s ? s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;') : '';
}

function trunc(s, n = 60) {
    return s && s.length > n ? s.substr(0, n) + '...' : (s || '');
}

function fmtDate(d) {
    if (!d) return '-';
    return new Date(d).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}

function plainFromHtml(html) {
    return html
        .replace(/<h[1-6][^>]*>(.*?)<\/h[1-6]>/gi, '## $1\n\n')
        .replace(/<p[^>]*>(.*?)<\/p>/gi, '$1\n\n')
        .replace(/<br\s*\/?>/gi, '\n')
        .replace(/<li[^>]*>(.*?)<\/li>/gi, '- $1\n')
        .replace(/<[^>]+>/g, '')
        .replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&nbsp;/g, ' ')
        .replace(/\n{3,}/g, '\n\n')
        .trim();
}

function htmlFromPlain(text) {
    if (!text) return '';
    return text
        .split(/\n\n+/)
        .map(block => {
            block = block.trim();
            if (!block) return '';
            if (block.startsWith('## ')) return `<h2>${block.slice(3)}</h2>`;
            if (block.startsWith('# '))  return `<h1>${block.slice(2)}</h1>`;
            return `<p>${block.replace(/\n/g, '<br>')}</p>`;
        })
        .filter(Boolean)
        .join('\n');
}

function formatArticleContent(html) {
    if (!html) return '';
    if (!/<[a-z][\s\S]*>/i.test(html)) return htmlFromPlain(html);
    return html;
}

async function uploadFile(file, statusElId) {
    const statusEl = document.getElementById(statusElId);
    const allowed  = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
    if (!allowed.includes(file.type)) { showToast('Format tidak didukung', true); return null; }
    if (file.size > 5 * 1024 * 1024) { showToast('Ukuran file maksimal 5MB', true); return null; }

    if (statusEl) { statusEl.textContent = '⏳ Mengupload...'; statusEl.style.color = 'var(--gold)'; }

    const formData = new FormData();
    formData.append('file', file);
    try {
        const r = await fetch(API_BASE + '?route=' + encodeURIComponent('/upload'), {
            method: 'POST',
            headers: { 'Authorization': 'Bearer ' + authToken },
            body: formData
        });
        const result = await r.json();
        if (result.url) {
            if (statusEl) { statusEl.textContent = '✓ Berhasil diupload'; statusEl.style.color = '#50c878'; }
            return result.url;
        } else {
            if (statusEl) { statusEl.textContent = '✗ ' + (result.error || 'Gagal upload'); statusEl.style.color = '#ff6b6b'; }
            return null;
        }
    } catch (e) {
        if (statusEl) { statusEl.textContent = '✗ Error: ' + e.message; statusEl.style.color = '#ff6b6b'; }
        return null;
    }
}