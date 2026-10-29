/**
 * Animated stat counters. Trigger once on scroll. Reduced-motion shows the
 * final value immediately. Supports integer + suffix via data-counter and
 * data-suffix.
 */
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

function format(value, el) {
    const suffix = el.dataset.suffix || '';
    const v = Math.round(value);
    return v.toLocaleString('en-US') + suffix;
}

export function initCounters(reduced) {
    gsap.utils.toArray('[data-counter]').forEach((el) => {
        const target = parseFloat(el.dataset.counter) || 0;

        if (reduced) {
            el.textContent = format(target, el);
            return;
        }

        ScrollTrigger.create({
            trigger: el,
            start: 'top 88%',
            once: true,
            onEnter: () => {
                gsap.fromTo(
                    { v: 0 },
                    { v: target, duration: 1.7, ease: 'power2.out',
                      onUpdate() {
                          el.textContent = format(this.targets()[0].v, el);
                      } }
                );
            },
        });
    });
}
