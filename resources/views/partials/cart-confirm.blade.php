{{--
    Reusable "Add to cart" confirmation modal.
    Any <form data-confirm-cart data-part-name="…" data-part-price="…" data-part-currency="…"> will
    open this dialog before submitting, so the user always confirms first.

    Pass $theme = 'dark' | 'light' (default 'light') to match the host page palette.
--}}
<style>
    .cc-overlay{position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:20px;
        background:rgba(4,10,22,.58);-webkit-backdrop-filter:blur(6px);backdrop-filter:blur(6px);
        opacity:0;visibility:hidden;transition:opacity .28s ease,visibility .28s ease}
    .cc-overlay.cc-open{opacity:1;visibility:visible}
    .cc-card{width:min(440px,100%);background:#fff;color:#172033;border:1px solid #E2E8F0;border-radius:22px;
        padding:30px 28px 24px;text-align:center;position:relative;font-family:inherit;
        box-shadow:0 30px 60px -22px rgba(15,23,42,.38);
        transform:translateY(16px) scale(.95);opacity:0;
        transition:transform .38s cubic-bezier(.16,1,.3,1),opacity .3s ease}
    .cc-overlay.cc-open .cc-card{transform:none;opacity:1}
    .cc-icon{width:66px;height:66px;border-radius:20px;margin:0 auto 16px;display:grid;place-items:center;
        font-size:26px;color:#fff;background:linear-gradient(135deg,#8b5cf6,#6d28d9);
        box-shadow:0 14px 28px -10px rgba(124,58,237,.6)}
    .cc-title{font-size:19px;font-weight:800;margin:0 0 7px;letter-spacing:-.2px;line-height:1.4}
    .cc-desc{font-size:13px;color:#64748B;line-height:1.7;margin:0 0 20px}
    .cc-desc strong{color:#172033;font-weight:800}
    .cc-meta{display:flex;gap:10px;margin-bottom:22px}
    .cc-meta-cell{flex:1;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:14px;padding:11px 14px;text-align:start}
    .cc-meta-label{display:block;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.09em;color:#94A3B8;margin-bottom:3px}
    .cc-meta-value{font-size:15px;font-weight:800;color:#172033}
    .cc-actions{display:flex;gap:10px}
    .cc-btn{flex:1;border:none;border-radius:14px;padding:13px 16px;font-size:13px;font-weight:800;cursor:pointer;
        display:inline-flex;align-items:center;justify-content:center;gap:8px;font-family:inherit;
        transition:transform .25s ease,background .25s ease,box-shadow .25s ease}
    .cc-cancel{background:#F1F5F9;color:#475569}
    .cc-cancel:hover{background:#E2E8F0;color:#1e293b}
    .cc-confirm{background:#7C3AED;color:#fff;box-shadow:0 10px 22px -8px rgba(124,58,237,.6)}
    .cc-confirm:hover{background:#6D28D9;transform:translateY(-2px);box-shadow:0 14px 26px -8px rgba(124,58,237,.7)}
    .cc-btn:active{transform:translateY(0)}

    /* ── Dark (cyber) variant ─────────────────────────────────────────────── */
    .cc-overlay[data-theme="dark"] .cc-card{background:linear-gradient(160deg,#102642,#071326);color:#f4f8ff;
        border-color:#1d4c78;box-shadow:0 30px 60px -18px #000c,inset 0 0 24px #168bff12}
    .cc-overlay[data-theme="dark"] .cc-icon{background:linear-gradient(135deg,#a64bff,#4b39ff 60%,#11b9ff);
        box-shadow:0 0 28px #743cff99}
    .cc-overlay[data-theme="dark"] .cc-title{color:#f4f8ff}
    .cc-overlay[data-theme="dark"] .cc-desc{color:#9db3cc}
    .cc-overlay[data-theme="dark"] .cc-desc strong{color:#fff}
    .cc-overlay[data-theme="dark"] .cc-meta-cell{background:#071426;border-color:#1d4c78}
    .cc-overlay[data-theme="dark"] .cc-meta-label{color:#7d93ad}
    .cc-overlay[data-theme="dark"] .cc-meta-value{color:#ffc247}
    .cc-overlay[data-theme="dark"] .cc-cancel{background:#16263b;color:#c3d2e4}
    .cc-overlay[data-theme="dark"] .cc-cancel:hover{background:#1d3251;color:#fff}
    .cc-overlay[data-theme="dark"] .cc-confirm{background:linear-gradient(110deg,#853dff,#4a35db);box-shadow:0 0 24px #713bff88}
    .cc-overlay[data-theme="dark"] .cc-confirm:hover{box-shadow:0 0 34px #853dffbb}
</style>

<div class="cc-overlay" id="ccOverlay" data-theme="{{ $theme ?? 'light' }}" hidden>
    <div class="cc-card" role="dialog" aria-modal="true" aria-labelledby="ccTitle" aria-describedby="ccDesc">
        <div class="cc-icon"><i class="fa-solid fa-cart-plus"></i></div>
        <h3 class="cc-title" id="ccTitle">{{ __('spare_parts.confirm_cart_title') }}</h3>
        <p class="cc-desc" id="ccDesc"></p>
        <div class="cc-meta">
            <div class="cc-meta-cell">
                <span class="cc-meta-label">{{ __('spare_parts.confirm_cart_qty') }}</span>
                <span class="cc-meta-value" id="ccQty">1</span>
            </div>
            <div class="cc-meta-cell" id="ccPriceCell" hidden>
                <span class="cc-meta-label">{{ __('spare_parts.confirm_cart_total') }}</span>
                <span class="cc-meta-value" id="ccPrice"></span>
            </div>
        </div>
        <div class="cc-actions">
            <button type="button" class="cc-btn cc-cancel" id="ccCancel">{{ __('spare_parts.confirm_cart_cancel') }}</button>
            <button type="button" class="cc-btn cc-confirm" id="ccConfirm">
                <i class="fa-solid fa-check"></i> {{ __('spare_parts.confirm_cart_confirm') }}
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    var overlay = document.getElementById('ccOverlay');
    if (!overlay) return;

    var descEl   = document.getElementById('ccDesc');
    var qtyEl    = document.getElementById('ccQty');
    var priceEl  = document.getElementById('ccPrice');
    var priceCell= document.getElementById('ccPriceCell');
    var confirmBtn = document.getElementById('ccConfirm');
    var cancelBtn  = document.getElementById('ccCancel');
    var template = @json(__('spare_parts.confirm_cart_desc'));
    var activeForm = null;

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    function open(form) {
        activeForm = form;

        var name  = form.getAttribute('data-part-name') || '';
        var price = parseFloat(form.getAttribute('data-part-price'));
        var cur   = form.getAttribute('data-part-currency') || '';
        var qtyInput = form.querySelector('input[name="quantity"]');
        var qty = qtyInput ? (parseInt(qtyInput.value, 10) || 1) : 1;

        descEl.innerHTML = escapeHtml(template).replace(':name', '<strong>' + escapeHtml(name) + '</strong>');
        qtyEl.textContent = qty;

        if (!isNaN(price) && price > 0) {
            priceCell.hidden = false;
            priceEl.textContent = (Math.round(price * qty * 100) / 100).toLocaleString() + (cur ? ' ' + cur : '');
        } else {
            priceCell.hidden = true;
        }

        overlay.hidden = false;
        requestAnimationFrame(function () { overlay.classList.add('cc-open'); });
        document.body.style.overflow = 'hidden';
        confirmBtn.focus();
    }

    function close() {
        overlay.classList.remove('cc-open');
        document.body.style.overflow = '';
        activeForm = null;
        window.setTimeout(function () { overlay.hidden = true; }, 300);
    }

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form.matches || !form.matches('form[data-confirm-cart]')) return;
        e.preventDefault();
        open(form);
    });

    confirmBtn.addEventListener('click', function () {
        var form = activeForm;
        if (!form) return;
        close();
        form.submit(); // native submit — does not re-trigger the submit listener
    });

    cancelBtn.addEventListener('click', close);
    overlay.addEventListener('click', function (e) { if (e.target === overlay) close(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !overlay.hidden) close(); });
})();
</script>
