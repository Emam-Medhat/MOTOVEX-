/**
 * Header behaviour: transparent → blurred glass on scroll, hides on scroll
 * down, reveals on scroll up. The overlay menu open/close is handled by
 * Alpine (x-data) in the Blade header.
 */
export function initNav() {
    const header = document.querySelector('[data-header]');
    if (!header) return;

    let last = window.scrollY;
    let ticking = false;

    const update = () => {
        const y = window.scrollY;
        header.classList.toggle('is-scrolled', y > 40);
        if (y > last && y > 220) {
            header.classList.add('is-hidden');
        } else {
            header.classList.remove('is-hidden');
        }
        last = y;
        ticking = false;
    };

    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(update);
            ticking = true;
        }
    }, { passive: true });
}
