/**
 * Brand accent theming. Exposes window.apexTheme(color) which updates the
 * --color-accent CSS variable so the whole page re-skins instantly.
 * The hero carousel and brand switcher both call it.
 */
function hexToRgb(hex) {
    const h = hex.replace('#', '');
    const full = h.length === 3 ? h.split('').map((c) => c + c).join('') : h;
    const n = parseInt(full, 16);
    return [(n >> 16) & 255, (n >> 8) & 255, n & 255];
}

window.apexTheme = (color) => {
    const root = document.documentElement.style;
    if (!color) return;
    root.setProperty('--color-accent', color);
    const [r, g, b] = hexToRgb(color);
    root.setProperty('--accent-rgb', `${r}, ${g}, ${b}`);
    document.dispatchEvent(new CustomEvent('apex:theme', { detail: { color } }));
};

export function initThemer() {
    // Default accent is already set in CSS @theme. Hook for brand switcher
    // to read initial accent from the active hero slide via data attribute.
    const initial = document.querySelector('[data-default-accent]');
    if (initial && initial.dataset.defaultAccent) {
        window.apexTheme(initial.dataset.defaultAccent);
    }
}
