/**
 * Custom cursor (dot + lagging ring). Desktop / non-touch only.
 * Ring grows on interactive elements and shows "View" on product cards.
 */
export function initCursor() {
    document.body.classList.add('custom-cursor');

    const dot = document.querySelector('.cursor-dot');
    const ring = document.querySelector('.cursor-ring');
    if (!dot || !ring) return;

    let mx = 0, my = 0, rx = 0, ry = 0;

    window.addEventListener('mousemove', (e) => {
        mx = e.clientX;
        my = e.clientY;
        dot.style.transform = `translate(${mx - 3.5}px, ${my - 3.5}px)`;
    });

    const loop = () => {
        rx += (mx - rx) * 0.16;
        ry += (my - ry) * 0.16;
        ring.style.transform = `translate(${rx - 19}px, ${ry - 19}px)`;
        requestAnimationFrame(loop);
    };
    loop();

    const grow = () => ring.classList.add('is-active');
    const shrink = () => ring.classList.remove('is-active');
    document.querySelectorAll('a, button, [data-cursor], .product-card').forEach((el) => {
        el.addEventListener('mouseenter', grow);
        el.addEventListener('mouseleave', shrink);
    });

    // "View" label on product cards.
    document.querySelectorAll('.product-card').forEach((card) => {
        card.addEventListener('mouseenter', () => {
            ring.textContent = 'View';
            ring.style.fontFamily = 'Orbitron, sans-serif';
            ring.style.fontSize = '11px';
            ring.style.letterSpacing = '0.2em';
            ring.style.color = '#fff';
            ring.style.display = 'flex';
            ring.style.alignItems = 'center';
            ring.style.justifyContent = 'center';
            ring.style.textTransform = 'uppercase';
        });
        card.addEventListener('mouseleave', () => {
            ring.textContent = '';
            ring.style.fontFamily = '';
        });
    });
}
