// ============================================================
// config.js — Konfigurasi Global & Variabel Bersama
// ============================================================

const _pageDir = window.location.href.split('/').slice(0, -1).join('/');
const API_BASE  = _pageDir + '/backend/api.php';

let authToken = localStorage.getItem('fh_token');
let adminData = null;
let editingId = null;
let modalType = null;

document.addEventListener('DOMContentLoaded', function() {
  const segments = window.location.pathname.split('/').filter(Boolean);
  const filtered = segments.filter(s => !s.includes('.') && s !== 'articles');
  const sub      = filtered.length > 0 ? '/' + filtered[0] : '';
  const base     = window.location.origin + sub;

  document.querySelectorAll('a[href="/articles"]').forEach(function(el) {
    el.href = base + '/articles';
  });
});