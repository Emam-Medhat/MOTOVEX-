<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AURA MotoVex | تسجيل الدخول</title>

    {{-- خط Cairo --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        /* عزل صفحة الدخول عن الثيم الداكن العام للموقع */
        body::before, body::after { display: none !important; }

        body {
            font-family: 'Cairo', ui-sans-serif, system-ui, sans-serif;
            background: #07040f;
            margin: 0;
            width: 100%;
            height: 100vh;
            height: 100dvh; /* يمنع تجاوز الشاشة على الموبايل (شريط العنوان) */
            overflow: hidden;
        }
        h1, h2, h3 { font-family: 'Cairo', ui-sans-serif, system-ui, sans-serif !important; letter-spacing: normal; }

        /* ===== Ambient Corner Lights ===== */
        .corner-light {
            position: fixed;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            filter: blur(110px);
            pointer-events: none;
            z-index: 0;
            opacity: 0.5;
        }
        .corner-light-tl { top: -160px; left: -160px; background: radial-gradient(circle, rgba(168, 85, 247, 0.5) 0%, transparent 70%); animation: floatLight 20s ease-in-out infinite; }
        .corner-light-tr { top: -160px; right: -160px; background: radial-gradient(circle, rgba(236, 72, 153, 0.38) 0%, transparent 70%); animation: floatLight 24s ease-in-out infinite reverse; }
        .corner-light-bl { bottom: -160px; left: -160px; background: radial-gradient(circle, rgba(139, 92, 246, 0.42) 0%, transparent 70%); animation: floatLight 22s ease-in-out infinite; }
        .corner-light-br { bottom: -160px; right: -160px; background: radial-gradient(circle, rgba(217, 70, 239, 0.36) 0%, transparent 70%); animation: floatLight 26s ease-in-out infinite reverse; }

        @keyframes floatLight {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(45px, 35px) scale(1.12); }
        }

        /* ===== Main Container (full screen) ===== */
        .glass-main {
            position: relative;
            background: rgba(12, 7, 24, 0.85);
            overflow: hidden;
        }
        .glass-main::after {
            content: '';
            position: absolute;
            inset: 0;
            padding: 1px;
            background: linear-gradient(
                var(--border-angle, 0deg),
                rgba(168, 85, 247, 0.55),
                rgba(236, 72, 153, 0.18),
                rgba(109, 84, 183, 0.55),
                rgba(168, 85, 247, 0.18)
            );
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
            z-index: 50;
            animation: rotate-border 12s linear infinite;
        }

        @property --border-angle { syntax: '<angle>'; initial-value: 0deg; inherits: false; }
        @keyframes rotate-border { to { --border-angle: 360deg; } }

        /* ===== Login Panel Background (dark aurora) ===== */
        .login-bg {
            background:
                radial-gradient(120% 90% at 15% 10%, rgba(109, 84, 183, 0.45) 0%, transparent 55%),
                radial-gradient(100% 80% at 85% 85%, rgba(236, 72, 153, 0.28) 0%, transparent 55%),
                linear-gradient(160deg, #150c2b 0%, #0d0719 55%, #090413 100%);
        }

        /* ===== Obsidian Glass Card ===== */
        .glass-card {
            position: relative;
            border-radius: 1.5rem;
            background: linear-gradient(155deg, rgba(255, 255, 255, 0.10) 0%, rgba(255, 255, 255, 0.03) 45%, rgba(168, 85, 247, 0.06) 100%);
            backdrop-filter: blur(26px);
            -webkit-backdrop-filter: blur(26px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow:
                0 40px 90px -30px rgba(0, 0, 0, 0.85),
                0 0 70px rgba(139, 92, 246, 0.22),
                inset 0 1px 0 rgba(255, 255, 255, 0.12);
            animation: cardFloat 14s ease-in-out infinite, haloPulse 16s ease-in-out infinite;
        }
        /* Neon top hairline */
        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 8%;
            right: 8%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(217, 70, 239, 0.9), rgba(168, 85, 247, 0.9), transparent);
            opacity: 0.85;
            pointer-events: none;
        }
        /* Soft halo behind the card (box-shadow = لا يسبب تمرير خارج الشاشة) */
        @keyframes haloPulse {
            0%, 100% { box-shadow: 0 40px 90px -30px rgba(0, 0, 0, 0.85), 0 0 60px rgba(139, 92, 246, 0.20), inset 0 1px 0 rgba(255, 255, 255, 0.12); }
            50% { box-shadow: 0 40px 90px -30px rgba(0, 0, 0, 0.85), 0 0 95px rgba(168, 85, 247, 0.38), inset 0 1px 0 rgba(255, 255, 255, 0.12); }
        }

        @keyframes cardFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-9px); } }

        /* ===== Inputs (dark glass) ===== */
        .input-field {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.10);
            color: #f5f3ff;
        }
        .input-field::placeholder { color: rgba(196, 181, 253, 0.35); }
        .input-field:hover { border-color: rgba(196, 181, 253, 0.28); }
        .input-field:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(168, 85, 247, 0.85);
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.18), 0 0 22px rgba(168, 85, 247, 0.28);
            outline: none;
        }
        .input-field:-webkit-autofill,
        .input-field:-webkit-autofill:focus {
            -webkit-text-fill-color: #f5f3ff;
            -webkit-box-shadow: 0 0 0 1000px #1b1132 inset;
        }

        /* ===== Submit Button ===== */
        .aura-btn {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 50%, #5B21B6 100%);
            box-shadow: 0 18px 40px -14px rgba(124, 58, 237, 0.85);
        }
        .aura-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.28), transparent);
            transition: left 0.6s ease;
        }
        .aura-btn:hover::before { left: 100%; }
        .aura-btn:disabled { cursor: progress; opacity: 0.85; }

        /* ===== Bike Panel ===== */
        .bike-overlay {
            background:
                linear-gradient(to left, rgba(7, 4, 15, 0.85) 0%, transparent 42%),
                linear-gradient(to top, rgba(7, 4, 15, 0.8) 0%, transparent 52%),
                linear-gradient(135deg, rgba(109, 84, 183, 0.28) 0%, transparent 62%);
        }
        .bike-video { animation: bikeZoom 22s ease-in-out infinite; }
        @keyframes bikeZoom { 0%, 100% { transform: scale(1.02); } 50% { transform: scale(1.06); } }

        /* Light beam sweeping across the video */
        .light-beam {
            position: absolute;
            top: -10%;
            left: -60%;
            width: 45%;
            height: 120%;
            background: linear-gradient(90deg, transparent, rgba(217, 70, 239, 0.16), rgba(168, 85, 247, 0.12), transparent);
            transform: skewX(-22deg);
            filter: blur(28px);
            pointer-events: none;
            z-index: 11;
            animation: beamMove 20s linear infinite;
        }
        @keyframes beamMove { 0% { left: -60%; } 100% { left: 160%; } }

        /* Floating dust particles */
        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            border-radius: 50%;
            background: rgba(216, 180, 254, 0.9);
            box-shadow: 0 0 8px rgba(168, 85, 247, 0.9);
            pointer-events: none;
            z-index: 11;
            animation: particleRise linear infinite;
        }
        @keyframes particleRise {
            0% { transform: translateY(110%) translateX(0) scale(0.6); opacity: 0; }
            15% { opacity: 0.9; }
            85% { opacity: 0.9; }
            100% { transform: translateY(-40px) translateX(45px) scale(1); opacity: 0; }
        }

        /* Headline entrance + glow */
        .headline-animate { animation: headlineEntrance 1.1s cubic-bezier(0.22, 1, 0.36, 1) both; }
        @keyframes headlineEntrance {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .headline-glow { text-shadow: 0 0 26px rgba(217, 70, 239, 0.55), 0 0 55px rgba(139, 92, 246, 0.35); }

        /* Grid pattern */
        .grid-pattern {
            background-image:
                linear-gradient(rgba(168, 85, 247, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(168, 85, 247, 0.05) 1px, transparent 1px);
            background-size: 52px 52px;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #6D28D9; border-radius: 3px; }

        /* ===== Reduced Motion ===== */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
            .bike-video { transform: scale(1.02); }
        }
    </style>
</head>
<body class="m-0">

    <!-- Ambient Background -->
    <div class="corner-light corner-light-tl"></div>
    <div class="corner-light corner-light-tr"></div>
    <div class="corner-light corner-light-bl"></div>
    <div class="corner-light corner-light-br"></div>

    <!-- Main Container : full screen -->
    <div class="glass-main w-full h-full flex flex-col md:flex-row relative z-10">

        <!-- ================= Login Panel ================= -->
        <div class="login-bg w-full md:w-[52%] flex-1 min-h-0 md:h-full flex flex-col items-center p-6 sm:p-8 md:p-10 relative overflow-y-auto overflow-x-hidden">

            <div class="absolute inset-0 grid-pattern opacity-50 pointer-events-none z-0"></div>

            <!-- Main Glass Card -->
            <div class="relative w-full max-w-[430px] my-auto z-10 animate-slide-up">
                <div class="glass-card px-7 py-8 md:px-9 md:py-10">

                    <!-- Logo -->
                    <div class="flex items-center justify-center mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-linear-to-br from-purple-500 to-fuchsia-500 flex items-center justify-center shadow-lg shadow-purple-500/40">
                                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="5" cy="17" r="3"/><circle cx="19" cy="17" r="3"/>
                                    <path d="M12 17h7"/><path d="M12 10h5"/><path d="M12 10l3-4h3l3 6"/>
                                    <path d="M9 17l3-6h1"/><path d="M9 6h4"/>
                                </svg>
                            </div>
                            <div class="text-right">
                                <div class="flex items-baseline gap-1">
                                    <span class="text-[10px] font-bold text-pink-400 tracking-[0.25em] uppercase">AURA</span>
                                    <span class="text-xl font-black text-white tracking-tight">MotoVex</span>
                                </div>
                                <p class="text-[9px] text-purple-300/60 font-bold -mt-0.5 tracking-wider">PREMIUM EXPERIENCE</p>
                            </div>
                        </div>
                    </div>

                    <!-- Heading -->
                    <div class="text-center mb-8 headline-animate">
                        <h1 class="text-2xl font-black text-white mb-2">جاهز لركوب حلمك؟</h1>
                        <p class="text-xs font-bold text-purple-200/50">سجّل الدخول للوصول إلى صالة العرض الخاصة بك</p>
                    </div>

                    @if(session('status'))
                        <div class="mb-6 rounded-xl border border-purple-400/30 bg-purple-500/10 px-4 py-3 text-center text-xs font-bold text-purple-200">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-[10px] font-bold text-purple-200/60 mr-1">البريد الإلكتروني</label>
                            <div class="relative">
                                <input type="email"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       autocomplete="email"
                                       autofocus
                                       placeholder="example@domain.com"
                                       class="input-field w-full pl-4 pr-11 py-3.5 rounded-xl text-sm text-right transition-all duration-200"
                                       required>
                                <span class="absolute inset-y-0 right-4 flex items-center text-purple-300/50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                        <rect x="2.5" y="4.5" width="19" height="15" rx="3"/>
                                        <path d="m3.5 7 7.4 5.3a2 2 0 0 0 2.2 0L20.5 7" stroke-linecap="round"/>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="space-y-2">
                            <label for="password" class="block text-[10px] font-bold text-purple-200/60 mr-1">كلمة المرور</label>
                            <div class="relative">
                                <input type="password"
                                       id="password"
                                       name="password"
                                       autocomplete="current-password"
                                       placeholder="••••••••"
                                       class="input-field w-full pl-12 pr-11 py-3.5 rounded-xl text-sm text-right transition-all duration-200"
                                       required>
                                <span class="absolute inset-y-0 right-4 flex items-center text-purple-300/50">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <button type="button"
                                        id="toggle-password"
                                        aria-label="إظهار كلمة المرور"
                                        class="absolute inset-y-0 left-4 flex items-center text-purple-300/50 hover:text-fuchsia-300 transition-colors">
                                    <svg id="icon-eye-off" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M3 3l18 18" stroke-linecap="round"/>
                                        <path d="M10.6 5.1A9.7 9.7 0 0 1 12 5c5 0 9 4.5 9 7 0 .8-.5 2-1.4 3.3M6.3 6.6C4 8.2 3 10.1 3 12c0 2.5 4 7 9 7 1.5 0 2.9-.4 4.2-1" stroke-linecap="round"/>
                                    </svg>
                                    <svg id="icon-eye" class="w-4 h-4 hidden" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        @if($errors->has('email') || $errors->has('password'))
                            <div class="rounded-xl border border-rose-400/30 bg-rose-500/10 px-4 py-2.5 text-xs font-bold text-rose-300">
                                {{ $errors->first('email') ?: $errors->first('password') }}
                            </div>
                        @endif

                        <!-- Options -->
                        <div class="flex items-center justify-between text-[11px] font-bold pt-1">
                            <label class="flex items-center gap-2 cursor-pointer select-none group">
                                <input type="checkbox"
                                       name="remember"
                                       value="1"
                                       checked
                                       class="w-4 h-4 rounded border-white/20 bg-white/5 accent-[#8B5CF6] cursor-pointer">
                                <span class="text-purple-200/60 group-hover:text-purple-100 transition">البقاء متصلاً</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-fuchsia-300/80 hover:text-fuchsia-200 hover:underline transition">نسيت كلمة المرور؟</a>
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                                id="submit-btn"
                                class="aura-btn w-full py-3.5 mt-1 text-white text-sm font-black rounded-xl transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0">
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                <span id="submit-label">تسجيل الدخول</span>
                                <svg id="submit-arrow" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                                </svg>
                                <svg id="submit-spinner" class="w-4 h-4 hidden animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3" opacity="0.25"/>
                                    <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                                </svg>
                            </span>
                        </button>
                    </form>

                    <p class="text-center mt-7 text-[11px] font-bold text-purple-200/50">
                        ليس لديك حساب؟
                        <a href="{{ route('register') }}" class="text-pink-400 hover:text-pink-300 hover:underline transition">أنشئ حساباً الآن</a>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="absolute bottom-6 left-0 right-0 items-center justify-center gap-6 text-[10px] font-bold text-purple-200/30 hidden md:flex">
                <a href="{{ route('contact') }}" class="hover:text-fuchsia-300 transition">الدعم الفني</a>
                <span class="w-1 h-1 rounded-full bg-purple-300/30"></span>
                <span class="font-mono">v2.5.0 Professional</span>
                <span class="w-1 h-1 rounded-full bg-purple-300/30"></span>
                <a href="{{ route('home') }}" class="hover:text-fuchsia-300 transition">الرئيسية</a>
            </div>
        </div>

        <!-- ================= Bike Panel ================= -->
        <div class="w-full md:w-[46%] h-[18vh] min-h-[130px] shrink-0 md:h-full relative overflow-hidden bg-[#050310]">

            <video class="absolute inset-0 h-full w-full object-cover bike-video"
                   autoplay muted loop playsinline preload="auto"
                   aria-label="Kawasaki Ninja H2">
                <source src="{{ asset('media/kawasaki-ninja-h2.mp4') }}" type="video/mp4">
            </video>

            <div class="absolute inset-0 bike-overlay z-10"></div>

            <!-- Light Beam -->
            <div class="light-beam"></div>

            <!-- Particles -->
            <div class="particle" style="left: 12%; animation-duration: 16s; animation-delay: 0s;"></div>
            <div class="particle" style="left: 28%; animation-duration: 19s; animation-delay: 3s;"></div>
            <div class="particle" style="left: 44%; animation-duration: 15s; animation-delay: 6s;"></div>
            <div class="particle" style="left: 62%; animation-duration: 21s; animation-delay: 2s;"></div>
            <div class="particle" style="left: 78%; animation-duration: 17s; animation-delay: 8s;"></div>
            <div class="particle" style="left: 90%; animation-duration: 20s; animation-delay: 5s;"></div>

            <!-- Glow Orbs -->
            <div class="absolute top-1/4 -left-20 w-72 h-72 bg-purple-600/30 rounded-full blur-[100px] z-10 animate-pulse-soft"></div>
            <div class="absolute bottom-1/3 right-0 w-56 h-56 bg-fuchsia-500/20 rounded-full blur-[80px] z-10"></div>

            <!-- Brand Badge -->
            <div class="absolute top-6 right-6 z-20 flex items-center gap-2 bg-black/40 backdrop-blur-md px-4 py-2 rounded-full border border-white/10">
                <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                <span class="text-[11px] text-white/80 font-medium tracking-wide">LIVE PREVIEW</span>
            </div>

            <!-- Bottom Content -->
            <div class="absolute bottom-0 left-0 right-0 p-6 md:p-10 z-20">
                <div class="space-y-3 headline-animate">
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/15">
                        <svg class="w-3.5 h-3.5 text-aura-neon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="5" cy="17" r="3.2"/><circle cx="19" cy="17" r="3.2"/>
                            <path d="M8.2 17h7.6"/><path d="M12 10h5.5"/><path d="M12 10 15.2 6H18l2.6 6.2"/>
                            <path d="M8.2 17 11 11.4h1"/><path d="M9.6 6H13.4"/>
                        </svg>
                        <span class="text-[11px] text-white/90 font-semibold tracking-wider uppercase">Ninja H2 Custom</span>
                    </div>

                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-white leading-tight">
                        استشعر<br>
                        <span class="bg-linear-to-l from-purple-300 via-fuchsia-300 to-pink-300 bg-clip-text text-transparent headline-glow">القوة المطلقة</span>
                    </h2>

                    <p class="text-white/55 text-sm max-w-xs hidden md:block">
                        انضم إلى عالم AURA MotoVex واستكشف أرقى الدراجات المخصصة
                    </p>
                </div>
            </div>

            <!-- Decorative Line -->
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-linear-to-l from-transparent via-purple-500 to-transparent z-20"></div>
        </div>
    </div>

    <script>
        (function () {
            const pw = document.getElementById('password');
            const toggle = document.getElementById('toggle-password');
            const eye = document.getElementById('icon-eye');
            const eyeOff = document.getElementById('icon-eye-off');

            toggle?.addEventListener('click', () => {
                const show = pw.type === 'password';
                pw.type = show ? 'text' : 'password';
                eye.classList.toggle('hidden', !show);
                eyeOff.classList.toggle('hidden', show);
                toggle.setAttribute('aria-label', show ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور');
            });

            const form = document.querySelector('form');
            const btn = document.getElementById('submit-btn');
            const label = document.getElementById('submit-label');
            const arrow = document.getElementById('submit-arrow');
            const spinner = document.getElementById('submit-spinner');

            form?.addEventListener('submit', () => {
                if (!form.checkValidity()) return;
                btn.disabled = true;
                label.textContent = 'جاري الدخول...';
                arrow.classList.add('hidden');
                spinner.classList.remove('hidden');
            });
        })();
    </script>
</body>
</html>
