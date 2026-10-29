/**
 * APEX MOTO GROUP — frontend entry
 * Bundled by Vite. Self-hosted fonts, Alpine for UI state, GSAP + Lenis
 * for motion. Heavy effects are skipped on touch / reduced-motion devices.
 */
import './bootstrap';

/* Self-hosted fonts (font-display: swap is set in each package) */
import '@fontsource/inter/400.css';
import '@fontsource/inter/500.css';
import '@fontsource/inter/600.css';
import '@fontsource/inter/700.css';
import '@fontsource/orbitron/400.css';
import '@fontsource/orbitron/500.css';
import '@fontsource/orbitron/600.css';
import '@fontsource/orbitron/700.css';
import '@fontsource/orbitron/800.css';
import '@fontsource/orbitron/900.css';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

Alpine.plugin(focus);

import { refreshIcons } from './components/icons.js';
import { initLenis } from './components/lenis.js';
import { initThemer } from './components/themer.js';
import { initNav } from './components/nav.js';
import { initPreloader } from './components/preloader.js';
import { initHero } from './components/hero-carousel.js';
import { initMagnetic } from './components/magnetic.js';
import { initCountdown } from './components/countdown.js';
import { initReveals } from './animations/reveal.js';
import { initCounters } from './animations/counters.js';
import { initHorizontalPin } from './animations/pin.js';
import { initProductCards } from './components/product-card.js';

const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const isTouch = window.matchMedia('(hover: none), (pointer: coarse)').matches;

window.Alpine = Alpine;

window.addEventListener('DOMContentLoaded', () => {
    // UI state first (Alpine), then motion layer.
    Alpine.start();
    refreshIcons(); // replace [data-lucide] placeholders with inline SVGs

    initPreloader();
    initLenis(prefersReduced);
    initThemer();
    initNav();

    initHero(prefersReduced, isTouch);
    initCountdown();
    initProductCards(isTouch);

    if (!prefersReduced) {
        initReveals();
        initCounters(prefersReduced);
    } else {
        initCounters(true);
    }

    if (!isTouch && !prefersReduced) {
        initMagnetic();
    }

    initHorizontalPin(prefersReduced);

    // Refresh ScrollTrigger once images/fonts settle.
    window.addEventListener('load', () => window.dispatchEvent(new Event('resize')));
});
