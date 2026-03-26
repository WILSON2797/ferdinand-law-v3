<!-- ====== ADMIN LOGIN ====== -->
<div class="admin-login" id="adminLogin" style="display:none">
  <div class="admin-login-box">
    <div class="admin-login-logo">Ferdinand Hutahaean & CO</div>
    <div class="admin-login-sub">Admin Portal</div>
    <div class="admin-login-title">Masuk ke Panel Admin</div>
    <div id="login-error" class="login-error" style="display:none"></div>
    <div class="admin-field">
      <label>Username</label>
      <input type="text" id="login-user" placeholder="admin" />
    </div>
    <div class="admin-field">
      <label>Password</label>
      <input type="password" id="login-pass" placeholder="••••••••" onkeypress="if(event.key==='Enter')doLogin()" />
    </div>
    <button class="btn-admin" onclick="doLogin()" style="width:100%;justify-content:center;margin-top:1rem">
      Masuk →
    </button>
    <button onclick="hideAdminLogin()" style="display:block;width:100%;text-align:center;margin-top:1rem;background:none;border:none;color:rgba(255,255,255,0.3);font-size:0.7rem;cursor:pointer;font-family:var(--font-sans)">
      ← Kembali ke Website
    </button>
  </div>
</div>