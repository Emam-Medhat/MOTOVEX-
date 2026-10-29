/**
 * Smooth scroll (Lenis) synced to GSAP's ticker + ScrollTrigger.
 * Disabled when prefers-reduced-motion. Also wires in-page anchor links.
 */
import Lenis from 'lenis';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

export function initLenis(reduced) {
    if (reduced) {
        wireAnchors(null);
        return;
    }

    const lenis = new Lenis({
        duration: 1.1,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        smoothWheel: true,
        touchMultiplier: 1.6,
    });

    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((time) => lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);

    window.__lenis = lenis;

    // Keep ScrollTrigger measurements correct on resize.
    let rT;
    window.addEventListener('resize', () => {
        clearTimeout(rT);
        rT = setTimeout(() => ScrollTrigger.refresh(), 200);
    });

    wireAnchors(lenis);
}

function wireAnchors(lenis) {
    document.querySelectorAll('a[href^="#"]').forEach((a) => {
        a.addEventListener('click', (e) => {
            const id = a.getAttribute('href');
            if (id.length <= 1) return;
            const target = document.querySelector(id);
            if (!target) return;
            e.preventDefault();
            if (lenis) {
                lenis.scrollTo(target, { offset: -72 });
            } else {
                target.scrollIntoView({ behavior: reducedSafe() ? 'auto' : 'smooth' });
            }
        });
    });
}

function reducedSafe() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}
