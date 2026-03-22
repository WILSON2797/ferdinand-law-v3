<!-- ====== TESTIMONIALS ====== -->
<section id="testimonials">
  <div class="testimonials-header" data-animate>
    <div class="section-label">Kepercayaan Klien</div>
    <h2 class="section-title light">Kata Mereka</h2>
    <p class="section-desc" style="color:rgba(255,255,255,0.45);margin:0 auto">Kepercayaan dan kepuasan klien adalah prioritas utama kami dalam setiap penanganan kasus.</p>
    <!-- Tombol buka form testimoni -->
    <button
      onclick="openTestimonialForm()"
      style="
        margin-top:2rem;
        display:inline-flex;align-items:center;gap:10px;
        background:transparent;
        color:var(--gold);
        border:1px solid rgba(201,168,76,0.4);
        padding:12px 28px;
        font-family:var(--font-sans);
        font-size:0.65rem;font-weight:600;
        letter-spacing:2px;text-transform:uppercase;
        cursor:pointer;
        transition:all 0.3s;
      "
      onmouseover="this.style.background='rgba(201,168,76,0.1)';this.style.borderColor='var(--gold)'"
      onmouseout="this.style.background='transparent';this.style.borderColor='rgba(201,168,76,0.4)'"
    >✍ Tulis Testimoni Anda</button>
  </div>
  <div class="testimonials-grid" id="testimonialsGrid">
    <!-- Loaded dynamically -->
  </div>
</section>

<!-- ====== MODAL FORM TESTIMONI PUBLIK ====== -->
<div id="testimonialFormModal" onclick="if(event.target===this)closeTestimonialForm()" style="
  display:none;
  position:fixed;inset:0;
  z-index:9999;
  background:rgba(10,22,40,0.92);
  backdrop-filter:blur(6px);
  align-items:center;justify-content:center;
  padding:2rem 1rem;
  overflow-y:auto;
">
  <div style="
    background:var(--navy-mid);
    border:1px solid rgba(201,168,76,0.2);
    width:100%;max-width:520px;
    margin:auto;
    position:relative;
    animation: heroFadeUp 0.4s cubic-bezier(0.25,0.46,0.45,0.94) both;
  ">
    <!-- Header -->
    <div style="
      padding:1.75rem 2rem 1.25rem;
      border-bottom:1px solid rgba(201,168,76,0.12);
      display:flex;align-items:center;justify-content:space-between;
    ">
      <div>
        <div class="section-label" style="font-size:0.58rem;margin-bottom:0.4rem">Kepercayaan Klien</div>
        <h3 style="font-family:var(--font-serif);font-size:1.4rem;color:var(--white);font-weight:300;margin:0">
          Tulis Testimoni
        </h3>
      </div>
      <button onclick="closeTestimonialForm()" style="
        width:36px;height:36px;border-radius:50%;
        background:rgba(255,255,255,0.06);
        border:1px solid rgba(255,255,255,0.1);
        color:rgba(255,255,255,0.6);
        font-size:1rem;cursor:pointer;
        display:flex;align-items:center;justify-content:center;
        transition:all 0.2s;
      "
      onmouseover="this.style.background='rgba(201,168,76,0.15)';this.style.color='var(--gold)'"
      onmouseout="this.style.background='rgba(255,255,255,0.06)';this.style.color='rgba(255,255,255,0.6)'"
      >✕</button>
    </div>

    <!-- Body -->
    <div style="padding:1.75rem 2rem">

      <!-- Nama & Perusahaan -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem">
        <div>
          <label style="display:block;font-size:0.6rem;color:var(--gold);letter-spacing:2px;text-transform:uppercase;font-weight:600;margin-bottom:6px">
            Nama Lengkap <span style="color:#ff6b6b">*</span>
          </label>
          <input id="tf-name" type="text" placeholder="John Doe"
            style="width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:var(--white);padding:10px 14px;font-family:var(--font-sans);font-size:0.82rem;outline:none;transition:border-color 0.2s"
            onfocus="this.style.borderColor='var(--gold)'"
            onblur="this.style.borderColor='rgba(255,255,255,0.1)'"
          />
        </div>
        <div>
          <label style="display:block;font-size:0.6rem;color:var(--gold);letter-spacing:2px;text-transform:uppercase;font-weight:600;margin-bottom:6px">
            Perusahaan / Jabatan
          </label>
          <input id="tf-company" type="text" placeholder="PT. Contoh / Direktur"
            style="width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:var(--white);padding:10px 14px;font-family:var(--font-sans);font-size:0.82rem;outline:none;transition:border-color 0.2s"
            onfocus="this.style.borderColor='var(--gold)'"
            onblur="this.style.borderColor='rgba(255,255,255,0.1)'"
          />
        </div>
      </div>

      <!-- Rating bintang -->
      <div style="margin-bottom:1rem">
        <label style="display:block;font-size:0.6rem;color:var(--gold);letter-spacing:2px;text-transform:uppercase;font-weight:600;margin-bottom:10px">
          Rating <span style="color:#ff6b6b">*</span>
        </label>
        <div id="tf-stars" style="display:flex;gap:6px">
          <?php for ($i = 1; $i <= 5; $i++): ?>
          <button
            type="button"
            data-val="<?= $i ?>"
            onclick="setRating(<?= $i ?>)"
            style="
              background:none;border:none;cursor:pointer;
              font-size:1.6rem;line-height:1;padding:2px;
              color:rgba(255,255,255,0.2);
              transition:color 0.15s, transform 0.15s;
            "
            onmouseover="hoverRating(<?= $i ?>)"
            onmouseout="hoverRating(0)"
          >★</button>
          <?php endfor; ?>
        </div>
        <input type="hidden" id="tf-rating" value="0" />
      </div>

      <!-- Isi testimoni -->
      <div style="margin-bottom:1.5rem">
        <label style="display:block;font-size:0.6rem;color:var(--gold);letter-spacing:2px;text-transform:uppercase;font-weight:600;margin-bottom:6px">
          Testimoni <span style="color:#ff6b6b">*</span>
        </label>
        <textarea id="tf-content" rows="4" placeholder="Ceritakan pengalaman Anda bekerja sama dengan kami..."
          style="width:100%;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:var(--white);padding:10px 14px;font-family:var(--font-sans);font-size:0.82rem;outline:none;resize:vertical;min-height:110px;transition:border-color 0.2s"
          onfocus="this.style.borderColor='var(--gold)'"
          onblur="this.style.borderColor='rgba(255,255,255,0.1)'"
        ></textarea>
      </div>

      <!-- Error message -->
      <div id="tf-error" style="display:none;background:rgba(255,80,80,0.1);border:1px solid rgba(255,80,80,0.3);color:#ff9999;padding:10px 14px;font-size:0.75rem;margin-bottom:1rem;"></div>

      <!-- Success message -->
      <div id="tf-success" style="display:none;background:rgba(201,168,76,0.1);border:1px solid rgba(201,168,76,0.3);color:var(--gold);padding:14px;font-size:0.82rem;text-align:center;margin-bottom:1rem">
        ✅ Testimoni Anda berhasil dikirim. Terima kasih!
      </div>

      <!-- Tombol submit -->
      <button id="tf-submit-btn" onclick="submitTestimonial()" style="
        width:100%;
        background:var(--gold);color:var(--navy);
        border:none;padding:13px;
        font-family:var(--font-sans);font-size:0.72rem;font-weight:700;
        letter-spacing:2px;text-transform:uppercase;
        cursor:pointer;
        transition:all 0.3s;
      "
      onmouseover="this.style.background='var(--gold-light)'"
      onmouseout="this.style.background='var(--gold)'"
      >Kirim Testimoni</button>

    </div>
  </div>
</div>