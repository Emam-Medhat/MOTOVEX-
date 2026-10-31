{{-- =====================================================================
     LANGUAGE SWITCHER — shared across public header, admin topbar, and
     account portal. Uses route('lang.switch') which validates against
     SetLocale::SUPPORTED and persists to session + user preference.
     Variants:
       $variant = 'light'  — for white-background headers (default)
       $variant = 'dark'   — for dark headers (auth pages, apex)
       $variant = 'admin'  — for the admin topbar (Bootstrap-friendly)
     ===================================================================== --}}
@php
    $variant = $variant ?? 'light';
    $current = app()->getLocale();

    $options = [
        'en' => ['label' => 'EN', 'full' => 'English'],
        'ar' => ['label' => 'العربية', 'full' => 'Arabic'],
    ];

    $baseClasses = 'inline-flex items-center gap-0.5 rounded-full border text-[11px] font-bold tracking-wide uppercase select-none';

    if ($variant === 'light') {
        $wrapper = $baseClasses . ' border-slate-200 bg-slate-50 text-slate-500';
        $active = 'bg-brand-600 text-white shadow-sm';
        $idle = 'text-slate-500 hover:text-brand-600';
    } elseif ($variant === 'dark') {
        $wrapper = $baseClasses . ' border-white/20 bg-white/5 text-white/70';
        $active = 'bg-white text-slate-900';
        $idle = 'text-white/70 hover:text-white';
    } else { // admin
        $wrapper = 'admin-lang-switch';
        $active = 'is-active';
        $idle = '';
    }
@endphp

<div class="{{ $variant === 'admin' ? 'admin-lang-switch' : 'inline-flex items-center gap-0.5 rounded-full border text-[11px] font-bold tracking-wide uppercase select-none ' . ($variant === 'dark' ? 'border-white/20 bg-white/5 text-white/70' : 'border-slate-200 bg-slate-50 text-slate-500') }}" role="group" aria-label="{{ __('common.switch_language') }}">
    @if($variant === 'admin')
        <style>
            .admin-lang-switch { display:inline-flex; align-items:center; gap:2px; background:#f1f5f9; border:1px solid #e2e8f0; border-radius:999px; padding:2px; }
            .admin-lang-switch a { display:inline-flex; align-items:center; padding:5px 12px; border-radius:999px; font-size:11px; font-weight:700; letter-spacing:.03em; text-transform:uppercase; color:#64748b; text-decoration:none; transition:all .18s; }
            .admin-lang-switch a:hover { color:var(--primary); }
            .admin-lang-switch span { display:inline-flex; align-items:center; padding:5px 12px; border-radius:999px; font-size:11px; font-weight:700; letter-spacing:.03em; text-transform:uppercase; background:var(--primary); color:#fff; }
        </style>
    @endif

    @foreach($options as $code => $opt)
        @if($code === $current)
            <span aria-current="true"
                  title="{{ $opt['full'] }}"
                  class="{{ $variant === 'admin' ? '' : $active . ' rounded-full px-3 py-1.5 cursor-default' }}">
                {{ $opt['label'] }}
            </span>
        @else
            <a href="{{ route('lang.switch', $code) }}"
               rel="nofollow"
               title="{{ $opt['full'] }}"
               aria-label="{{ __('common.switch_language') }}: {{ $opt['full'] }}"
               class="{{ $variant === 'admin' ? '' : $idle . ' rounded-full px-3 py-1.5 no-underline' }}">
                {{ $opt['label'] }}
            </a>
        @endif
    @endforeach
</div>
