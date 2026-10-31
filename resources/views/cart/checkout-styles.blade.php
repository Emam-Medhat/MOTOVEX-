<style>
/* ═══════════════════════════════════════════════════════════════
   MOTOVEX Checkout — Modal styles
   Scoped to #cko-* — never leaks to other pages.
   Used by: cart/checkout.blade.php (full page fallback)
            and cart/index.blade.php (injected modal overlay)
   ═══════════════════════════════════════════════════════════════ */

/* ── Backdrop (full-page visit) ── */
/* z-index must clear the sticky navbar (.mvnav = 9999) so the popup —
   including its header and close button — is never occluded. */
#cko-backdrop {
    position: fixed; inset: 0; z-index: 10050;
    background: rgba(3, 7, 18, .74);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    display: flex; align-items: center; justify-content: center;
    padding: 1rem;
    animation: ckoFadeIn .2s ease both;
}
@keyframes ckoFadeIn { from { opacity: 0; } to { opacity: 1; } }

/* ── Modal panel ── */
#cko-modal {
    position: relative;
    width: 100%; max-width: 1000px;
    max-height: 92vh; overflow-y: auto;
    background: #0d1b2e;
    border: 1px solid #1e3a58;
    border-radius: 18px;
    box-shadow: 0 32px 80px rgba(0,0,0,.65), 0 0 0 1px rgba(139,92,246,.08);
    animation: ckoUp .26s cubic-bezier(.16,1,.3,1) both;
    scrollbar-width: thin; scrollbar-color: #1e3a58 transparent;
}
#cko-modal::-webkit-scrollbar       { width: 5px; }
#cko-modal::-webkit-scrollbar-thumb { background: #1e3a58; border-radius: 10px; }
@keyframes ckoUp {
    from { opacity: 0; transform: translateY(24px) scale(.985); }
    to   { opacity: 1; transform: none; }
}

/* ── Close ── */
#cko-close {
    position: sticky; top: 10px; float: right; z-index: 30;
    margin: 14px 14px -14px 0;
    width: 34px; height: 34px;
    border-radius: 50%; border: 1px solid rgba(255,255,255,.1);
    background: rgba(255,255,255,.06);
    color: #94a3b8; font-size: 14px;
    display: grid; place-items: center; cursor: pointer;
    transition: background .18s, color .18s, transform .18s;
}
#cko-close:hover { background: rgba(248,113,113,.15); border-color: rgba(248,113,113,.3); color: #fca5a5; transform: scale(1.1); }

/* ── Header ── */
.cko-header {
    padding: 1.5rem 1.75rem 0;
    display: flex; align-items: flex-start; gap: 1rem;
}
.cko-title {
    font-family: 'Inter', 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
    font-size: 1.5rem; font-weight: 700; color: #f0f6ff;
    letter-spacing: -.018em; line-height: 1.2; margin: 0;
}
.cko-subtitle { font-size: .84rem; color: #6b8aaa; margin-top: .3rem; }
.cko-secure-badge {
    display: inline-flex; align-items: center; gap: .4rem;
    font-size: .7rem; font-weight: 600; color: #a78bfa;
    background: rgba(139,92,246,.1); border: 1px solid rgba(139,92,246,.22);
    border-radius: 999px; padding: .28rem .7rem; margin-top: .6rem;
}

/* ── Progress steps ── */
.cko-steps {
    display: flex; align-items: center;
    padding: 1.1rem 1.75rem .4rem; gap: 0; max-width: 380px;
}
.cko-step { display: flex; flex-direction: column; align-items: center; gap: .3rem; flex: 1; position: relative; }
.cko-step::after {
    content: ''; position: absolute; top: 15px; left: 50%; width: 100%; height: 1.5px;
    background: #1e3a58; z-index: 0;
}
.cko-step:last-child::after { display: none; }
.cko-step-dot {
    width: 30px; height: 30px; border-radius: 50%;
    display: grid; place-items: center;
    font-size: 11px; font-weight: 700; position: relative; z-index: 1;
    border: 1.5px solid #1e3a58; background: #0d1b2e; color: #3d5a78;
    transition: all .2s;
}
.cko-step.done .cko-step-dot   { background: rgba(124,58,237,.18); border-color: #7c3aed; color: #a78bfa; }
.cko-step.done::after          { background: #7c3aed; }
.cko-step.active .cko-step-dot { background: #7c3aed; border-color: #7c3aed; color: #fff; box-shadow: 0 4px 14px rgba(124,58,237,.4); }
.cko-step-lbl { font-size: .61rem; font-weight: 600; color: #3d5a78; text-transform: uppercase; letter-spacing: .05em; }
.cko-step.active .cko-step-lbl { color: #a78bfa; }
.cko-step.done .cko-step-lbl   { color: #7c3aed; }

/* ── Divider ── */
.cko-divider { height: 1px; background: #1e3a58; margin: 1rem 1.75rem 0; }

/* ── Two-column body ── */
.cko-body { display: grid; gap: 1.4rem; padding: 1.4rem 1.75rem 1.75rem; }
@media (min-width: 700px)  { .cko-body { grid-template-columns: 1fr 296px; } }
@media (min-width: 920px)  { .cko-body { grid-template-columns: 1fr 316px; } }

/* ── Card ── */
.cko-card { background: rgba(255,255,255,.03); border: 1px solid #1e3a58; border-radius: 13px; overflow: hidden; }
.cko-card-head {
    display: flex; align-items: center; gap: .6rem;
    padding: .95rem 1.2rem; border-bottom: 1px solid #1e3a58;
    background: rgba(139,92,246,.04);
}
.cko-card-ico {
    width: 32px; height: 32px; border-radius: 9px; flex-shrink: 0;
    display: grid; place-items: center; font-size: 12px;
    background: rgba(139,92,246,.15); color: #a78bfa; border: 1px solid rgba(139,92,246,.25);
}
.cko-card-title { font-size: .88rem; font-weight: 700; color: #f0f6ff; }
.cko-card-sub   { font-size: .72rem; color: #6b8aaa; margin-top: .08rem; }
.cko-card-body  { padding: 1.2rem; }

/* ── Form fields ── */
.cko-field { margin-bottom: .95rem; }
.cko-label { display: block; font-size: .75rem; font-weight: 600; color: #9eb9d3; margin-bottom: .38rem; }
.cko-req   { color: #a78bfa; }
.cko-iw    { position: relative; }
.cko-iw > i {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: #6b8aaa; font-size: 12px; pointer-events: none; z-index: 1;
}
.cko-input {
    width: 100%; background: rgba(255,255,255,.04); border: 1px solid #1e3a58;
    border-radius: 10px; padding: .72rem 1rem .72rem 2.35rem;
    font-size: .875rem;
    font-family: 'Inter', 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
    color: #e8f0f8; outline: none;
    transition: border-color .18s, box-shadow .18s;
}
.cko-input::placeholder { color: #28445e; }
.cko-input:focus  { border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124,58,237,.14); }
.cko-input.is-err { border-color: #f87171; box-shadow: 0 0 0 3px rgba(248,113,113,.12); }
textarea.cko-input { padding-top: .72rem; padding-bottom: .72rem; resize: vertical; min-height: 88px; }
.cko-input option { background: #0d1b2e; color: #e8f0f8; }
.cko-err {
    font-size: .71rem; color: #fca5a5; font-weight: 600;
    margin-top: .28rem; display: flex; align-items: center; gap: .3rem;
}
.cko-grid2 { display: grid; gap: .85rem; }
@media (min-width: 480px) { .cko-grid2 { grid-template-columns: 1fr 1fr; } }

/* ── Submit ── */
.cko-submit {
    width: 100%; display: flex; align-items: center; justify-content: center; gap: .6rem;
    padding: .88rem 1.5rem; border-radius: 11px; border: none; cursor: pointer;
    font-family: 'Inter', 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
    font-size: .9rem; font-weight: 700; color: #fff; margin-top: 1.1rem;
    background: #7c3aed;
    box-shadow: 0 8px 24px rgba(124,58,237,.32);
    transition: background .2s, transform .2s, box-shadow .2s;
}
.cko-submit:hover { background: #6d28d9; transform: translateY(-1px); box-shadow: 0 12px 32px rgba(124,58,237,.42); }
.cko-submit:disabled { opacity: .58; cursor: not-allowed; transform: none; }
.cko-submit .arrow { transition: transform .2s; }
.cko-submit:hover .arrow { transform: translateX(3px); }

/* ── Trust bar ── */
.cko-trust {
    display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center;
    padding: .7rem .85rem; margin-top: .85rem; border-radius: 9px;
    background: rgba(139,92,246,.05); border: 1px solid rgba(139,92,246,.12);
}
.cko-trust span { display: flex; align-items: center; gap: .32rem; font-size: .69rem; font-weight: 600; color: #a78bfa; }

/* ── Order items ── */
.cko-item {
    display: flex; align-items: center; gap: .7rem;
    padding: .7rem 0; border-bottom: 1px solid #1e3a58;
}
.cko-item:last-child { border-bottom: none; padding-bottom: 0; }
.cko-item-img {
    width: 46px; height: 36px; flex-shrink: 0; border-radius: 8px;
    border: 1px solid #1e3a58; background: #071326; overflow: hidden;
    display: flex; align-items: center; justify-content: center;
}
.cko-item-img img { width: 100%; height: 100%; object-fit: cover; }
.cko-item-name  { font-size: .81rem; font-weight: 700; color: #e8f0f8; line-height: 1.3; }
.cko-item-brand { font-size: .66rem; font-weight: 600; color: #7c3aed; text-transform: uppercase; letter-spacing: .08em; margin-top: .1rem; }
.cko-item-price { margin-left: auto; font-size: .83rem; font-weight: 700; color: #a78bfa; flex-shrink: 0; }

/* ── Total ── */
.cko-total-row {
    display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    padding: .85rem 0 0; border-top: 1px solid #1e3a58; margin-top: .7rem;
}
.cko-total-lbl { font-size: .73rem; font-weight: 700; color: #6b8aaa; text-transform: uppercase; letter-spacing: .07em; }
.cko-total-val { font-size: 1.4rem; font-weight: 800; color: #e8f0f8; letter-spacing: -.02em; }
.cko-total-cur { font-size: .76rem; font-weight: 600; color: #a78bfa; margin-right: .22rem; }

/* ── Note ── */
.cko-note {
    display: flex; align-items: flex-start; gap: .5rem;
    padding: .7rem .85rem; border-radius: 9px; margin-top: .85rem;
    background: rgba(56,189,248,.06); border: 1px solid rgba(56,189,248,.14);
    font-size: .71rem; color: #6b8aaa; line-height: 1.55;
}
.cko-note i { color: #38bdf8; margin-top: .1rem; flex-shrink: 0; font-size: .72rem; }

/* ── Promises ── */
.cko-promises { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; margin-top: .8rem; }
.cko-promise {
    display: flex; align-items: flex-start; gap: .45rem;
    padding: .6rem .7rem; border-radius: 8px;
    background: rgba(139,92,246,.05); border: 1px solid rgba(139,92,246,.12);
}
.cko-promise i    { color: #a78bfa; font-size: .78rem; margin-top: .1rem; flex-shrink: 0; }
.cko-promise span { font-size: .67rem; font-weight: 600; color: #9eb9d3; line-height: 1.4; }

/* ── Flash ── */
.cko-flash {
    display: flex; align-items: center; gap: .6rem; padding: .75rem .95rem;
    border-radius: 9px; margin: 0 1.75rem .4rem; font-size: .82rem; font-weight: 600;
}
.cko-flash.ok   { background: rgba(34,197,94,.08);  border: 1px solid rgba(34,197,94,.22);  color: #86efac; }
.cko-flash.err  { background: rgba(239,68,68,.08);  border: 1px solid rgba(239,68,68,.22);  color: #fca5a5; }
.cko-flash.info { background: rgba(56,189,248,.07); border: 1px solid rgba(56,189,248,.18); color: #7dd3fc; }

/* ── Mobile: bottom sheet ── */
@media (max-width: 599px) {
    #cko-backdrop { padding: 0; align-items: flex-end; }
    #cko-modal {
        max-width: 100%; max-height: 96vh;
        border-radius: 20px 20px 0 0;
        animation: ckoSheet .28s cubic-bezier(.16,1,.3,1) both;
    }
    @keyframes ckoSheet { from { opacity:0; transform:translateY(100%); } to { opacity:1; transform:none; } }
    .cko-header  { padding: 1.2rem 1.2rem 0; }
    .cko-divider { margin: .75rem 1.2rem 0; }
    .cko-steps   { padding: .9rem 1.2rem .25rem; }
    .cko-body    { padding: 1.1rem 1.2rem 1.4rem; }
    .cko-flash   { margin-inline: 1.2rem; }
}

@media (prefers-reduced-motion: reduce) {
    #cko-backdrop, #cko-modal { animation: none; }
}
</style>
