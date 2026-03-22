<!-- ====== MODAL (Admin CRUD) ====== -->
<div class="admin-modal-bg" id="modalBg" onclick="if(event.target===this)closeModal()">
  <div class="admin-modal">
    <div class="admin-modal-header">
      <div class="admin-modal-title" id="modal-title">Tambah</div>
      <button class="admin-modal-close" onclick="closeModal()">✕</button>
    </div>
    <div id="modal-body"></div>
  </div>
</div>

<!-- ====== ARTICLE MODAL ====== -->
<div class="article-modal-bg" id="articleModal" onclick="if(event.target===this)closeArticle()">
  <div class="article-modal">
    <div class="article-modal-header">
      <button class="article-modal-close" onclick="closeArticle()">✕</button>
    </div>
    <div class="article-modal-content">
      <div id="article-modal-img"></div>
      <div class="article-modal-meta" id="article-modal-meta"></div>
      <h2 class="article-modal-title" id="article-modal-title"></h2>
      <div class="article-modal-body" id="article-modal-body"></div>
    </div>
  </div>
</div>

<!-- ====== TOAST NOTIFICATION ====== -->
<div class="toast" id="toast"></div>