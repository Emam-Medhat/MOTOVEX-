<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'ar' ? 'ar' : 'en' }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.merchant_pending_title') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        body {
            font-family: 'Cairo', ui-sans-serif, system-ui, sans-serif;
            background: #07040f;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .corner-light {
            position: fixed;
            width: 420px; height: 420px;
            border-radius: 50%;
            filter: blur(110px);
            pointer-events: none;
            z-index: 0;
            opacity: 0.5;
        }
        .corner-light-tl { top: -160px; left: -160px; background: radial-gradient(circle, rgba(168,85,247,0.5) 0%, transparent 70%); }
        .corner-light-br { bottom: -160px; right: -160px; background: radial-gradient(circle, rgba(217,70,239,0.36) 0%, transparent 70%); }

        .card {
            position: relative;
            z-index: 10;
            max-width: 520px;
            width: 100%;
            margin: 2rem;
            background: linear-gradient(155deg, rgba(255,255,255,0.10) 0%, rgba(255,255,255,0.03) 45%, rgba(168,85,247,0.06) 100%);
            backdrop-filter: blur(26px);
            -webkit-backdrop-filter: blur(26px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 1.5rem;
            box-shadow: 0 40px 90px -30px rgba(0,0,0,0.85), 0 0 70px rgba(139,92,246,0.22);
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .card::before {
            content: '';
            position: absolute; top: 0; left: 8%; right: 8%; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(217,70,239,0.9), rgba(168,85,247,0.9), transparent);
            opacity: 0.85; pointer-events: none;
        }

        .icon-wrap {
            width: 80px; height: 80px;
            border-radius: 1.5rem;
            background: linear-gradient(135deg, rgba(234,179,8,0.25), rgba(234,179,8,0.1));
            border: 1px solid rgba(234,179,8,0.4);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
        }

        h1 { color: #fff; font-size: 1.5rem; font-weight: 800; margin: 0 0 0.5rem; }
        .subtitle { color: rgba(196,181,253,0.6); font-size: 0.85rem; margin: 0 0 1.5rem; }

        .info-box {
            background: rgba(234,179,8,0.08);
            border: 1px solid rgba(234,179,8,0.25);
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            text-align: left;
            margin-bottom: 1.5rem;
        }

        .info-box p { color: rgba(253,224,71,0.85); font-size: 0.875rem; margin: 0; line-height: 1.6; }

        .steps {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 0.75rem;
            text-align: left;
        }

        .step-num {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #8B5CF6, #6D28D9);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 800; color: #fff;
            flex-shrink: 0;
        }

        .step-text { font-size: 12px; color: rgba(196,181,253,0.75); font-weight: 600; }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 100%);
            color: #fff;
            padding: 0.75rem 1.75rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 700;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 8px 24px -8px rgba(109,40,217,0.7);
        }
        .btn-home:hover { transform: translateY(-1px); box-shadow: 0 12px 32px -8px rgba(109,40,217,0.8); }

        .btn-login {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            color: rgba(196,181,253,0.8);
            padding: 0.75rem 1.75rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            margin-left: 0.75rem;
        }
        .btn-login:hover { background: rgba(255,255,255,0.1); color: #fff; }
    </style>
@include('partials.rtl-styles')
</head>
<body>
    <div class="corner-light corner-light-tl"></div>
    <div class="corner-light corner-light-br"></div>

    <div class="card">
        <div class="icon-wrap">
            ⏳
        </div>

        <h1>{{ __('auth.merchant_pending_heading') }}</h1>
        <p class="subtitle">
            @if(session('pending_name'))
                {{ __('auth.pending_greeting') }} <strong style="color:#e9d5ff">{{ session('pending_name') }}</strong>,
            @endif
            {{ __('auth.pending_submitted') }}
        </p>

        <div class="info-box">
            <p>
                {{ __('auth.pending_info_a') }} <strong>{{ __('auth.pending_info_bold') }}</strong>{{ __('auth.pending_info_b') }}
            </p>
        </div>

        <div class="steps">
            <div class="step">
                <div class="step-num">✓</div>
                <div class="step-text">{{ __('auth.pending_step_1') }}</div>
            </div>
            <div class="step">
                <div class="step-num" style="background: linear-gradient(135deg, #d97706, #b45309);">2</div>
                <div class="step-text">{{ __('auth.pending_step_2') }}</div>
            </div>
            <div class="step">
                <div class="step-num" style="background: rgba(255,255,255,0.1); color: rgba(196,181,253,0.5);">3</div>
                <div class="step-text" style="color: rgba(196,181,253,0.4);">{{ __('auth.pending_step_3') }}</div>
            </div>
        </div>

        <div style="display: flex; align-items: center; justify-content: center; flex-wrap: wrap; gap: 0.5rem;">
            <a href="{{ route('home') }}" class="btn-home">
                {{ __('auth.return_homepage') }}
            </a>
            <a href="{{ route('login') }}" class="btn-login">
                {{ __('auth.sign_in') }}
            </a>
        </div>

        <p style="margin-top: 1.5rem; font-size: 11px; color: rgba(196,181,253,0.35);">
            {{ __('auth.have_questions') }} <a href="{{ route('contact') }}" style="color: rgba(217,70,239,0.7); text-decoration: none;">{{ __('auth.contact_us') }}</a>
        </p>
    </div>
</body>
</html>
