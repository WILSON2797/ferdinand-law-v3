// ============================================================
// config.js — Konfigurasi Global & Variabel Bersama
// ============================================================

const _pageDir = window.location.href.split('/').slice(0, -1).join('/');
const API_BASE  = _pageDir + '/backend/api.php';

let authToken = localStorage.getItem('fh_token');
let adminData = null;
let editingId = null;
let modalType = null;