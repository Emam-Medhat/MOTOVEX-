/**
 * Product card interactions: 3D tilt + glow-follows-cursor on desktop.
 * On touch devices these are skipped (simple reveal only). The whole card
 * is a clickable link handled in Blade via an <a> wrapper.
 */
export function initProductCards(isTouch) {
    if (isTouch) return;

    document.querySelectorAll('.product-card').forEach((card) => {
        const inner = card.querySelector('[data-card-tilt]');
        if (!inner) return;

        let raf;
        const strength = 8; // max deg tilt

        const onMove = (e) => {
            const r = card.getBoundingClientRect();
            const px = (e.clientX - r.left) / r.width;
            const py = (e.clientY - r.top) / r.height;
            const rx = (0.5 - py) * strength;
            const ry = (px - 0.5) * strength;
            cancelAnimationFrame(raf);
            raf = requestAnimationFrame(() => {
                inner.style.transform = `perspective(900px) rotateX(${rx}deg) rotateY(${ry}deg) scale(1.02)`;
                card.style.setProperty('--mx', `${px * 100}%`);
                card.style.setProperty('--my', `${py * 100}%`);
            });
        };
        const onLeave = () => {
            cancelAnimationFrame(raf);
            inner.style.transform = '';
        };

        card.addEventListener('mousemove', onMove);
        card.addEventListener('mouseleave', onLeave);
    });
}
