<div id="preloader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-[#FFFFFF] transition-opacity duration-500">
    <div class="relative w-40 h-40">
        <svg viewBox="0 0 100 60" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
            <!-- Rear wheel -->
            <g class="wheel-spin" style="transform-origin: 30px 40px;">
                <circle cx="30" cy="40" r="13" fill="none" stroke="#2D2D2D" stroke-width="2.5" />
                <circle cx="30" cy="40" r="8.5" fill="none" stroke="#7c3aed" stroke-width="1" />
                <path d="M30 31.5 L30 48.5 M22.8 35 L37.2 45 M22.8 45 L37.2 35" stroke="#7c3aed" stroke-width="0.9" stroke-linecap="round" />
            </g>
            <!-- Front wheel -->
            <g class="wheel-spin" style="transform-origin: 70px 40px;">
                <circle cx="70" cy="40" r="13" fill="none" stroke="#2D2D2D" stroke-width="2.5" />
                <circle cx="70" cy="40" r="8.5" fill="none" stroke="#7c3aed" stroke-width="1" />
                <path d="M70 31.5 L70 48.5 M62.8 35 L77.2 45 M62.8 45 L77.2 35" stroke="#7c3aed" stroke-width="0.9" stroke-linecap="round" />
            </g>

            <!-- Front forks (angled forward) -->
            <path d="M70 40 L80 27" fill="none" stroke="#2D2D2D" stroke-width="2.5" stroke-linecap="round" />
            <!-- Swing arm -->
            <path d="M30 40 L45 34" fill="none" stroke="#2D2D2D" stroke-width="2.2" stroke-linecap="round" />

            <!-- Sportbike upper body: nose fairing → windscreen → tank → seat → raised tail -->
            <path d="M83 30 Q80 26 76 24 Q73 21 69 20 L60 19 L52 18 L44 21 L38 24 L30 23 L24 20"
                  fill="none" stroke="#2D2D2D" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
            <!-- Lower body line -->
            <path d="M80 30 L72 32 L58 32 L48 30 L42 26"
                  fill="none" stroke="#2D2D2D" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />

            <!-- Purple fairing panel (nose) -->
            <path d="M80 28 Q77 25 74 23 Q72 21 69 20 L65 20 L67 25 L76 27 Z"
                  fill="#7c3aed" stroke="#5b21b6" stroke-width="0.5" stroke-linejoin="round" />
            <!-- Windscreen -->
            <path d="M69 20 L67 16 L63 17 L65 20 Z"
                  fill="#8b5cf6" fill-opacity="0.45" stroke="#7c3aed" stroke-width="0.5" stroke-linejoin="round" />
            <!-- Fuel tank -->
            <path d="M60 19 L52 18 L46 21 L51 24 L60 22 Z"
                  fill="#2D2D2D" stroke="#1a1a1a" stroke-width="0.5" stroke-linejoin="round" />
            <!-- Tank accent line -->
            <path d="M57 19.5 L51 21" fill="none" stroke="#7c3aed" stroke-width="0.9" opacity="0.8" />

            <!-- Engine block -->
            <rect x="42" y="26" width="11" height="6" rx="1" fill="#2D2D2D" stroke="#1a1a1a" stroke-width="0.5" />
            <line x1="44" y1="29" x2="51" y2="29" stroke="#7c3aed" stroke-width="0.5" opacity="0.6" />

            <!-- Exhaust -->
            <path d="M53 32 Q43 36 29 35" fill="none" stroke="#3a3a3a" stroke-width="1.8" stroke-linecap="round" />
            <ellipse cx="28" cy="35" rx="2" ry="1.5" fill="#2D2D2D" stroke="#7c3aed" stroke-width="0.4" />

            <!-- Clip-on handlebars -->
            <path d="M75 24 L80 21" fill="none" stroke="#2D2D2D" stroke-width="1.5" stroke-linecap="round" />

            <!-- Tail light -->
            <ellipse cx="24" cy="20" rx="1.5" ry="1.2" fill="#7c3aed" />
        </svg>
    </div>
</div>

<style>
    .wheel-spin {
        animation: bike-spin 1.5s linear infinite;
    }
    @keyframes bike-spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .preloader-hidden {
        opacity: 0;
        pointer-events: none;
    }
</style>

<script>
    (function() {
        const preloader = document.getElementById('preloader');
        
        // Hide preloader when page is fully loaded
        window.addEventListener('load', function() {
            setTimeout(() => {
                preloader.classList.add('preloader-hidden');
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 500);
            }, 800); // Small delay for smooth transition
        });

        // Safety timeout in case window load event doesn't fire
        setTimeout(() => {
            if (!preloader.classList.contains('preloader-hidden')) {
                preloader.classList.add('preloader-hidden');
                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 500);
            }
        }, 5000);
    })();
</script>
