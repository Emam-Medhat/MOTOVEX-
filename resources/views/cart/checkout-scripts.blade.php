<script>
(function () {
    'use strict';

    /* ── References ── */
    var backdrop  = document.getElementById('cko-backdrop');
    var closeBtn  = document.getElementById('cko-close');
    var form      = document.getElementById('cko-form');
    var submitBtn = document.getElementById('cko-submit');
    var submitTxt = document.getElementById('cko-submit-txt');
    var submitted = false;

    /* ── Close / navigate back ── */
    function closeModal () {
        var ref = document.referrer;
        var cartUrl = '{{ route('cart.index') }}';
        if (ref && ref.indexOf('/cart') !== -1 && ref.indexOf('checkout') === -1) {
            history.back();
        } else {
            window.location.href = cartUrl;
        }
    }

    /* ── Backdrop click ── */
    backdrop && backdrop.addEventListener('click', function (e) {
        if (e.target === backdrop) closeModal();
    });

    /* ── Close button ── */
    closeBtn && closeBtn.addEventListener('click', closeModal);

    /* ── Escape key ── */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });

    /* ── Lock page scroll while modal is visible ── */
    var wasLocked = false;
    if (document.body.style.overflow !== 'hidden') {
        document.body.style.overflow = 'hidden';
        wasLocked = true;
    }

    /* ── Form validation + double-submit guard ── */
    form && form.addEventListener('submit', function (e) {
        var nameF  = form.querySelector('[name="name"]');
        var emailF = form.querySelector('[name="email"]');
        var phoneF = form.querySelector('[name="phone"]');
        var valid  = true;

        [nameF, emailF, phoneF].forEach(function (f) {
            if (!f || !f.value.trim()) {
                if (f) f.classList.add('is-err');
                valid = false;
            } else {
                if (f) f.classList.remove('is-err');
            }
        });

        if (!valid) { e.preventDefault(); return; }
        if (submitted) { e.preventDefault(); return; }

        submitted = true;
        if (submitBtn) submitBtn.disabled = true;
        if (submitTxt) submitTxt.textContent = '{{ addslashes(__('cart.submitting_text')) }}';
    });

    /* ── bfcache restore ── */
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) {
            submitted = false;
            if (submitBtn) submitBtn.disabled = false;
            if (submitTxt) submitTxt.textContent = '{{ addslashes(__('cart.place_order_enquiry')) }}';
        }
    });

    /* ── Auto-focus first field ── */
    var firstInput = form && form.querySelector('input:not([type="hidden"])');
    if (firstInput) {
        setTimeout(function () { firstInput.focus(); }, 300);
    }
})();
</script>
