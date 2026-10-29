/**
 * Magnetic hover for primary CTAs. Translates the element toward the cursor
 * and springs back on leave. Animate transform/opacity only.
 */
export function initMagnetic() {
    document.querySelectorAll('[data-magnetic]').forEach((el) => {
        const strength = parseFloat(el.dataset.magnetic || '0.3');
        el.addEventListener('mousemove', (e) => {
            const r = el.getBoundingClientRect();
            const x = (e.clientX - r.left - r.width / 2) * strength;
            const y = (e.clientY - r.top - r.height / 2) * strength;
            el.style.transform = `translate(${x}px, ${y}px)`;
        });
        el.addEventListener('mouseleave', () => {
            el.style.transform = '';
        });
    });
}
