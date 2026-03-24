<!-- ====== GALLERY ====== -->
<section id="gallery">

  <div class="gallery-bg-deco">
    <div class="gbg-line"></div>
    <div class="gbg-line"></div>
    <div class="gbg-line"></div>
    <div class="gbg-orb"></div>
  </div>

  <div class="gallery-header" data-animate>
    <div class="section-label">Galeri</div>
    <h2 class="section-title light">Momen &amp; Kegiatan<br><em>Firma Kami</em></h2>
    <p class="gallery-subtitle">Dokumentasi perjalanan, pencapaian, dan aktivitas Ferdinand Hutahaean &amp; Co</p>
  </div>

  <div class="gallery-meta" data-animate>
    <span class="gallery-counter">
      <span id="gallery-current">01</span>
      <span class="gallery-counter-sep">/</span>
      <span id="gallery-total">00</span>
    </span>
    <div class="gallery-progress-bar">
      <div class="gallery-progress-fill" id="gallery-progress"></div>
    </div>
    <div class="gallery-arrows">
      <button class="gallery-arrow-btn" id="galleryPrev" aria-label="Sebelumnya">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="15,18 9,12 15,6"/></svg>
      </button>
      <button class="gallery-arrow-btn" id="galleryNext" aria-label="Berikutnya">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="9,6 15,12 9,18"/></svg>
      </button>
    </div>
  </div>

  <!-- Carousel Stage -->
  <div class="gallery-stage" data-animate>
    <div class="gallery-track" id="galleryTrack">
      <div class="gallery-card skeleton-card"></div>
      <div class="gallery-card skeleton-card"></div>
      <div class="gallery-card skeleton-card"></div>
    </div>
  </div>

  <!-- Dot Navigation -->
  <div class="gallery-dots" id="galleryDots" data-animate></div>

  <!-- Empty state -->
  <div class="gallery-empty" id="galleryEmpty" style="display:none">
    <div class="gallery-empty-icon">🖼️</div>
    <p>Belum ada foto yang tersedia</p>
  </div>

  <!-- ── Lightbox ── -->
  <div class="gallery-lightbox" id="galleryLightbox" aria-hidden="true">
    <div class="lightbox-backdrop" id="lightboxBackdrop"></div>
    <div class="lightbox-panel">
      <button class="lightbox-close" id="lightboxClose" aria-label="Tutup">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
      <button class="lightbox-nav lb-prev" id="lightboxPrev" aria-label="Sebelumnya">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="15,18 9,12 15,6"/></svg>
      </button>
      <div class="lightbox-img-wrap">
        <div class="lightbox-shimmer" id="lightboxShimmer"></div>
        <img id="lightboxImg" src="" alt="" draggable="false" />
      </div>
      <button class="lightbox-nav lb-next" id="lightboxNext" aria-label="Berikutnya">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="9,6 15,12 9,18"/></svg>
      </button>
      <div class="lightbox-info">
        <p class="lightbox-desc" id="lightboxDesc"></p>
        <span class="lightbox-date" id="lightboxDate"></span>
      </div>
    </div>
  </div>

</section>