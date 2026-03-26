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

    <!-- Google Maps -->
      <div class="contact-map" style="margin-top:2rem;border-radius:12px;overflow:hidden;border:1px solid rgba(201,168,76,0.2);position:relative;">
    
        <!-- Link klik (BENAR) -->
        <a href="https://www.google.com/maps/search/?api=1&query=FERDINAND+HUTAHAEAN+%26+Co+Advocates+dan+Legal+Consultan"
          target="_blank"
          rel="noopener noreferrer"
          title="Buka di Google Maps"
          style="position:absolute;top:0;left:0;width:100%;height:100%;z-index:2;">
        </a>

        <!-- Iframe -->
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.5122633843234!2d106.87062279999999!3d-6.1959386!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f572405890bd%3A0xe3a78dac1e4ad8f5!2sFERDINAND%20HUTAHAEAN%20%26%20Co%20Advocates%20dan%20Legal%20Consultan!5e0!3m2!1sid!2sid!4v1774485176996!5m2!1sid!2sid"
          width="100%"
          height="200"
          style="border:0;display:block;"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          title="Lokasi Ferdinand Hutahaean & CO">
        </iframe>

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