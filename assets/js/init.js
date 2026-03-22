// ============================================================
// init.js — Inisialisasi Aplikasi
// ============================================================

(async function init() {
    await loadSiteData();
    await Promise.all([loadPractice(), loadCases(), loadTeam(), loadTestimonials(), loadNews()]);

    // Auto-login jika token tersimpan
    if (authToken) {
        const me = await apiGet('/auth/me', true);
        if (me && !me.error) {
            adminData = me;
            document.getElementById('admin-username').textContent = me.name || me.username;
        } else {
            authToken = null;
            localStorage.removeItem('fh_token');
        }
    }
})();