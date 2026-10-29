/**
 * Preloader: animated logo draw-in + percentage counter, then a curtain
 * reveal into the hero. Skipped on repeat visits (sessionStorage) and
 * completes in under 1.5s.
 */
export function initPreloader() {
    const pre = document.querySelector('[data-preloader]');
    if (!pre) {
        document.body.classList.add('loaded');
        return;
    }

    // Respect reduced motion / repeat visits: skip straight through.
    if (sessionStorage.getItem('apex_loaded')) {
        pre.remove();
        document.body.classList.add('loaded');
        return;
    }

    const numEl = pre.querySelector('[data-preloader-count]');
    const barEl = pre.querySelector('[data-preloader-bar]');
    let pct = 0;

    const finish = () => {
        sessionStorage.setItem('apex_loaded', '1');
        pre.classList.add('is-done');
        document.body.classList.add('loaded');
        window.dispatchEvent(new CustomEvent('apex:loaded'));
        setTimeout(() => pre.remove(), 850);
    };

    const step = () => {
        pct += Math.random() * 11 + 5;
        if (pct >= 100) {
            pct = 100;
            if (numEl) numEl.textContent = '100';
            if (barEl) barEl.style.transform = 'scaleX(1)';
            setTimeout(finish, 180);
            return;
        }
        if (numEl) numEl.textContent = Math.floor(pct);
        if (barEl) barEl.style.transform = `scaleX(${pct / 100})`;
        setTimeout(step, 70 + Math.random() * 70);
    };

    setTimeout(step, 120);
}
