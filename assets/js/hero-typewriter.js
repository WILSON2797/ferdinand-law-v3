document.addEventListener('DOMContentLoaded', () => {

    const titleLines = ['Excellence in ', 'Legal Advocacy'];
    const subtitleText = 'Memberikan nasihat dan representasi hukum terpercaya dengan integritas, keahlian, dan dedikasi penuh kepada keadilan untuk setiap klien kami.';

    const titleEl    = document.getElementById('hero-title');
    const subtitleEl = document.getElementById('hero-subtitle');

    if (!titleEl || !subtitleEl) return;

    function randomSpeed(base) {
        return base + Math.random() * base * 0.5 - base * 0.15;
    }

    function typeChars(target, text, baseSpeed, cursorEl, onDone) {
        let i = 0;

        function next() {
            if (i >= text.length) {
                setTimeout(() => {
                    if (onDone) onDone();
                }, 200);
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

    function typeTitle(onDone) {
        titleEl.innerHTML = '';
        titleEl.classList.add('typing-started');

        const cursor = createCursor();
        titleEl.appendChild(cursor);

        /* Baris pertama */
        typeChars(titleEl, titleLines[0], 50, cursor, () => {

            setTimeout(() => {
                titleEl.insertBefore(document.createElement('br'), cursor);

                /* <em> untuk baris kedua */
                const em = document.createElement('em');
                em.style.opacity = '0';
                em.style.transition = 'opacity 0.2s ease';
                titleEl.insertBefore(em, cursor);

                requestAnimationFrame(() => {
                    requestAnimationFrame(() => { em.style.opacity = '1'; });
                });

                /* cursor dipindah ke dalam em agar posisi tepat */
                em.appendChild(cursor);

                typeChars(em, titleLines[1], 55, cursor, () => {
                    removeCursor(cursor);
                    if (onDone) onDone();
                });

            }, 300);
        });
    }

    function typeSubtitle() {
        subtitleEl.innerHTML = '';
        subtitleEl.classList.add('typing-started');

        const cursor = createCursor();
        subtitleEl.appendChild(cursor);

        typeChars(subtitleEl, subtitleText, 22, cursor, () => {
            removeCursor(cursor);
        });
    }

    setTimeout(() => {
        typeTitle(() => {
            setTimeout(typeSubtitle, 350);
        });
    }, 1400);

});