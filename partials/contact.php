<!-- ====== CONTACT ====== -->
<section id="contact">
  <div class="contact-info" data-animate>
    <div class="section-label">Hubungi Kami</div>
    <h2 class="section-title light">Konsultasikan<br>Masalah Hukum Anda</h2>
    <p style="font-size:0.85rem;color:rgba(255,255,255,0.5);line-height:1.9;margin-bottom:2rem">
      Tim kami siap membantu Anda dengan konsultasi awal secara gratis. Jangan ragu untuk menghubungi kami.
    </p>
    <div class="contact-detail">
      <div class="contact-icon">📍</div>
      <div class="contact-detail-text">
        <strong>Alamat</strong>
        <span id="c-address">Jl. Sudirman No. 123, Jakarta Selatan 12190</span>
      </div>
    </div>
    <div class="contact-detail">
      <div class="contact-icon">📞</div>
      <div class="contact-detail-text">
        <strong>Telepon</strong>
        <span id="c-phone">+62 21 1234 5678</span>
      </div>
    </div>
    <div class="contact-detail">
      <div class="contact-icon">✉️</div>
      <div class="contact-detail-text">
        <strong>Email</strong>
        <span id="c-email">info@ferdinandlaw.com</span>
      </div>
    </div>
    <div class="contact-detail">
      <div class="contact-icon">🕐</div>
      <div class="contact-detail-text">
        <strong>Jam Operasional</strong>
        <span id="c-hours">Senin - Jumat: 08:00 - 17:00 WIB</span>
      </div>
    </div>
    <div class="contact-socials">
      <a href="#" class="social-btn" id="whatsapp-btn" title="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
      <a href="#" class="social-btn" id="linkedin-btn" title="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
      <a href="#" class="social-btn" id="instagram-btn" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
    </div>
  </div>
  <div class="contact-form" data-animate>
    <div class="section-label" style="color:rgba(201,168,76,0.7)">Formulir Konsultasi</div>
    <h3 style="font-family:var(--font-serif);font-size:1.6rem;color:var(--white);font-weight:300;margin-bottom:2rem">Kirim Pesan</h3>
    <div class="form-row">
      <div class="form-group">
        <label>Nama Lengkap *</label>
        <input type="text" id="form-name" placeholder="Masukkan nama Anda" />
      </div>
      <div class="form-group">
        <label>Email *</label>
        <input type="email" id="form-email" placeholder="email@domain.com" />
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Telepon</label>
        <input type="tel" id="form-phone" placeholder="+62 ..." />
      </div>
      <div class="form-group">
        <label>Bidang Hukum</label>
        <select id="form-subject">
          <option value="">Pilih bidang...</option>
          <option>Hukum Korporasi</option>
          <option>Litigasi & Arbitrase</option>
          <option>Hukum Ketenagakerjaan</option>
          <option>Hukum Properti</option>
          <option>Hukum Bisnis</option>
          <option>Hukum Internasional</option>
          <option>Lainnya</option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label>Pesan *</label>
      <textarea id="form-message" placeholder="Deskripsikan singkat permasalahan hukum Anda..."></textarea>
    </div>
    <button class="btn-primary" onclick="submitContact()" style="width:100%;justify-content:center">
      Kirim Pesan →
    </button>
    <div id="form-success" style="display:none" class="form-success">
      ✓ Pesan berhasil dikirim! Tim kami akan menghubungi Anda segera.
    </div>
  </div>
</section>