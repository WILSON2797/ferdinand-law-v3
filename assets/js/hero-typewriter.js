document.addEventListener('DOMContentLoaded', () => {

    const titleEl    = document.getElementById('hero-title');
    const subtitleEl = document.getElementById('hero-subtitle');

    if (!titleEl || !subtitleEl) return;

    // ─────────────────────────────────────────────
    // Fungsi typewriter inti (tidak berubah)
    // ─────────────────────────────────────────────
    function randomSpeed(base) {
        return base + Math.random() * base * 0.5 - base * 0.15;
    }

    function typeChars(target, text, baseSpeed, cursorEl, onDone) {
        let i = 0;
        function next() {
            if (i >= text.length) {
                setTimeout(() => { if (onDone) onDone(); }, 200);
                return;
            }
            const ch = text[i];
            let delay = randomSpeed(baseSpeed);
            if (ch === ' ')  delay += 30;
            if (ch === ',')  delay += 100;
            if (ch === '.')  delay += 150;
            setTimeout(() => {
                target.insertBefore(document.createTextNode(ch), cursorEl);
                i++;
                next();
            }, delay);
        }
        next();
    }

    function createCursor() {
        const cursor = document.createElement('span');
        cursor.className = 'typewriter-cursor';
        return cursor;
    }

    function removeCursor(cursor) {
        cursor.style.transition = 'opacity 0.6s ease';
        cursor.style.opacity = '0';
        setTimeout(() => cursor.remove(), 600);
    }

    // ─────────────────────────────────────────────
    // Baca teks dari DOM (diisi oleh public.js)
    // ─────────────────────────────────────────────
    function getTitleLines() {
        // Ambil teks mentah dari innerHTML, pecah di <br>
        return titleEl.innerHTML
            .split(/<br\s*\/?>/i)
            .map(s => s.replace(/<[^>]+>/g, '').trim())   // strip tag <em> dll
            .filter(Boolean);
    }

    function getSubtitleText() {
        return subtitleEl.textContent.trim();
    }

    // ─────────────────────────────────────────────
    // Typewriter title
    // ─────────────────────────────────────────────
    function typeTitle(titleLines, onDone) {
        titleEl.innerHTML = '';
        titleEl.classList.add('typing-started');

        const cursor = createCursor();
        titleEl.appendChild(cursor);

        const line1 = titleLines[0] ?? '';
        const line2 = titleLines[1] ?? '';

        typeChars(titleEl, line1, 30, cursor, () => {
            setTimeout(() => {
                titleEl.insertBefore(document.createElement('br'), cursor);

                const em = document.createElement('em');
                em.style.opacity    = '0';
                em.style.transition = 'opacity 0.1s ease';
                titleEl.insertBefore(em, cursor);

                requestAnimationFrame(() => {
                    requestAnimationFrame(() => { em.style.opacity = '1'; });
                });

                em.appendChild(cursor);

                typeChars(em, line2, 31, cursor, () => {
                    removeCursor(cursor);
                    if (onDone) onDone();
                });
            }, 300);
        });
    }

    // ─────────────────────────────────────────────
    // Typewriter subtitle
    // ─────────────────────────────────────────────
    function typeSubtitle(text) {
        subtitleEl.innerHTML = '';
        subtitleEl.classList.add('typing-started');

        const cursor = createCursor();
        subtitleEl.appendChild(cursor);

        typeChars(subtitleEl, text, 22, cursor, () => {
            removeCursor(cursor);
        });
    }

    // ─────────────────────────────────────────────
    // Tunggu public.js selesai isi DOM, baru mulai
    //
    // public.js memanggil loadSiteData() yang async
    // (fetch ke /settings). Kita poll elemen sampai
    // isinya berubah dari placeholder HTML awal,
    // atau fallback ke teks awal jika timeout.
    // ─────────────────────────────────────────────
    const POLL_INTERVAL = 80;   // ms
    const POLL_TIMEOUT  = 5000; // ms — fallback setelah 5 detik

    const initialTitle    = titleEl.innerHTML;
    const initialSubtitle = subtitleEl.textContent;
    let   elapsed         = 0;

    const poll = setInterval(() => {
        elapsed += POLL_INTERVAL;

        const titleReady    = titleEl.innerHTML    !== initialTitle;
        const subtitleReady = subtitleEl.textContent !== initialSubtitle;
        const timedOut      = elapsed >= POLL_TIMEOUT;

        if (titleReady || subtitleReady || timedOut) {
            clearInterval(poll);

            const titleLines   = getTitleLines();
            const subtitleText = getSubtitleText();

            setTimeout(() => {
                typeTitle(titleLines, () => {
                    setTimeout(() => typeSubtitle(subtitleText), 350);
                });
            }, 400); // sedikit jeda setelah data siap
        }
    }, POLL_INTERVAL);

});