@extends('layouts.app')
@section('title', 'تواصل معنا | MOTOVEX')

@push('head')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Noto+Kufi+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    /* ─── Contact Page – Light / White Theme ─── */
    #contact-page {
        font-family: 'Plus Jakarta Sans', 'Noto Kufi Arabic', sans-serif;
        direction: rtl;
        background: #ffffff;
        color: #111827;
        min-height: 100vh;
    }

    #contact-page .cp-hero {
        background: linear-gradient(135deg, #f8f7ff 0%, #ede9fe 50%, #f5f3ff 100%);
        border-bottom: 1px solid #e9e4fd;
    }

    #contact-page .cp-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(139,92,246,0.1);
        border: 1px solid rgba(139,92,246,0.2);
        border-radius: 9999px;
        padding: 6px 16px;
        font-size: 12px;
        font-weight: 700;
        color: #6d28d9;
        letter-spacing: 0.15em;
        text-transform: uppercase;
    }

    #contact-page .cp-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 24px;
        box-shadow: 0 4px 24px rgba(109,40,217,0.06), 0 1px 4px rgba(0,0,0,0.04);
        transition: box-shadow 0.3s ease, border-color 0.3s ease;
    }

    #contact-page .cp-card:hover {
        box-shadow: 0 8px 40px rgba(109,40,217,0.12);
        border-color: #c4b5fd;
    }

    #contact-page .cp-info-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: rgba(139,92,246,0.1);
        color: #6d28d9;
        flex-shrink: 0;
        font-size: 18px;
    }

    #contact-page .cp-input {
        width: 100%;
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        background: #f9fafb;
        padding: 12px 16px;
        font-size: 14px;
        color: #111827;
        outline: none;
        transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
        font-family: inherit;
    }

    #contact-page .cp-input::placeholder { color: #9ca3af; }

    #contact-page .cp-input:focus {
        border-color: #8b5cf6;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(139,92,246,0.12);
    }

    #contact-page .cp-input.is-error {
        border-color: #ef4444;
        background: #fff5f5;
    }

    #contact-page .cp-input.is-error:focus {
        box-shadow: 0 0 0 3px rgba(239,68,68,0.12);
    }

    #contact-page .cp-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #6b7280;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    #contact-page .cp-btn {
        width: 100%;
        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        color: #ffffff;
        border: none;
        border-radius: 14px;
        padding: 16px;
        font-size: 15px;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        box-shadow: 0 8px 24px rgba(109,40,217,0.3);
        transition: all 0.35s cubic-bezier(0.16,1,0.3,1);
        letter-spacing: 0.02em;
    }

    #contact-page .cp-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(109,40,217,0.45);
    }

    #contact-page .cp-btn:active {
        transform: translateY(0);
    }

    #contact-page .cp-success {
        background: #f0fdf4;
        border: 1.5px solid #86efac;
        border-radius: 14px;
        padding: 16px 20px;
        font-size: 14px;
        color: #15803d;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    #contact-page .cp-error-msg {
        margin-top: 5px;
        font-size: 12px;
        color: #ef4444;
        font-weight: 500;
    }

    #contact-page .cp-stat-box {
        background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        border-radius: 20px;
        padding: 28px;
        color: #ffffff;
        text-align: center;
        box-shadow: 0 12px 32px rgba(109,40,217,0.25);
    }

    #contact-page .cp-stat-num {
        font-size: 2.25rem;
        font-weight: 800;
        line-height: 1;
    }

    #contact-page .cp-stat-label {
        font-size: 13px;
        margin-top: 4px;
        opacity: 0.85;
        font-weight: 500;
    }

    #contact-page .cp-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
        margin: 32px 0;
    }

    /* Reveal animation */
    #contact-page .cp-reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.7s ease, transform 0.7s ease;
    }
    #contact-page .cp-reveal.active {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endpush

@section('content')
<div id="contact-page">

    {{-- ── Hero Banner ──────────────────────────────────────────── --}}
    <div class="cp-hero py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-5 sm:px-8 text-center">
            <span class="cp-badge cp-reveal">
                <span style="width:7px;height:7px;border-radius:50%;background:#8b5cf6;display:inline-block;"></span>
                تواصل معنا
            </span>
            <h1 class="cp-reveal mt-5 text-4xl sm:text-5xl font-extrabold text-gray-900 leading-tight"
                style="transition-delay:0.1s">
                خلينا نساعدك
            </h1>
            <p class="cp-reveal mt-4 text-gray-500 text-lg max-w-xl mx-auto leading-relaxed"
               style="transition-delay:0.2s">
                فريقنا جاهز يرد على كل استفساراتك ويساعدك تختار الدراجة المناسبة.
            </p>
        </div>
    </div>

    {{-- ── Main Content ──────────────────────────────────────────── --}}
    <div class="mx-auto max-w-7xl px-5 sm:px-8 py-16">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16">

            {{-- ── Left: Info Panel ─────────────────────────── --}}
            <div class="space-y-8 cp-reveal">

                {{-- Intro text --}}
                <div>
                    <p class="text-gray-600 leading-relaxed text-base">
                        سواء كنت مهتم بموديل معين، محتاج حجز تجربة قيادة، أو عايز تعرف أكتر عن خدماتنا — فريق MOTOVEX هنا عشانك.
                    </p>
                </div>

                {{-- Contact info items --}}
                <div class="space-y-5">
                    <div class="flex items-start gap-4">
                        <div class="cp-info-icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">العنوان</p>
                            <p class="text-gray-800 font-semibold text-sm">123 Street, Cairo, Egypt</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="cp-info-icon">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">البريد الإلكتروني</p>
                            <p class="text-gray-800 font-semibold text-sm">info@motovex.com</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="cp-info-icon">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">الهاتف</p>
                            <p class="text-gray-800 font-semibold text-sm">(+012) 3456 7890</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="cp-info-icon">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">مواعيد العمل</p>
                            <p class="text-gray-800 font-semibold text-sm">الأحد – الخميس: 10ص – 8م</p>
                            <p class="text-gray-500 text-xs mt-0.5">الجمعة – السبت: 12م – 9م</p>
                        </div>
                    </div>
                </div>

                <div class="cp-divider"></div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-4">
                    <div class="cp-stat-box">
                        <div class="cp-stat-num">150+</div>
                        <div class="cp-stat-label">دراجة فاخرة</div>
                    </div>
                    <div class="cp-stat-box" style="background: linear-gradient(135deg,#7c3aed,#5b21b6);">
                        <div class="cp-stat-num">12K+</div>
                        <div class="cp-stat-label">عميل سعيد</div>
                    </div>
                    <div class="cp-stat-box" style="background: linear-gradient(135deg,#6d28d9,#4c1d95);">
                        <div class="cp-stat-num">98%</div>
                        <div class="cp-stat-label">رضا العملاء</div>
                    </div>
                </div>

                {{-- Guarantees --}}
                <div class="flex flex-wrap gap-3 pt-2">
                    @foreach(['فحص معتمد', 'ضمان حقيقي', 'دعم 24/7'] as $tag)
                    <div style="display:inline-flex;align-items:center;gap:8px;background:#f5f3ff;border:1px solid #e9d5ff;border-radius:10px;padding:8px 16px;font-size:13px;font-weight:600;color:#6d28d9;">
                        <i class="fa-solid fa-check" style="font-size:11px;"></i>
                        {{ $tag }}
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Right: Contact Form ───────────────────────── --}}
            <div class="cp-reveal" style="transition-delay:0.15s">

                {{-- Success message (global flash) --}}
                @if(session('success'))
                    <div class="cp-success mb-6">
                        <i class="fa-solid fa-circle-check" style="font-size:18px;"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="cp-card p-8 sm:p-10">
                    <h2 class="text-xl font-extrabold text-gray-900 mb-7">أرسل رسالتك</h2>

                    <form method="POST" action="{{ route('contact.store') }}" novalidate>
                        @csrf

                        {{-- Row 1: Name + Email --}}
                        <div class="grid gap-5 sm:grid-cols-2 mb-5">
                            <div>
                                <label class="cp-label">الاسم الكامل <span style="color:#ef4444">*</span></label>
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="اكتب اسمك"
                                    class="cp-input {{ $errors->has('name') ? 'is-error' : '' }}"
                                >
                                @error('name')
                                    <p class="cp-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="cp-label">البريد الإلكتروني <span style="color:#ef4444">*</span></label>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="you@example.com"
                                    class="cp-input {{ $errors->has('email') ? 'is-error' : '' }}"
                                >
                                @error('email')
                                    <p class="cp-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Row 2: Phone + Enquiry Type --}}
                        <div class="grid gap-5 sm:grid-cols-2 mb-5">
                            <div>
                                <label class="cp-label">رقم الهاتف</label>
                                <input
                                    type="tel"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    placeholder="+20 ..."
                                    class="cp-input"
                                >
                            </div>

                            <div>
                                <label class="cp-label">نوع الاستفسار <span style="color:#ef4444">*</span></label>
                                <select
                                    name="enquiry_type"
                                    class="cp-input {{ $errors->has('enquiry_type') ? 'is-error' : '' }}"
                                    style="cursor:pointer;"
                                >
                                    <option value="">اختر النوع...</option>
                                    <option value="general"    {{ old('enquiry_type') === 'general'    ? 'selected' : '' }}>استفسار عام</option>
                                    <option value="test_drive" {{ old('enquiry_type') === 'test_drive' ? 'selected' : '' }}>تجربة قيادة</option>
                                    <option value="parts"      {{ old('enquiry_type') === 'parts'      ? 'selected' : '' }}>قطع غيار</option>
                                    <option value="service"    {{ old('enquiry_type') === 'service'    ? 'selected' : '' }}>حجز صيانة</option>
                                </select>
                                @error('enquiry_type')
                                    <p class="cp-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Subject --}}
                        <div class="mb-5">
                            <label class="cp-label">الموضوع <span style="color:#ef4444">*</span></label>
                            <input
                                type="text"
                                name="subject"
                                value="{{ old('subject') }}"
                                placeholder="موضوع رسالتك"
                                class="cp-input {{ $errors->has('subject') ? 'is-error' : '' }}"
                            >
                            @error('subject')
                                <p class="cp-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Message --}}
                        <div class="mb-7">
                            <label class="cp-label">الرسالة <span style="color:#ef4444">*</span></label>
                            <textarea
                                name="message"
                                rows="5"
                                placeholder="اكتب رسالتك هنا..."
                                class="cp-input {{ $errors->has('message') ? 'is-error' : '' }}"
                                style="resize:vertical;"
                            >{{ old('message') }}</textarea>
                            @error('message')
                                <p class="cp-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="cp-btn">
                            إرسال الرسالة &nbsp;<i class="fa-solid fa-paper-plane" style="font-size:13px;"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Scroll reveal
    const cpReveals = document.querySelectorAll('#contact-page .cp-reveal');
    const cpObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                cpObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    cpReveals.forEach(el => cpObserver.observe(el));
</script>
@endpush
