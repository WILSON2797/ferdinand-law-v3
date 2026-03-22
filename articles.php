<?php
// articles.php — Halaman Semua Artikel
// Letakkan di root folder (sejajar index.php)
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Berita & Artikel — Ferdinand Hutahaean & Co</title>

  <!-- Google Fonts (sama seperti head.php) -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="assets/css/article.css" />
</head>
<body>

<!-- ====== NAVBAR (identik dengan navbar.php) ====== -->
<?php include 'partials/navbar.php'; ?>

<!-- ====== PAGE HERO ====== -->
<section class="articles-hero">
  <div class="articles-hero-inner">
    <div class="breadcrumb">
      <a href="/ferdinand-law-v3/">Beranda</a>
      <span class="breadcrumb-sep">›</span>
      <span>Berita & Artikel</span>
    </div>
    <div class="section-label">Informasi Terkini</div>
    <h1>Berita & <em>Artikel</em></h1>
    <div class="articles-hero-divider"></div>
    <p>Kumpulan artikel hukum, analisis kasus, dan informasi terkini seputar dunia hukum dari tim Ferdinand Hutahaean & Co.</p>
  </div>
</section>

<!-- ====== TOOLBAR ====== -->
<div class="articles-toolbar">
  <div class="articles-search-wrap">
    <span class="articles-search-icon">🔍</span>
    <input
      type="text"
      id="articleSearch"
      placeholder="Cari artikel..."
      oninput="handleSearch(this.value)"
    />
  </div>
  <div class="articles-count" id="articlesCount">Memuat artikel...</div>
</div>

<!-- ====== MAIN CONTENT ====== -->
<main class="articles-main">
  <div class="news-grid" id="articlesGrid">
    <!-- Skeleton loading -->
    <?php for ($i = 0; $i < 6; $i++): ?>
    <div class="skeleton-card">
      <div class="skeleton-thumb"></div>
      <div class="skeleton-body">
        <div class="skeleton-line short"></div>
        <div class="skeleton-line full"></div>
        <div class="skeleton-line medium"></div>
        <div class="skeleton-line short" style="margin-top:1.5rem"></div>
      </div>
    </div>
    <?php endfor; ?>
  </div>
  <div id="articlesPagination"></div>
</main>

<!-- ====== FOOTER ====== -->
<?php include 'partials/footer.php'; ?>

<!-- ====== ARTICLE DETAIL MODAL (sama dengan modals.php) ====== -->
<div class="article-modal-bg" id="articleModal" onclick="if(event.target===this)closeArticle()">
  <div class="article-modal">
    <div class="article-modal-header">
      <button class="article-modal-close" onclick="closeArticle()">✕</button>
    </div>
    <div class="article-modal-content">
      <div class="article-modal-meta" id="article-modal-meta"></div>
      <h2 class="article-modal-title" id="article-modal-title"></h2>
      <div id="article-modal-img"></div>
      <div class="article-modal-body" id="article-modal-body"></div>
    </div>
  </div>
</div>

<!-- ====== BACK TO TOP ====== -->
<button id="backToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})" title="Kembali ke atas">↑</button>

<!-- ====== SCRIPTS ====== -->
<script src="assets/js/config.js"></script>
<script src="assets/js/utils.js"></script>
<script>
// ── IMAGE URL HELPER (sama dengan public.js) ──
const _appBase = (() => {
  const origin   = window.location.origin;
  const segments = window.location.pathname.split('/').filter(Boolean);
  // Abaikan "articles" dari segmen terakhir
  const filtered = segments.filter(s => !s.includes('.') && s !== 'articles');
  const sub      = filtered.length > 0 ? '/' + filtered[0] : '';
  return origin + sub;
})();

function imgUrl(path) {
  if (!path) return '';
  if (path.startsWith('http')) return path;
  if (path.startsWith('/uploads/')) return _appBase + path;
  return _appBase + '/uploads/' + path;
}

// ── NAVBAR SCROLL ──
window.addEventListener('scroll', () => {
  document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
  document.getElementById('backToTop').classList.toggle('visible', window.scrollY > 400);
});

// ── MOBILE MENU ──
function toggleMobileMenu() {
  document.getElementById('mobileMenu').classList.toggle('open');
  document.querySelector('.hamburger').classList.toggle('open');
}

// ── STATE ──
let _page       = 1;
const _limit    = 9;          // 9 per halaman (grid 3 kolom)
let _total      = 0;
let _searchQ    = '';
let _searchTimer;

// ── RENDER CARDS ──
const newsIcons = ['⚖️','📋','🏛️','💼','🌐','📑'];

function renderCards(items) {
  const grid = document.getElementById('articlesGrid');
  if (!items.length) {
    grid.innerHTML = `
      <div class="articles-empty">
        <div class="articles-empty-icon">📭</div>
        <h3>Belum Ada Artikel</h3>
        <p>Artikel hukum akan segera hadir. Nantikan informasi terbaru dari kami.</p>
      </div>`;
    return;
  }

  grid.innerHTML = items.map((n, i) => {
    const icon     = newsIcons[i % newsIcons.length];
    const imageUrl = imgUrl(n.image);
    const imgEl    = imageUrl
      ? `<img class="news-thumb-img" src="${esc(imageUrl)}" alt="" onerror="this.style.display='none'">`
      : '';
    return `
      <div class="news-card" onclick="openArticle(${n.id})" style="cursor:pointer">
        <div class="news-thumb">
          ${imgEl}
          <div class="news-thumb-icon">${icon}</div>
        </div>
        <div class="news-body">
          <div class="news-cat">Artikel Hukum</div>
          <h3 class="news-title">${esc(n.title)}</h3>
          <div class="news-meta">
            <span>✍ ${esc(n.author || 'Admin')}</span>
            <span>📅 ${fmtDate(n.published_at)}</span>
          </div>
          <div style="margin-top:1rem"><span class="news-read-more">Baca Selengkapnya →</span></div>
        </div>
      </div>`;
  }).join('');
}

// ── FETCH PAGE ──
async function fetchPage() {
  const grid = document.getElementById('articlesGrid');

  // Tampilkan skeleton
  grid.innerHTML = Array(6).fill(`
    <div class="skeleton-card">
      <div class="skeleton-thumb"></div>
      <div class="skeleton-body">
        <div class="skeleton-line short"></div>
        <div class="skeleton-line full"></div>
        <div class="skeleton-line medium"></div>
        <div class="skeleton-line short" style="margin-top:1.5rem"></div>
      </div>
    </div>`).join('');

  const offset = (_page - 1) * _limit;
  let url      = `/news?limit=${_limit}&offset=${offset}`;
  if (_searchQ) url += `&q=${encodeURIComponent(_searchQ)}`;

  const res   = await apiGet(url);
  const items = res?.items || [];
  _total      = res?.total  || 0;

  renderCards(items);
  renderPagination();
  updateCount();
}

// ── PAGINATION ──
function renderPagination() {
  const totalPages = Math.ceil(_total / _limit);
  const wrap       = document.getElementById('articlesPagination');
  if (totalPages <= 1) { wrap.innerHTML = ''; return; }

  let html = '<div class="news-pagination">';
  html += `<button class="news-page-btn" onclick="goPage(${_page - 1})" ${_page === 1 ? 'disabled' : ''}>← Sebelumnya</button>`;

  // Tampilkan maks 7 tombol halaman
  const delta = 2;
  for (let p = 1; p <= totalPages; p++) {
    if (p === 1 || p === totalPages || (p >= _page - delta && p <= _page + delta)) {
      html += `<button class="news-page-btn ${p === _page ? 'active' : ''}" onclick="goPage(${p})">${p}</button>`;
    } else if (p === _page - delta - 1 || p === _page + delta + 1) {
      html += `<button class="news-page-btn" disabled>…</button>`;
    }
  }

  html += `<button class="news-page-btn" onclick="goPage(${_page + 1})" ${_page === totalPages ? 'disabled' : ''}>Berikutnya →</button>`;
  html += '</div>';
  wrap.innerHTML = html;
}

function goPage(p) {
  const totalPages = Math.ceil(_total / _limit);
  if (p < 1 || p > totalPages) return;
  _page = p;
  window.scrollTo({ top: 0, behavior: 'smooth' });
  fetchPage();
}

// ── COUNT LABEL ──
function updateCount() {
  const el    = document.getElementById('articlesCount');
  const start = (_page - 1) * _limit + 1;
  const end   = Math.min(_page * _limit, _total);
  el.innerHTML = _total
    ? `Menampilkan <strong>${start}–${end}</strong> dari <strong>${_total}</strong> artikel`
    : 'Tidak ada artikel ditemukan';
}

// ── SEARCH ──
function handleSearch(val) {
  clearTimeout(_searchTimer);
  _searchTimer = setTimeout(() => {
    _searchQ = val.trim();
    _page    = 1;
    fetchPage();
  }, 400);
}

// ── OPEN / CLOSE ARTICLE MODAL ──
async function openArticle(id) {
  const n = await apiGet('/news/' + id);
  if (!n || n.error) return;
  const imageUrl = imgUrl(n.image);
  document.getElementById('article-modal-title').textContent = n.title || '';
  document.getElementById('article-modal-meta').innerHTML =
    `<span>✍ ${esc(n.author || 'Admin')}</span> &nbsp;·&nbsp; <span>📅 ${fmtDate(n.published_at)}</span>`;
  document.getElementById('article-modal-img').innerHTML = imageUrl
    ? `<img src="${esc(imageUrl)}" style="width:100%;max-height:320px;object-fit:cover;margin-bottom:1.5rem" onerror="this.style.display='none'">`
    : '';
  document.getElementById('article-modal-body').innerHTML = formatArticleContent(n.content || '');
  document.getElementById('articleModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeArticle() {
  document.getElementById('articleModal').classList.remove('open');
  document.body.style.overflow = '';
}

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeArticle();
});

// ── INIT ──
fetchPage();
</script>

</body>
</html>