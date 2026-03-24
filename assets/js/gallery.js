/* ============================================================
   GALLERY JS — Ferdinand Hutahaean & Co
   Fetch dari API, swipe/drag, lightbox, animasi
   ============================================================ */

(function () {
  'use strict';

  /* ── State ── */
  let items        = [];   // data dari API
  let currentIndex = 0;   // index card pertama yang terlihat
  let lbIndex      = 0;   // index lightbox
  let visibleCount = 3;   // jumlah card terlihat
  let isDragging   = false;
  let dragStartX   = 0;
  let dragDelta    = 0;
  let autoplayTimer = null;

  /* ── DOM refs ── */
  const track      = document.getElementById('galleryTrack');
  const dotsWrap   = document.getElementById('galleryDots');
  const btnPrev    = document.getElementById('galleryPrev');
  const btnNext    = document.getElementById('galleryNext');
  const elCurrent  = document.getElementById('gallery-current');
  const elTotal    = document.getElementById('gallery-total');
  const elProgress = document.getElementById('gallery-progress');
  const emptyState = document.getElementById('galleryEmpty');
  const stage      = document.querySelector('.gallery-stage');

  /* Lightbox */
  const lightbox   = document.getElementById('galleryLightbox');
  const lbBackdrop = document.getElementById('lightboxBackdrop');
  const lbImg      = document.getElementById('lightboxImg');
  const lbDesc     = document.getElementById('lightboxDesc');
  const lbDate     = document.getElementById('lightboxDate');
  const lbShimmer  = document.getElementById('lightboxShimmer');
  const lbClose    = document.getElementById('lightboxClose');
  const lbPrev     = document.getElementById('lightboxPrev');
  const lbNext     = document.getElementById('lightboxNext');

  /* ── Responsive: berapa card yang terlihat ── */
  function getVisibleCount() {
    if (window.innerWidth <= 640)  return 1;
    if (window.innerWidth <= 1024) return 2;
    return 3;
  }

  /* ── Format tanggal ── */
  function formatDate(str) {
    if (!str) return '';
    try {
      const d = new Date(str);
      return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    } catch { return str; }
  }

  /* ── Update counter + progress bar ── */
  function updateMeta() {
    const maxIndex = Math.max(0, items.length - visibleCount);
    const idx = Math.min(currentIndex, maxIndex);
    const pct = items.length <= visibleCount ? 100 : (idx / maxIndex) * 100;

    elCurrent.textContent  = String(idx + 1).padStart(2, '0');
    elTotal.textContent    = String(items.length).padStart(2, '0');
    elProgress.style.width = pct + '%';

    btnPrev.disabled = idx === 0;
    btnNext.disabled = idx >= maxIndex;

    /* Dots */
    document.querySelectorAll('.gallery-dot').forEach((dot, i) => {
      dot.classList.toggle('active', i === idx);
    });
  }

  /* ── Slide ke index ── */
  function slideTo(idx, animate = true) {
    const maxIndex = Math.max(0, items.length - visibleCount);
    currentIndex = Math.max(0, Math.min(idx, maxIndex));

    const cardW = track.querySelector('.gallery-card')?.offsetWidth || 0;
    const gap   = 24; // 1.5rem
    const offset = currentIndex * (cardW + gap);

    if (!animate) track.classList.add('no-transition');
    track.style.transform = `translateX(-${offset}px)`;
    if (!animate) requestAnimationFrame(() => track.classList.remove('no-transition'));

    updateMeta();
  }

  /* ── Build dots ── */
  function buildDots() {
    dotsWrap.innerHTML = '';
    const total = Math.max(1, items.length - visibleCount + 1);
    for (let i = 0; i < total; i++) {
      const dot = document.createElement('button');
      dot.className = 'gallery-dot' + (i === 0 ? ' active' : '');
      dot.setAttribute('aria-label', `Slide ${i + 1}`);
      dot.addEventListener('click', () => { slideTo(i); resetAutoplay(); });
      dotsWrap.appendChild(dot);
    }
  }

  /* ── Render cards ── */
  function renderCards() {
    track.innerHTML = '';
    if (!items.length) {
      emptyState.style.display = 'block';
      return;
    }
    emptyState.style.display = 'none';

    items.forEach((item, i) => {
      const card = document.createElement('div');
      card.className = 'gallery-card entering';
      card.style.animationDelay = (i % visibleCount) * 0.08 + 's';

      card.innerHTML = `
        <div class="gallery-card-img-wrap">
          <img
            class="gallery-card-img"
            src="${escHtml(item.image_url || item.image || '')}"
            alt="${escHtml(item.description || 'Foto galeri')}"
            loading="lazy"
          />
          <div class="gallery-card-overlay">
            <div class="gallery-zoom-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <circle cx="11" cy="11" r="7"/>
                <line x1="16.5" y1="16.5" x2="22" y2="22"/>
                <line x1="11" y1="8" x2="11" y2="14"/>
                <line x1="8" y1="11" x2="14" y2="11"/>
              </svg>
            </div>
          </div>
        </div>
        <div class="gallery-card-body">
          <div class="gallery-card-date">${escHtml(formatDate(item.date || item.created_at))}</div>
          <p class="gallery-card-desc">${escHtml(item.description || '')}</p>
        </div>
      `;

      card.addEventListener('click', () => openLightbox(i));
      track.appendChild(card);
    });

    buildDots();
    updateMeta();
  }

  /* ── HTML escape ── */
  function escHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  /* ── Fetch dari API ── */
  async function loadGallery() {
    try {
      const res  = await fetch(`${API}/gallery`);
      const json = await res.json();
      items = Array.isArray(json) ? json : (json.data || json.items || []);
      visibleCount = getVisibleCount();
      renderCards();
      startAutoplay();
    } catch (err) {
      console.error('Gallery fetch error:', err);
      /* Fallback: kosongkan skeleton dan tampilkan empty */
      track.innerHTML = '';
      emptyState.style.display = 'block';
    }
  }

  /* ── Autoplay ── */
  function startAutoplay() {
    clearInterval(autoplayTimer);
    if (items.length <= visibleCount) return;
    autoplayTimer = setInterval(() => {
      const maxIndex = items.length - visibleCount;
      slideTo(currentIndex >= maxIndex ? 0 : currentIndex + 1);
    }, 4500);
  }
  function resetAutoplay() {
    clearInterval(autoplayTimer);
    startAutoplay();
  }

  /* ── Arrow buttons ── */
  btnPrev.addEventListener('click', () => { slideTo(currentIndex - 1); resetAutoplay(); });
  btnNext.addEventListener('click', () => { slideTo(currentIndex + 1); resetAutoplay(); });

  /* ── Keyboard navigation ── */
  document.addEventListener('keydown', (e) => {
    if (lightbox.classList.contains('active')) {
      if (e.key === 'ArrowLeft')  lbNavigate(-1);
      if (e.key === 'ArrowRight') lbNavigate(1);
      if (e.key === 'Escape')     closeLightbox();
      return;
    }
    if (e.key === 'ArrowLeft')  { slideTo(currentIndex - 1); resetAutoplay(); }
    if (e.key === 'ArrowRight') { slideTo(currentIndex + 1); resetAutoplay(); }
  });

  /* ── Drag / Swipe ── */
  function pointerStart(x) {
    isDragging = true;
    dragStartX = x;
    dragDelta  = 0;
    track.classList.add('no-transition');
    clearInterval(autoplayTimer);
  }
  function pointerMove(x) {
    if (!isDragging) return;
    dragDelta = x - dragStartX;
    const cardW = track.querySelector('.gallery-card')?.offsetWidth || 0;
    const gap   = 24;
    const base  = currentIndex * (cardW + gap);
    track.style.transform = `translateX(${-base + dragDelta}px)`;
  }
  function pointerEnd() {
    if (!isDragging) return;
    isDragging = false;
    track.classList.remove('no-transition');
    const threshold = 60;
    if (dragDelta < -threshold) slideTo(currentIndex + 1);
    else if (dragDelta > threshold) slideTo(currentIndex - 1);
    else slideTo(currentIndex); // snap back
    startAutoplay();
  }

  /* Mouse */
  stage.addEventListener('mousedown',  (e) => pointerStart(e.clientX));
  window.addEventListener('mousemove', (e) => pointerMove(e.clientX));
  window.addEventListener('mouseup',   () => pointerEnd());

  /* Touch */
  stage.addEventListener('touchstart', (e) => pointerStart(e.touches[0].clientX), { passive: true });
  stage.addEventListener('touchmove',  (e) => pointerMove(e.touches[0].clientX),  { passive: true });
  stage.addEventListener('touchend',   () => pointerEnd());

  /* Prevent click after drag */
  stage.addEventListener('click', (e) => {
    if (Math.abs(dragDelta) > 10) e.stopPropagation();
  }, true);

  /* ── Lightbox ── */
  function openLightbox(idx) {
    lbIndex = idx;
    updateLightbox();
    lightbox.classList.add('active');
    lightbox.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }
  function closeLightbox() {
    lightbox.classList.remove('active');
    lightbox.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }
  function updateLightbox() {
    const item = items[lbIndex];
    if (!item) return;
    lbShimmer.classList.add('active');
    lbImg.style.opacity = '0';
    lbImg.src = item.image_url || item.image || '';
    lbImg.alt = item.description || '';
    lbImg.onload = () => {
      lbShimmer.classList.remove('active');
      lbImg.style.opacity = '1';
    };
    lbDesc.textContent = item.description || '';
    lbDate.textContent = formatDate(item.date || item.created_at);
    lbPrev.disabled = lbIndex === 0;
    lbNext.disabled = lbIndex === items.length - 1;
  }
  function lbNavigate(dir) {
    const next = lbIndex + dir;
    if (next < 0 || next >= items.length) return;
    lbIndex = next;
    updateLightbox();
  }

  lbClose.addEventListener('click',    closeLightbox);
  lbBackdrop.addEventListener('click', closeLightbox);
  lbPrev.addEventListener('click',     () => lbNavigate(-1));
  lbNext.addEventListener('click',     () => lbNavigate(1));

  /* ── Resize: rebuild ── */
  let resizeTimer;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
      const newVisible = getVisibleCount();
      if (newVisible !== visibleCount) {
        visibleCount = newVisible;
        renderCards();
        slideTo(currentIndex, false);
        startAutoplay();
      }
    }, 250);
  });

  /* ── Init ── */
  loadGallery();

})();