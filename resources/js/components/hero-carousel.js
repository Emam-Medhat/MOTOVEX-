/**
 * Hero carousel — the showpiece.
 * Drives: slide counter, prev/next, autoplay w/ progress, keyboard, swipe,
 * accent re-theming per slide, animated model-name + bike clip reveal.
 */
import { gsap } from 'gsap';

export function initHero(reduced, isTouch) {
    const root = document.querySelector('[data-hero]');
    const dataEl = document.getElementById('hero-data');
    if (!root || !dataEl) return;

    let slides;
    try { slides = JSON.parse(dataEl.textContent); } catch (e) { return; }
    if (!slides || !slides.length) return;

    // DOM refs
    const outline       = root.querySelector('[data-hero-outline]');
    const bike          = root.querySelector('[data-hero-bike]');
    const bikeWrap      = root.querySelector('[data-hero-bike-wrap]');
    const brandText     = root.querySelector('[data-hero-brand]');
    const modelMainText = root.querySelector('[data-hero-model-main]');
    const description   = root.querySelector('[data-hero-description]');
    const specsEl       = root.querySelector('[data-hero-specs]');
    const prevBtn       = root.querySelector('[data-hero-prev]');
    const nextBtn       = root.querySelector('[data-hero-next]');
    const dots          = root.querySelectorAll('[data-hero-dot]');
    const tabsBar       = root.querySelector('[data-hero-tabs]');
    const tabBrand      = root.querySelector('[data-hero-tab-brand]');
    const tabModel      = root.querySelector('[data-hero-tab-model]');
    const glow          = root.querySelector('[data-hero-glow]');

    let index = 0;
    let activeTab = 0;
    let autoTl = null;

    function getPreviewContent(slide, tabIdx) {
        switch (tabIdx) {
            case 1: return `<span class="text-[10px] text-white font-bold">${slide.hp} HP</span><span class="text-[8px] opacity-40 uppercase">Performance</span>`;
            case 2: return `<span class="text-[10px] text-white font-bold">Aerodynamic</span><span class="text-[8px] opacity-40 uppercase">Design</span>`;
            case 3: return `<span class="text-[10px] text-white font-bold">TFT Dash</span><span class="text-[8px] opacity-40 uppercase">Technology</span>`;
            case 4: return `<span class="text-[10px] text-white font-bold">ABS & TC</span><span class="text-[8px] opacity-40 uppercase">Safety</span>`;
            default: return '';
        }
    }

    /* ---- the transition timeline ---- */
    function goTo(i, instant) {
        index = (i + slides.length) % slides.length;
        const s = slides[index];
        const accent = s.brand?.accent || '#1ee05a';

        window.apexTheme && window.apexTheme(accent);

        const dur = instant ? 0 : 0.8;
        const ease = 'expo.out';

        // Update dots
        dots.forEach((dot, idx) => {
            dot.classList.toggle('text-accent', idx === index);
            dot.classList.toggle('scale-125', idx === index);
            dot.classList.toggle('text-apex-faint', idx !== index);
        });

        // Background Outline
        if (outline) {
            outline.textContent = s.model;
            if (!instant) {
                gsap.fromTo(outline, { x: 50, opacity: 0 }, { x: 0, opacity: 0.15, duration: dur, ease });
            }
        }

        // Left Side Text
        if (brandText) brandText.textContent = s.brand?.name || '';
        if (modelMainText) modelMainText.textContent = s.model;
        if (description) description.textContent = s.short || '';

        if (!instant) {
            gsap.fromTo([brandText, modelMainText, description], 
                { x: -30, opacity: 0 }, 
                { x: 0, opacity: (el) => el === brandText ? 0.2 : 1, duration: dur, ease, stagger: 0.05 }
            );
        }

        // Bike Image
        if (bike) {
            bike.src = s.image;
            if (!instant) {
                gsap.fromTo(bike, { x: 100, opacity: 0, scale: 0.9 }, { x: 0, opacity: 1, scale: 1, duration: dur, ease });
            }
        }

        // Bottom Tabs
        if (tabBrand) tabBrand.textContent = s.brand?.name || '';
        if (tabModel) tabModel.textContent = s.model;

        // Update previews in other tabs
        for (let t = 1; t <= 4; t++) {
            const preview = root.querySelector(`[data-tab-preview="${t}"]`);
            if (preview) preview.innerHTML = getPreviewContent(s, t);
        }

        // Update Specs
        if (specsEl) {
            const enginePart = s.engine ? `
                <div class="w-px bg-white/10 self-stretch"></div>
                <div class="flex flex-col gap-1">
                    <span class="eyebrow text-[9px] opacity-60">Engine</span>
                    <span class="font-display text-2xl sm:text-3xl">${s.engine}<span class="text-[10px] text-apex-muted ms-1">CC</span></span>
                </div>` : '';
            specsEl.innerHTML = `
                <div class="flex flex-col gap-1">
                    <span class="eyebrow text-[9px] opacity-60">Power</span>
                    <span class="font-display text-2xl sm:text-3xl">${s.hp}<span class="text-[10px] text-apex-muted ms-1">HP</span></span>
                </div>
                <div class="w-px bg-white/10 self-stretch"></div>
                <div class="flex flex-col gap-1">
                    <span class="eyebrow text-[9px] opacity-60">Torque</span>
                    <span class="font-display text-2xl sm:text-3xl">${s.torque}<span class="text-[10px] text-apex-muted ms-1">NM</span></span>
                </div>
                ${enginePart}
            `;
        }

        restartAuto();
    }

    function renderTab(tab) {
        activeTab = tab;
        if (tabsBar) {
            tabsBar.querySelectorAll('[data-tab]').forEach((btn, i) => {
                btn.classList.toggle('is-active', i === tab);
            });
        }
    }

    /* ---- autoplay ---- */
    function restartAuto() {
        if (reduced) return;
        if (autoTl) autoTl.kill();
        autoTl = gsap.delayedCall(6, () => goTo(index + 1));
    }

    function pauseAuto() { if (autoTl) autoTl.pause(); }
    function resumeAuto() { if (autoTl && !reduced) autoTl.resume(); }

    /* ---- wiring ---- */
    nextBtn && nextBtn.addEventListener('click', () => goTo(index + 1));
    prevBtn && prevBtn.addEventListener('click', () => goTo(index - 1));

    // Inner tab arrows (inside the first "model name" tab)
    root.querySelectorAll('[data-hero-prev-tab]').forEach(btn => {
        btn.addEventListener('click', (e) => { e.stopPropagation(); goTo(index - 1); });
    });
    root.querySelectorAll('[data-hero-next-tab]').forEach(btn => {
        btn.addEventListener('click', (e) => { e.stopPropagation(); goTo(index + 1); });
    });

    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => goTo(i));
    });

    if (tabsBar) {
        tabsBar.querySelectorAll('[data-tab]').forEach((btn, i) => {
            btn.addEventListener('click', () => renderTab(i));
        });
    }

    root.addEventListener('mouseenter', pauseAuto);
    root.addEventListener('mouseleave', resumeAuto);

    if (!reduced) {
        window.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowRight') goTo(index + 1);
            if (e.key === 'ArrowLeft') goTo(index - 1);
        });
    }

    if (isTouch) {
        let sx = 0;
        root.addEventListener('touchstart', (e) => { sx = e.touches[0].clientX; }, { passive: true });
        root.addEventListener('touchend', (e) => {
            const dx = e.changedTouches[0].clientX - sx;
            if (Math.abs(dx) > 50) goTo(index + (dx < 0 ? 1 : -1));
        });
    }

    /* ---- parallax (desktop only) ---- */
    if (!isTouch && !reduced && bikeWrap) {
        let mx = 0, my = 0, cx = 0, cy = 0;
        root.addEventListener('mousemove', (e) => {
            const r = root.getBoundingClientRect();
            mx = (e.clientX - r.left) / r.width - 0.5;
            my = (e.clientY - r.top) / r.height - 0.5;
        });
        const loop = () => {
            cx += (mx - cx) * 0.06;
            cy += (my - cy) * 0.06;
            if (bikeWrap) bikeWrap.style.transform = `translate(${cx * 30}px, ${cy * 20}px)`;
            if (outline) outline.style.transform = `translate(${cx * -20}px, ${cy * -10}px)`;
            requestAnimationFrame(loop);
        };
        loop();
    }

    /* ---- expose + init ---- */
    window.apexHero = { next: () => goTo(index + 1), prev: () => goTo(index - 1), goTo };
    goTo(0, true);
}
