/**
 * Horizontal pinned showcase (desktop only). Pins a container and
 * translates a row of panels sideways as the user scrolls, with spec
 * numbers counting up as each panel enters the viewport via containerAnimation.
 * Skipped on touch / reduced-motion / narrow screens.
 */
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

function format(value, el) {
    const suffix = el.dataset.suffix || '';
    return Math.round(value).toLocaleString('en-US') + suffix;
}

function animateCounter(el) {
    const target = parseFloat(el.dataset.pinCounter) || 0;
    gsap.fromTo(
        { v: 0 },
        { v: target, duration: 1.4, ease: 'power2.out',
          onUpdate() { el.textContent = format(this.targets()[0].v, el); } }
    );
}

export function initHorizontalPin(reduced) {
    if (reduced) return;
    if (window.innerWidth < 1024) return;

    const wrap = document.querySelector('[data-pin-section]');
    if (!wrap) return;
    const pin = wrap.querySelector('[data-pin-pin]');
    const row = wrap.querySelector('[data-pin-row]');
    if (!pin || !row) return;

    try {
        const getDist = () => Math.max(0, row.scrollWidth - window.innerWidth);

        const tween = gsap.to(row, {
            x: () => -getDist(),
            ease: 'none',
            scrollTrigger: {
                trigger: wrap,
                start: 'top top',
                end: () => '+=' + getDist(),
                pin: pin,
                scrub: 0.8,
                invalidateOnRefresh: true,
                anticipatePin: 1,
            },
        });

        // Count-up numbers bound to horizontal position.
        row.querySelectorAll('[data-pin-counter]').forEach((el) => {
            ScrollTrigger.create({
                trigger: el,
                containerAnimation: tween,
                start: 'left 75%',
                once: true,
                onEnter: () => animateCounter(el),
            });
        });
    } catch (e) {
        // Graceful degradation — leave panels in normal flow.
        console.warn('Pin showcase disabled:', e);
    }
}
