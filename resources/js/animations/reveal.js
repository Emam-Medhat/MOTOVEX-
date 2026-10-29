/**
 * Scroll-triggered reveals. Fade + translate, single coherent easing
 * (power3.out). Uses mask reveal where [data-mask] is set.
 */
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

export function initReveals() {
    // Single elements
    gsap.utils.toArray('[data-reveal]').forEach((el) => {
        gsap.fromTo(
            el,
            { y: 44, opacity: 0 },
            {
                y: 0,
                opacity: 1,
                duration: 0.9,
                ease: 'power3.out',
                scrollTrigger: { trigger: el, start: 'top 86%' },
            }
        );
    });

    // Staggered groups
    gsap.utils.toArray('[data-reveal-stagger]').forEach((group) => {
        const items = group.querySelectorAll('[data-reveal-item]');
        if (!items.length) return;
        gsap.fromTo(
            items,
            { y: 40, opacity: 0 },
            {
                y: 0,
                opacity: 1,
                duration: 0.7,
                ease: 'power3.out',
                stagger: 0.08,
                scrollTrigger: { trigger: group, start: 'top 82%' },
            }
        );
    });

    // Mask line reveals (split-text feel)
    gsap.utils.toArray('[data-mask]').forEach((line) => {
        const inner = line.querySelector('.line-inner') || line;
        gsap.fromTo(
            inner,
            { yPercent: 115 },
            {
                yPercent: 0,
                duration: 1,
                ease: 'expo.out',
                scrollTrigger: { trigger: line, start: 'top 90%' },
            }
        );
    });

    // Parallax on backgrounds
    gsap.utils.toArray('[data-parallax]').forEach((el) => {
        const speed = parseFloat(el.dataset.parallax || '0.2');
        gsap.to(el, {
            yPercent: -speed * 100,
            ease: 'none',
            scrollTrigger: { trigger: el, start: 'top bottom', end: 'bottom top', scrub: true },
        });
    });
}
