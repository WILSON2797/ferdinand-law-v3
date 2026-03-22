<!-- ====== NEWS ====== -->
<section id="news">
  <div class="news-header" data-animate>
    <div>
      <div class="section-label">Informasi Terkini</div>
      <h2 class="section-title">Berita & Artikel</h2>
    </div>
    <!-- Tombol sekarang redirect ke halaman /articles, bukan buka modal -->
    <a href="/ferdinand-law-v3/articles" class="btn-primary" style="font-size:0.65rem;padding:12px 24px;text-decoration:none">Semua Artikel →</a>
  </div>
  <div class="news-grid" id="newsGrid">
    <!-- Loaded dynamically -->
  </div>
</section>

<!-- ====== MODAL BACA SELENGKAPNYA (tetap ada untuk preview homepage) ====== -->
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