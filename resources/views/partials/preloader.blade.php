<div id="preloader" class="fixed inset-0 z-[9999] flex items-center justify-center bg-[#0b0d0e] transition-opacity duration-500">
    <div class="relative w-40 h-40">
        <svg viewBox="0 0 100 60" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
            <!-- Wheels -->
            <g class="wheel-spin" style="transform-origin: 30px 40px;">
                <circle cx="30" cy="40" r="14" fill="none" stroke="#94a3b8" stroke-width="1.5" />
                <path d="M30 26 L30 54 M16 40 L44 40" stroke="#94a3b8" stroke-width="0.8" />
            </g>
            <g class="wheel-spin" style="transform-origin: 70px 40px;">
                <circle cx="70" cy="40" r="14" fill="none" stroke="#94a3b8" stroke-width="1.5" />
                <path d="M70 26 L70 54 M56 40 L84 40" stroke="#94a3b8" stroke-width="0.8" />
            </g>
            
            <!-- Frame -->
            <path d="M30 40 L50 40 L62 22 L42 22 Z" fill="none" stroke="#D4AF37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M62 22 L70 40 M62 22 L66 18 M42 22 L40 18" fill="none" stroke="#D4AF37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
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
