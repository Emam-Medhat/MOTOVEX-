/**
 * Promotion countdown timer. Reads an ISO end date from [data-countdown]
 * and updates [data-cd] cells (d / h / m / s).
 */
export function initCountdown() {
    const el = document.querySelector('[data-countdown]');
    if (!el) return;

    const end = new Date(el.dataset.countdown).getTime();
    if (isNaN(end)) return;

    const cells = el.querySelectorAll('[data-cd]');
    const pad = (n) => String(n).padStart(2, '0');

    const tick = () => {
        let diff = end - Date.now();
        if (diff < 0) diff = 0;
        const d = Math.floor(diff / 86400000);
        const h = Math.floor((diff % 86400000) / 3600000);
        const m = Math.floor((diff % 3600000) / 60000);
        const s = Math.floor((diff % 60000) / 1000);
        const map = { d, h, m, s };
        cells.forEach((c) => {
            const key = c.dataset.cd;
            if (map[key] !== undefined) c.textContent = pad(map[key]);
        });
    };

    tick();
    setInterval(tick, 1000);
}
