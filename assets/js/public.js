// ============================================================
// public.js — Konten Publik (Halaman Utama)
// ============================================================

// ===== IMAGE URL HELPER =====
const _appBase = (() => {
    const origin   = window.location.origin;
    const segments = window.location.pathname.split('/').filter(Boolean);
    const sub      = segments.length > 0 && !segments[0].includes('.') ? '/' + segments[0] : '';
    return origin + sub;
})();

function imgUrl(path) {
    if (!path) return '';
    if (path.startsWith('http')) return path;
    if (path.startsWith('/uploads/')) return _appBase + path;
    return _appBase + '/uploads/' + path;
}

// ===== NAVBAR SCROLL =====
window.addEventListener('scroll', () => {
    document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
});

// ===== MOBILE MENU =====
function toggleMobileMenu() {
    document.getElementById('mobileMenu').classList.toggle('open');
    document.querySelector('.hamburger').classList.toggle('open');
}

// ===== SCROLL ANIMATIONS =====
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.style.opacity = '1';
            e.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('[data-animate]').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'all 0.8s cubic-bezier(0.25,0.46,0.45,0.94)';
    observer.observe(el);
});

// ===== LOAD SITE DATA =====
async function loadSiteData() {
    const s = await apiGet('/settings');
    if (!s) return;

    if (s.hero_title) {
    const parts = s.hero_title.split('|').map(s => s.trim());
    const html  = parts.length > 1
        ? `${parts[0]}<br><em>${parts[1]}</em>`
        : parts[0];
    document.getElementById('hero-title').innerHTML = html;
}
    if (s.hero_subtitle) document.getElementById('hero-subtitle').textContent = s.hero_subtitle;

    if (s.about_title)   document.getElementById('about-title').textContent = s.about_title;
    if (s.about_content) document.getElementById('about-content').textContent = s.about_content;
    if (s.about_vision)  document.getElementById('about-vision').textContent = '"' + s.about_vision + '"';

    if (s.stats_cases) {
        document.getElementById('stat-cases').textContent = s.stats_cases;
        document.getElementById('s-cases').textContent = s.stats_cases;
    }
    if (s.stats_clients) {
        document.getElementById('stat-clients').textContent = s.stats_clients;
        document.getElementById('s-clients').textContent = s.stats_clients;
    }
    if (s.stats_years) {
        document.getElementById('stat-years').textContent = s.stats_years;
        document.getElementById('stat-years-badge').textContent = s.stats_years;
        document.getElementById('s-years').textContent = s.stats_years;
    }

    startCounters(s);

    if (s.stats_lawyers) document.getElementById('s-lawyers').textContent = s.stats_lawyers;

    if (s.address)      document.getElementById('c-address').textContent = s.address;
    if (s.phone)        document.getElementById('c-phone').textContent = s.phone;
    if (s.email)        document.getElementById('c-email').textContent = s.email;
    if (s.office_hours) document.getElementById('c-hours').textContent = s.office_hours;
    if (s.whatsapp)     document.getElementById('whatsapp-btn').href = 'https://wa.me/' + s.whatsapp.replace(/[^0-9]/g, '');
    if (s.linkedin)     document.getElementById('linkedin-btn').href = s.linkedin;
    if (s.instagram)    document.getElementById('instagram-btn').href = s.instagram;

    if (s.footer_text) document.getElementById('footer-copy').textContent = s.footer_text;
    if (s.site_name) {
        document.getElementById('footer-brand').textContent = s.site_name;
        document.title = s.site_name + ' — Advocates & Legal Consultants';
    }
}

// ===== LOAD PRACTICE AREAS =====
async function loadPractice() {
    const areas = await apiGet('/practice-areas');
    const grid  = document.getElementById('practiceGrid');
    if (!areas || !areas.length) {
        grid.innerHTML = '<div style="color:rgba(255,255,255,0.3);padding:3rem;text-align:center">Tidak ada data</div>';
        return;
    }
    grid.innerHTML = areas.map((a, i) => `
    <div class="practice-card" data-animate style="opacity:0;transform:translateY(30px);transition:all 0.6s ${0.1 * i}s cubic-bezier(0.25,0.46,0.45,0.94)">
      <div class="practice-card-num">0${i + 1}</div>
      <span class="practice-card-icon">${esc(a.icon)}</span>
      <h3 class="practice-card-title">${esc(a.title)}</h3>
      <p class="practice-card-desc">${esc(a.description)}</p>
    </div>
    `).join('');
    grid.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));
}

// ===== LOAD TEAM =====
async function loadTeam() {
    const members = await apiGet('/team');
    const grid    = document.getElementById('teamGrid');
    if (!members || !members.length) {
        grid.innerHTML = '<p style="color:var(--gray)">Tidak ada data tim</p>';
        return;
    }
    grid.innerHTML = members.map(m => {
        const initials = m.name.split(' ').map(w => w[0]).slice(0, 2).join('');
        const hasPhoto = !!m.photo;
        const photoUrl = imgUrl(m.photo);
        const photoEl  = hasPhoto
            ? `<img class="team-photo-img" src="${esc(photoUrl)}" alt="${esc(m.name)}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">`
            : '';
        return `
    <div class="team-card" data-animate>
      <div class="team-photo">
        ${photoEl}
        <div class="team-photo-inner" style="${hasPhoto ? 'display:none' : ''}">
          <div class="team-photo-avatar">${initials}</div>
        </div>
        <div class="team-overlay">
          <div class="team-edu">${esc(m.education || '')}</div>
        </div>
      </div>
      <div class="team-info">
        <div class="team-name">${esc(m.name)}</div>
        <div class="team-pos">${esc(m.position)}</div>
      </div>
    </div>`;
    }).join('');
    grid.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));
}

// ===== LOAD CASES =====
const _casesPreview = 4; // 1 featured + 3 reguler ditampilkan awal
let   _casesExpanded = false;
 
async function loadCases() {
    const items = await apiGet('/cases');
    const grid  = document.getElementById('casesGrid');
    if (!items || !items.length) {
        grid.innerHTML = '<div class="cases-empty">Belum ada data kasus.</div>';
        return;
    }
 
    // Pisahkan featured & reguler
    const featured = items.filter(c => c.is_featured == 1);
    const regular  = items.filter(c => c.is_featured != 1);
 
    // Gabung: featured duluan, lalu reguler
    const sorted = [...featured, ...regular];
 
    grid.innerHTML = sorted.map((c, i) => {
        const isFeatured = i === 0 && c.is_featured == 1;
        const isHidden   = i >= _casesPreview;
        const cardClass  = [
            'case-card',
            isFeatured ? 'featured' : '',
            isHidden   ? 'hidden-case' : ''
        ].filter(Boolean).join(' ');
 
        const yearEl = c.year
            ? `<div class="case-year">${esc(String(c.year))}</div>`
            : '';
 
        const descEl = c.description
            ? `<p class="case-desc">${esc(c.description)}</p>`
            : '';
 
        const badgeEl = isFeatured
            ? `<div class="case-featured-badge">★ Kasus Unggulan</div>`
            : `<div class="case-number">0${i + 1}</div>`;
 
        return `
        <div class="${cardClass}" data-animate>
            ${badgeEl}
            ${yearEl}
            <h3 class="case-title">${esc(c.title)}</h3>
            ${descEl}
        </div>`;
    }).join('');
 
    // Observ animasi
    grid.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));
 
    // Tampilkan tombol "lihat semua" jika lebih dari preview
    const moreWrap = document.getElementById('casesMoreWrap');
    if (sorted.length > _casesPreview) {
        moreWrap.style.display = 'block';
    }
}
 
function toggleAllCases() {
    _casesExpanded = !_casesExpanded;
    document.querySelectorAll('.case-card.hidden-case').forEach(el => {
        el.classList.toggle('visible', _casesExpanded);
    });
    const btn = document.getElementById('casesMoreBtn');
    btn.textContent = _casesExpanded ? '← Sembunyikan' : 'Lihat Semua Kasus →';
}

// ===== LOAD TESTIMONIALS =====
async function loadTestimonials() {
    const items = await apiGet('/testimonials');
    const grid  = document.getElementById('testimonialsGrid');
    if (!items || !items.length) {
        grid.innerHTML = '<p style="color:rgba(255,255,255,0.3)">Tidak ada testimoni</p>';
        return;
    }
    grid.innerHTML = items.map(t => `
    <div class="testimonial-card" data-animate>
      <div class="testimonial-quote">"</div>
      <p class="testimonial-text">${esc(t.content)}</p>
      <div class="testimonial-stars">${'★'.repeat(parseInt(t.rating || 5))}</div>
      <div class="testimonial-client">
        <strong>${esc(t.client_name)}</strong>
        <span>${esc(t.client_company)}</span>
      </div>
    </div>`).join('');
    grid.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));
}

// ===== LOAD NEWS (preview 6 di homepage) =====
const newsIcons = ['⚖️', '📋', '🏛️', '💼', '🌐', '📑'];
 
function renderNewsCards(items, container, startIndex = 0) {
    container.innerHTML += items.map((n, i) => {
        const idx      = startIndex + i;
        const imageUrl = imgUrl(n.image);
 
        // Kalau ada foto: container menyesuaikan ukuran foto
        // Kalau tidak ada foto: tampilkan icon placeholder dengan fixed height
        const thumbEl = imageUrl
            ? `<div class="news-thumb-auto">
                 <img class="news-thumb-img-auto" src="${imageUrl}" alt=""
                   onerror="this.parentElement.innerHTML='<div class=&quot;news-thumb-fallback&quot;>${newsIcons[idx % newsIcons.length]}</div>'">
               </div>`
            : `<div class="news-thumb-fallback">${newsIcons[idx % newsIcons.length]}</div>`;
 
        return `
    <div class="news-card" onclick="openArticle(${n.id})" style="cursor:pointer">
      ${thumbEl}
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

async function loadNews() {
    const res   = await apiGet('/news?limit=6');
    const grid  = document.getElementById('newsGrid');
    const items = res?.items || [];
    if (!items.length) {
        grid.innerHTML = '<p style="color:var(--gray)">Belum ada artikel</p>';
        return;
    }
    grid.innerHTML = '';
    renderNewsCards(items, grid, 0);
}

// ===== TESTIMONIAL FORM (PUBLIC) =====
let _currentRating = 0;

function openTestimonialForm() {
    // Reset form
    ['tf-name','tf-company','tf-content'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('tf-rating').value = '0';
    document.getElementById('tf-error').style.display   = 'none';
    document.getElementById('tf-success').style.display = 'none';
    document.getElementById('tf-submit-btn').style.display = 'block';
    _currentRating = 0;
    updateStars(0);

    const modal = document.getElementById('testimonialFormModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeTestimonialForm() {
    document.getElementById('testimonialFormModal').style.display = 'none';
    document.body.style.overflow = '';
}

function setRating(val) {
    _currentRating = val;
    document.getElementById('tf-rating').value = val;
    updateStars(val);
}

function hoverRating(val) {
    updateStars(val || _currentRating);
}

function updateStars(val) {
    document.querySelectorAll('#tf-stars button').forEach(btn => {
        const n = parseInt(btn.dataset.val);
        btn.style.color     = n <= val ? 'var(--gold)' : 'rgba(255,255,255,0.2)';
        btn.style.transform = n <= val ? 'scale(1.15)' : 'scale(1)';
    });
}

async function submitTestimonial() {
    const name    = document.getElementById('tf-name').value.trim();
    const company = document.getElementById('tf-company').value.trim();
    const content = document.getElementById('tf-content').value.trim();
    const rating  = parseInt(document.getElementById('tf-rating').value);
    const errEl   = document.getElementById('tf-error');

    // Validasi
    errEl.style.display = 'none';
    if (!name) {
        errEl.textContent = 'Nama lengkap wajib diisi.';
        errEl.style.display = 'block'; return;
    }
    if (!rating || rating < 1) {
        errEl.textContent = 'Mohon pilih rating bintang.';
        errEl.style.display = 'block'; return;
    }
    if (!content || content.length < 10) {
        errEl.textContent = 'Testimoni minimal 10 karakter.';
        errEl.style.display = 'block'; return;
    }

    // Disable tombol saat loading
    const btn = document.getElementById('tf-submit-btn');
    btn.textContent = 'Mengirim...';
    btn.disabled    = true;
    btn.style.opacity = '0.7';

    const res = await apiPost('/testimonials', {
        client_name:    name,
        client_company: company,
        content:        content,
        rating:         rating,
        is_active:      1
    });

    btn.disabled  = false;
    btn.style.opacity = '1';
    btn.textContent   = 'Kirim Testimoni';

    if (res && !res.error) {
        document.getElementById('tf-success').style.display = 'block';
        btn.style.display = 'none';
        loadTestimonials(); // refresh tampilan
    } else {
        errEl.textContent   = 'Gagal mengirim. Silakan coba lagi.';
        errEl.style.display = 'block';
    }
}

// ===== CONTACT FORM =====
async function submitContact() {
    const name    = document.getElementById('form-name').value.trim();
    const email   = document.getElementById('form-email').value.trim();
    const phone   = document.getElementById('form-phone').value.trim();
    const subject = document.getElementById('form-subject').value;
    const message = document.getElementById('form-message').value.trim();
    if (!name || !email || !message) {
        showToast('Mohon isi nama, email, dan pesan', true);
        return;
    }
    const res = await apiPost('/contacts', { name, email, phone, subject, message });
    if (res && !res.error) {
        document.getElementById('form-success').style.display = 'block';
        ['form-name', 'form-email', 'form-phone', 'form-message'].forEach(id => document.getElementById(id).value = '');
        document.getElementById('form-subject').value = '';
    } else {
        showToast('Gagal mengirim pesan. Coba lagi.', true);
    }
}

// ===== BACA SELENGKAPNYA (modal detail artikel di homepage) =====
async function openArticle(id) {
    const n = await apiGet('/news/' + id);
    if (!n || n.error) return;

    const imageUrl = imgUrl(n.image);

    const modal = document.getElementById('articleModal');
    document.getElementById('article-modal-title').textContent = n.title || '';
    document.getElementById('article-modal-meta').innerHTML =
        `<span>✍ ${esc(n.author || 'Admin')}</span> &nbsp;·&nbsp; <span>📅 ${fmtDate(n.published_at)}</span>`;
    document.getElementById('article-modal-img').innerHTML = imageUrl
        ? `<img src="${imageUrl}" style="width:100%;max-height:320px;object-fit:cover;margin-bottom:1.5rem" onerror="this.style.display='none'">`
        : '';
    document.getElementById('article-modal-body').innerHTML = formatArticleContent(n.content || '');
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeArticle() {
    document.getElementById('articleModal').classList.remove('open');
    document.body.style.overflow = '';
}

// ===== HERO STATS COUNTER ANIMATION =====
function animateCounter(element, target, duration = 2000) {
  if (!element || target <= 0) return;
  let start = 0;
  const increment = target / (duration / 16); // ~60fps
  const suffix = '+';
  const timer = setInterval(() => {
    start += increment;
    if (start >= target) {
      start = target;
      clearInterval(timer);
    }
    element.textContent = Math.floor(start) + suffix;
  }, 16);
}

function startCounters(s) {
  const parseNum = str => parseInt((str ?? '').replace(/\D/g, '')) || 0;
  animateCounter(document.getElementById('stat-cases'),   parseNum(s.stats_cases),   2000);
  animateCounter(document.getElementById('stat-clients'), parseNum(s.stats_clients), 2000);
  animateCounter(document.getElementById('stat-years'),   parseNum(s.stats_years),   1500);
}

