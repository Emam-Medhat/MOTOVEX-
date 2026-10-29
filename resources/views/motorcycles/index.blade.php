<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motorcycle Showroom | MOTOVEX — Premium Motorcycle Group</title>
    <meta name="description" content="Browse the MOTOVEX motorcycle showroom. Filter by brand, category and engine capacity, compare specifications and pricing, then order online.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        /* Kept intact: the shared navbar / footer rely on these tokens. */
        tailwind.config = {
            darkMode: false,
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans','sans-serif'] },
                    colors: {
                        brand: { 50:'#f5f3ff', 100:'#ede9fe', 200:'#ddd6fe', 300:'#c4b5fd', 400:'#a78bfa', 500:'#8b5cf6', 600:'#7c3aed', 700:'#6d28d9', 800:'#5b21b6', 900:'#4c1d95' }
                    }
                }
            }
        };
    </script>

    @php
        $hasFilters     = request()->hasAny(['search','brand','category','min_cc','max_cc']);
        $sortKey        = request('sort', 'name');
        $catalogueCount = $brands->sum('motorcycles_count');
        $selectedBrands = (array) request('brand', []);
        $activeBrands   = $brands->whereIn('id', $selectedBrands);
        
        // Build clean catalogue URLs (no trailing "?" or "&", page always reset).
        $cleanUrl = fn (array $params) => rtrim(rtrim(request()->fullUrlWithQuery($params + ['page' => null]), '&'), '?');
        $drop     = fn (array $keys) => $cleanUrl(array_fill_keys($keys, null));
        $set      = fn (array $params) => $cleanUrl($params);

        $catIcons = [
            'urban'      => 'fa-city',
            'sport'      => 'fa-gauge-high',
            'adventure'  => 'fa-mountain-sun',
            'cruiser'    => 'fa-road',
            'touring'    => 'fa-map-location-dot',
            'off-road'   => 'fa-hill-rockslide',
            'heritage'   => 'fa-khanda',
            'electric'   => 'fa-bolt',
            'scooter'    => 'fa-moped',
            'naked'      => 'fa-motorcycle'
        ];
    @endphp

    <style>
        /* =====================================================================
           MOTOVEX SHOWROOM — page-scoped design system (all classes .mvx-*)
           Premium motorcycle dealership aesthetic. Light surfaces, deep navy
           type, refined purple accents. Enhanced animations and hover states.
           v2 — wider layout, larger cards, polished interactions.
           ===================================================================== */
        :root{
            --mvx-page:#F5F7FC;
            --mvx-surface:#FFFFFF;
            --mvx-ink:#0F172A;
            --mvx-body:#172033;
            --mvx-muted:#64748B;
            --mvx-faint:#94A3B8;
            --mvx-line:#E2E8F0;
            --mvx-line-soft:#EEF2F7;
            --mvx-brand:#7C3AED;
            --mvx-brand-2:#6D28D9;
            --mvx-brand-light:#8B5CF6;
            --mvx-soft:#F5F3FF;
            --mvx-soft-line:#E9D5FF;
            --mvx-well-1:#0F172A;
            --mvx-well-2:#1E293B;
            --mvx-radius:20px;
            --mvx-radius-sm:14px;
            --mvx-shadow:0 1px 3px rgba(15,23,42,.04), 0 8px 24px -12px rgba(15,23,42,.08);
            --mvx-shadow-md:0 4px 12px -4px rgba(15,23,42,.06), 0 12px 36px -16px rgba(15,23,42,.10);
            --mvx-shadow-lg:0 24px 60px -24px rgba(124,58,237,.28), 0 10px 24px -10px rgba(15,23,42,.12);
            --mvx-image-bg:radial-gradient(ellipse at 50% 40%, #1E293B 0%, #0F172A 65%, #0B1120 100%);
        }

        html,body{ background:var(--mvx-page); }
        body{
            color:var(--mvx-body);
            font-family:'Plus Jakarta Sans',sans-serif;
            overflow-x:hidden;
            -webkit-font-smoothing:antialiased;
            text-rendering:optimizeLegibility;
        }

        ::-webkit-scrollbar{ width:8px; height:8px; }
        ::-webkit-scrollbar-track{ background:#F1F5F9; }
        ::-webkit-scrollbar-thumb{ background:#CBD5E1; border-radius:4px; }
        ::-webkit-scrollbar-thumb:hover{ background:var(--mvx-brand); }

        /* Focus identity */
        a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible{
            outline:2px solid var(--mvx-brand);
            outline-offset:2px;
            border-radius:8px;
        }

        /* ---------------- Shared container ---------------- */
        .mvx-container{
            max-width:1600px;
            margin-inline:auto;
            padding-inline:1rem;
        }
        @media(min-width:640px){  .mvx-container{ padding-inline:1.5rem; } }
        @media(min-width:1024px){ .mvx-container{ padding-inline:2rem; } }
        @media(min-width:1280px){ .mvx-container{ padding-inline:2.5rem; } }
        @media(min-width:1600px){ .mvx-container{ padding-inline:3rem; } }

        /* ---------------- Category rail ---------------- */
        .mvx-rail{ background:var(--mvx-surface); border-bottom:1px solid var(--mvx-line); }
        .mvx-rail-inner{ display:flex; align-items:center; gap:.5rem; padding:.7rem 0; overflow-x:auto; scrollbar-width:none; }
        .mvx-rail-inner::-webkit-scrollbar{ display:none; }
        .mvx-rail-label{ flex:0 0 auto; font-size:.6rem; font-weight:800; letter-spacing:.16em; text-transform:uppercase; color:var(--mvx-faint); padding-right:.35rem; }
        .mvx-chip{
            flex:0 0 auto; display:inline-flex; align-items:center; gap:.35rem;
            padding:.45rem .85rem; border-radius:999px;
            border:1px solid var(--mvx-line); background:var(--mvx-surface);
            font-size:.74rem; font-weight:600; color:var(--mvx-muted); text-decoration:none; white-space:nowrap;
            transition:border-color .2s, color .2s, background-color .2s, box-shadow .2s, transform .2s;
        }
        .mvx-chip:hover{ border-color:var(--mvx-soft-line); color:var(--mvx-brand-2); background:var(--mvx-soft); transform:translateY(-1px); }
        .mvx-chip.is-active{ background:var(--mvx-brand); border-color:var(--mvx-brand); color:#fff; box-shadow:0 8px 18px -12px rgba(124,58,237,.9); }

        /* ---------------- Showroom intro ---------------- */
        .mvx-intro{ padding:1.25rem 0 1rem; }
        .mvx-crumbs{ display:flex; align-items:center; gap:.45rem; font-size:.7rem; color:var(--mvx-faint); }
        .mvx-crumbs a{ display:inline-flex; align-items:center; gap:.35rem; color:var(--mvx-muted); text-decoration:none; transition:color .18s; }
        .mvx-crumbs a:hover{ color:var(--mvx-brand); }
        .mvx-crumbs i{ font-size:.6rem; }
        .mvx-crumbs [aria-current]{ color:var(--mvx-ink); font-weight:700; }
        .mvx-title{
            margin:.4rem 0 0; display:flex; align-items:center; gap:.7rem; flex-wrap:wrap;
            font-size:clamp(1.5rem,3.5vw,2.2rem); font-weight:800; letter-spacing:-.025em; line-height:1.1; color:var(--mvx-ink);
        }
        .mvx-count-pill{
            display:inline-flex; align-items:baseline; gap:.3rem;
            padding:.25rem .6rem; border-radius:999px;
            background:var(--mvx-soft); border:1px solid var(--mvx-soft-line);
            color:var(--mvx-brand-2); font-size:.65rem; font-weight:700;
        }
        .mvx-count-pill strong{ font-size:.78rem; font-weight:800; }
        .mvx-sub{ margin-top:.45rem; max-width:65ch; font-size:.82rem; line-height:1.6; color:var(--mvx-muted); }
        .mvx-chips{ display:flex; flex-wrap:wrap; align-items:center; gap:.4rem; }
        .mvx-fchip{
            display:inline-flex; align-items:center; gap:.35rem;
            padding:.28rem .6rem; border-radius:999px; text-decoration:none;
            background:var(--mvx-soft); border:1px solid var(--mvx-soft-line);
            color:var(--mvx-brand-2); font-size:.68rem; font-weight:700; transition:background-color .15s, transform .15s;
        }
        .mvx-fchip:hover{ background:#EDE9FE; transform:translateY(-1px); }
        .mvx-fchip i{ font-size:.6rem; }
        .mvx-fchip .fa-xmark{ opacity:.65; }
        .mvx-clearall{ font-size:.7rem; font-weight:700; color:var(--mvx-muted); text-decoration:underline; text-underline-offset:2px; }
        .mvx-clearall:hover{ color:#DC2626; }

        /* ---------------- Trust strip ---------------- */
        .mvx-trust{ background:var(--mvx-surface); border-top:1px solid var(--mvx-line); border-bottom:1px solid var(--mvx-line); }
        .mvx-trust-list{ display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); list-style:none; margin:0; padding:0; }
        @media(min-width:1024px){ .mvx-trust-list{ grid-template-columns:repeat(4,minmax(0,1fr)); } }
        .mvx-trust-item{ display:flex; align-items:center; gap:.65rem; padding:.85rem .1rem; min-width:0; }
        .mvx-trust-ico{
            flex:0 0 auto; width:38px; height:38px; border-radius:11px;
            display:grid; place-items:center; font-size:.82rem;
            background:var(--mvx-soft); border:1px solid var(--mvx-soft-line); color:var(--mvx-brand);
        }
        .mvx-trust-txt{ min-width:0; font-size:.73rem; line-height:1.35; color:var(--mvx-muted); }
        .mvx-trust-txt strong{ display:block; font-size:.78rem; font-weight:800; color:var(--mvx-ink); }

        /* ---------------- Filter panel ---------------- */
        .mvx-panel{
            background:var(--mvx-surface); border:1px solid var(--mvx-line);
            border-radius:var(--mvx-radius); box-shadow:var(--mvx-shadow); overflow:hidden;
        }
        @media(min-width:1024px){ .mvx-panel{ position:sticky; top:5.25rem; max-height:calc(100vh - 6.5rem); overflow-y:auto; } }
        @media(max-width:1023px){
            .mvx-panel:not(.is-open){ display:none; }
        }
        .mvx-panel.is-open{
            animation:mvx-slide-down .35s cubic-bezier(.16,1,.3,1);
        }
        @keyframes mvx-slide-down{
            from{ opacity:0; transform:translateY(-10px); }
            to{ opacity:1; transform:translateY(0); }
        }
        .mvx-panel-head{
            display:flex; align-items:center; justify-content:space-between; gap:.5rem;
            padding:.85rem 1.1rem; border-bottom:1px solid var(--mvx-line);
        }
        .mvx-panel-title{ margin:0; display:flex; align-items:center; gap:.5rem; font-size:.8rem; font-weight:800; color:var(--mvx-ink); letter-spacing:.01em; }
        .mvx-panel-title i{ color:var(--mvx-brand); }
        .mvx-reset-link{ font-size:.68rem; font-weight:700; color:var(--mvx-brand); text-decoration:none; transition:color .15s; }
        .mvx-reset-link:hover{ color:var(--mvx-brand-2); text-decoration:underline; }
        .mvx-fset{ margin:0; border:0; padding:.75rem 1.1rem; border-bottom:1px solid var(--mvx-line-soft); }
        .mvx-legend{ padding:0; margin-bottom:.45rem; font-size:.6rem; font-weight:800; letter-spacing:.12em; text-transform:uppercase; color:var(--mvx-faint); }
        .mvx-opt{ display:flex; align-items:center; gap:.45rem; padding:.28rem .35rem; border-radius:9px; cursor:pointer; transition:background-color .15s; }
        .mvx-opt:hover{ background:var(--mvx-soft); }
        .mvx-opt input[type=radio], .mvx-opt input[type=checkbox]{
            -webkit-appearance:none; appearance:none; flex:0 0 auto;
            width:16px; height:16px; margin:0; border-radius:50%;
            border:1.5px solid #CBD5E1; background:#fff;
            display:grid; place-items:center; cursor:pointer;
            transition:border-color .15s, background-color .15s;
        }
        .mvx-opt input[type=checkbox]{ border-radius:4px; }
        .mvx-opt input[type=radio]::after{
            content:""; width:7px; height:7px; border-radius:50%;
            background:var(--mvx-brand); transform:scale(0); transition:transform .15s;
        }
        .mvx-opt input[type=checkbox]::after{
            content:"\f00c"; font-family:"Font Awesome 6 Free"; font-weight:900;
            font-size:9px; color:#fff; transform:scale(0); transition:transform .15s;
        }
        .mvx-opt input[type=radio]:checked{ border-color:var(--mvx-brand); }
        .mvx-opt input[type=radio]:checked::after{ transform:scale(1); }
        .mvx-opt input[type=checkbox]:checked{ border-color:var(--mvx-brand); background:var(--mvx-brand); }
        .mvx-opt input[type=checkbox]:checked::after{ transform:scale(1); }
        .mvx-opt-label{ font-size:.77rem; font-weight:600; color:#334155; }
        .mvx-opt-count{ margin-left:auto; padding:.05rem .42rem; border-radius:999px; background:#F1F5F9; font-size:.62rem; font-weight:700; color:var(--mvx-muted); }
        .mvx-cc-row{ display:flex; align-items:center; gap:.5rem; }
        .mvx-cc-row span{ color:var(--mvx-faint); font-size:.72rem; }
        .mvx-input{
            width:100%; min-width:0; border:1px solid var(--mvx-line); background:#F8FAFC;
            border-radius:10px; padding:.5rem .6rem; font-size:.75rem; color:var(--mvx-ink); outline:none;
            transition:border-color .15s, background-color .15s, box-shadow .15s;
        }
        .mvx-input:focus{ border-color:var(--mvx-brand); background:#fff; box-shadow:0 0 0 3px rgba(124,58,237,.12); }
        .mvx-filter-foot{ padding:.9rem 1.1rem 1.1rem; display:flex; flex-direction:column; gap:.5rem; }
        .mvx-filter-toggle{
            width:100%; display:flex; align-items:center; justify-content:space-between; gap:.5rem;
            padding:.8rem 1.1rem; margin-bottom:.85rem; border-radius:14px;
            background:var(--mvx-surface); border:1px solid var(--mvx-line); box-shadow:var(--mvx-shadow);
            font-size:.82rem; font-weight:700; color:var(--mvx-ink); cursor:pointer;
            transition:border-color .18s, box-shadow .18s;
        }
        .mvx-filter-toggle:hover{ border-color:var(--mvx-soft-line); box-shadow:0 6px 18px -10px rgba(124,58,237,.3); }
        .mvx-filter-toggle i:first-child{ color:var(--mvx-brand); margin-right:.5rem; }
        .mvx-filter-toggle .fa-chevron-down{ font-size:.7rem; color:var(--mvx-faint); transition:transform .25s; }
        .mvx-filter-toggle[aria-expanded="true"] .fa-chevron-down{ transform:rotate(180deg); }

        /* ---------------- Buttons ---------------- */
        .mvx-btn{
            display:inline-flex; align-items:center; justify-content:center; gap:.45rem;
            padding:.62rem 1rem; border-radius:11px; border:1px solid transparent;
            font-size:.76rem; font-weight:700; text-decoration:none; cursor:pointer;
            transition:background-color .2s, color .2s, border-color .2s, box-shadow .2s, transform .15s;
        }
        .mvx-btn-primary{ background:var(--mvx-brand); color:#fff; box-shadow:0 10px 22px -12px rgba(124,58,237,.9); }
        .mvx-btn-primary:hover{ background:var(--mvx-brand-2); transform:translateY(-2px); box-shadow:0 14px 28px -10px rgba(124,58,237,.7); }
        .mvx-btn-primary:active{ transform:translateY(0) scale(.97); box-shadow:0 6px 14px -8px rgba(124,58,237,.6); }
        .mvx-btn-ghost{ background:var(--mvx-surface); color:var(--mvx-ink); border-color:var(--mvx-line); }
        .mvx-btn-ghost:hover{ border-color:var(--mvx-soft-line); color:var(--mvx-brand-2); background:var(--mvx-soft); transform:translateY(-1px); }
        .mvx-btn-ghost:active{ transform:translateY(0) scale(.97); }
        .mvx-btn-block{ width:100%; }
        .mvx-btn:disabled, .mvx-btn[aria-busy="true"]{ opacity:.55; cursor:not-allowed; transform:none !important; box-shadow:none !important; }

        /* ---------------- Toolbar ---------------- */
        .mvx-toolbar{
            display:flex; flex-wrap:wrap; align-items:center; gap:.65rem;
            padding:.8rem 1rem; margin-bottom:1.25rem;
            background:var(--mvx-surface); border:1px solid var(--mvx-line);
            border-radius:var(--mvx-radius); box-shadow:var(--mvx-shadow);
        }
        .mvx-toolbar-form{ display:flex; flex-wrap:wrap; align-items:center; gap:.5rem; flex:1 1 340px; min-width:0; }
        .mvx-search{ position:relative; flex:1 1 200px; min-width:0; }
        .mvx-search > i{ position:absolute; left:.7rem; top:50%; transform:translateY(-50%); font-size:.78rem; color:var(--mvx-faint); pointer-events:none; }
        .mvx-search input{
            width:100%; border:1px solid var(--mvx-line); background:#F8FAFC; border-radius:12px;
            padding:.62rem .75rem .62rem 2.1rem; font-size:.8rem; color:var(--mvx-ink); outline:none;
            transition:border-color .15s, background-color .15s, box-shadow .15s;
        }
        .mvx-search input::placeholder{ color:var(--mvx-faint); }
        .mvx-search input:focus{ border-color:var(--mvx-brand); background:#fff; box-shadow:0 0 0 3px rgba(124,58,237,.12); }
        .mvx-select{ position:relative; flex:0 0 auto; display:inline-flex; align-items:center; }
        .mvx-select > i{ position:absolute; left:.7rem; font-size:.72rem; color:var(--mvx-faint); pointer-events:none; }
        .mvx-select select{
            -webkit-appearance:none; appearance:none; cursor:pointer; outline:none;
            border:1px solid var(--mvx-line); background:#F8FAFC; border-radius:12px;
            padding:.62rem 1.9rem .62rem 1.85rem; font-size:.76rem; font-weight:600; color:var(--mvx-ink);
            transition:border-color .15s, background-color .15s, box-shadow .15s;
        }
        .mvx-select select:focus{ border-color:var(--mvx-brand); background:#fff; box-shadow:0 0 0 3px rgba(124,58,237,.12); }
        .mvx-select::after{
            content:""; position:absolute; right:.8rem; width:6px; height:6px; pointer-events:none;
            border-right:1.5px solid var(--mvx-faint); border-bottom:1.5px solid var(--mvx-faint);
            transform:translateY(-2px) rotate(45deg);
        }
        .mvx-result-count{ flex:1 1 auto; font-size:.73rem; color:var(--mvx-muted); white-space:nowrap; }
        .mvx-result-count strong{ color:var(--mvx-ink); font-weight:800; }
        .mvx-viewtoggle{ display:inline-flex; gap:.2rem; padding:.2rem; background:#F1F5F9; border:1px solid var(--mvx-line); border-radius:12px; }
        .mvx-viewbtn{
            width:34px; height:30px; display:grid; place-items:center;
            border:0; border-radius:9px; background:transparent; color:var(--mvx-muted);
            font-size:.78rem; cursor:pointer; transition:background-color .15s, color .15s, box-shadow .15s;
        }
        .mvx-viewbtn:hover{ color:var(--mvx-ink); }
        .mvx-viewbtn.is-active{ background:#fff; color:var(--mvx-brand); box-shadow:0 1px 3px rgba(15,23,42,.14); }

        /* ---------------- Product cards (premium v2) ---------------- */
        .mvx-grid{ display:grid; grid-template-columns:1fr; gap:1.25rem; }
        @media(min-width:560px){ .mvx-grid{ grid-template-columns:repeat(2,minmax(0,1fr)); } }
        @media(min-width:1024px){ .mvx-grid{ grid-template-columns:repeat(3,minmax(0,1fr)); } }
        @media(min-width:1600px){ .mvx-grid{ grid-template-columns:repeat(4,minmax(0,1fr)); } }

        .mvx-card{
            position:relative; display:flex; flex-direction:column; overflow:hidden;
            background:var(--mvx-surface); border:1px solid var(--mvx-line);
            border-radius:var(--mvx-radius); box-shadow:var(--mvx-shadow);
            opacity:0; transform:translateY(28px);
            transition:opacity .55s cubic-bezier(.16,1,.3,1),
                       transform .55s cubic-bezier(.16,1,.3,1);
            transition-delay:calc(var(--stagger,0) * 70ms);
        }
        .mvx-card.is-visible{
            opacity:1; transform:translateY(0);
            transition:transform .35s cubic-bezier(.16,1,.3,1),
                       box-shadow .35s ease, border-color .35s ease;
            transition-delay:0s;
        }
        .mvx-card.is-visible:hover{
            transform:translateY(-8px);
            box-shadow:var(--mvx-shadow-lg);
            border-color:var(--mvx-soft-line);
        }

        /* Card media — premium studio backdrop with contained image */
        .mvx-card-media{ position:relative; display:block; aspect-ratio:16/11; overflow:hidden; background:var(--mvx-image-bg); }
        .mvx-card-img{
            display:block; width:100%; height:100%; object-fit:contain; padding:14px 20px;
            transition:transform .55s cubic-bezier(.16,1,.3,1), filter .35s ease;
            filter:drop-shadow(0 8px 20px rgba(0,0,0,.3));
        }
        .mvx-card.is-visible:hover .mvx-card-img{ transform:scale(1.07); filter:drop-shadow(0 12px 28px rgba(0,0,0,.4)); }
        .mvx-noimg{ position:absolute; inset:0; display:grid; place-content:center; gap:.45rem; text-align:center; color:#475569; font-size:.68rem; font-weight:600; }
        .mvx-noimg i{ font-size:1.9rem; color:#334155; }
        .mvx-card-scrim{ position:absolute; inset:0; pointer-events:none; background:linear-gradient(to top, rgba(2,6,23,.5) 0%, rgba(2,6,23,.06) 40%, transparent 65%); opacity:.6; transition:opacity .35s ease; }
        .mvx-card.is-visible:hover .mvx-card-scrim{ opacity:.85; }
        .mvx-badges{ position:absolute; top:.7rem; left:.7rem; right:.7rem; display:flex; flex-wrap:wrap; gap:.32rem; z-index:1; }
        .mvx-badge{
            display:inline-flex; align-items:center; gap:.28rem;
            padding:.22rem .5rem; border-radius:999px;
            font-size:.58rem; font-weight:800; letter-spacing:.07em; text-transform:uppercase;
            -webkit-backdrop-filter:blur(8px); backdrop-filter:blur(8px);
            transition:transform .2s ease;
        }
        .mvx-card.is-visible:hover .mvx-badge{ transform:translateY(-1px); }
        .mvx-badge-feat{ background:rgba(124,58,237,.92); color:#fff; }
        .mvx-badge-ok{ background:rgba(16,185,129,.92); color:#fff; }
        .mvx-badge-warn{ background:rgba(245,158,11,.92); color:#1F2937; }
        .mvx-badge-bad{ background:rgba(239,68,68,.92); color:#fff; }
        .mvx-badge-neutral{ background:rgba(203,213,225,.92); color:#0F172A; }

        /* Card body */
        .mvx-card-body{ display:flex; flex:1; flex-direction:column; gap:.6rem; padding:1.1rem 1.2rem 1.2rem; }
        .mvx-card-brand{ margin:0; font-size:.6rem; font-weight:800; letter-spacing:.16em; text-transform:uppercase; color:var(--mvx-brand); }
        .mvx-card-title{ margin:0; font-size:1rem; font-weight:800; line-height:1.3; letter-spacing:-.01em; color:var(--mvx-ink); }
        .mvx-card-title a{ color:inherit; text-decoration:none; transition:color .18s; }
        .mvx-card-title a:hover{ color:var(--mvx-brand); }
        .mvx-card-desc{ margin:0; font-size:.75rem; line-height:1.5; color:var(--mvx-muted); display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical; overflow:hidden; }
        .mvx-specs{ display:flex; flex-wrap:wrap; gap:.32rem; margin:0; padding:0; list-style:none; }
        .mvx-specs li{
            padding:.2rem .46rem; border-radius:8px; background:#F1F5F9;
            border:1px solid var(--mvx-line-soft);
            font-size:.63rem; font-weight:600; color:#475569;
            transition:background-color .18s, border-color .18s;
        }
        .mvx-card.is-visible:hover .mvx-specs li{ background:var(--mvx-soft); border-color:var(--mvx-soft-line); color:var(--mvx-brand-2); }
        .mvx-card-foot{ margin-top:auto; padding-top:.85rem; border-top:1px solid var(--mvx-line-soft); display:flex; flex-direction:column; gap:.65rem; }
        .mvx-price{ display:flex; align-items:baseline; justify-content:space-between; gap:.5rem; }
        .mvx-price small{ font-size:.6rem; font-weight:800; letter-spacing:.09em; text-transform:uppercase; color:var(--mvx-faint); }
        .mvx-price strong{ display:inline-flex; align-items:baseline; gap:.28rem; font-size:1.1rem; font-weight:800; letter-spacing:-.015em; color:var(--mvx-ink); }
        .mvx-price strong span{ font-size:.6rem; font-weight:800; letter-spacing:.07em; color:var(--mvx-muted); }
        .mvx-price .mvx-price-req{ font-size:.8rem; font-weight:700; color:var(--mvx-muted); }
        .mvx-card-actions{ display:flex; gap:.5rem; }
        .mvx-card-actions > *{ flex:1 1 0; min-width:0; }
        .mvx-card-actions .mvx-order-form{ display:flex; }
        .mvx-card-actions .mvx-btn{ padding:.62rem .5rem; font-size:.73rem; white-space:nowrap; }

        /* ---------------- List rows ---------------- */
        .mvx-list{ display:flex; flex-direction:column; gap:1rem; }
        .mvx-row{
            display:flex; flex-direction:column; gap:.95rem; padding:1rem;
            background:var(--mvx-surface); border:1px solid var(--mvx-line);
            border-radius:var(--mvx-radius); box-shadow:var(--mvx-shadow);
            opacity:0; transform:translateY(20px);
            transition:opacity .5s cubic-bezier(.16,1,.3,1), transform .5s cubic-bezier(.16,1,.3,1);
            transition-delay:calc(var(--stagger,0) * 60ms);
        }
        .mvx-row.is-visible{
            opacity:1; transform:translateY(0);
            transition:transform .35s cubic-bezier(.16,1,.3,1),
                       box-shadow .35s ease, border-color .35s ease;
            transition-delay:0s;
        }
        .mvx-row.is-visible:hover{ transform:translateY(-3px); border-color:var(--mvx-soft-line); box-shadow:var(--mvx-shadow-lg); }
        @media(min-width:768px){ .mvx-row{ flex-direction:row; align-items:center; gap:1.2rem; } }
        .mvx-row-media{ position:relative; display:block; flex:0 0 auto; width:100%; aspect-ratio:16/10; overflow:hidden; border-radius:var(--mvx-radius-sm); background:var(--mvx-image-bg); }
        @media(min-width:768px){ .mvx-row-media{ width:280px; aspect-ratio:4/3; } }
        .mvx-row-media img{ display:block; width:100%; height:100%; object-fit:contain; padding:10px; transition:transform .5s cubic-bezier(.16,1,.3,1); }
        .mvx-row.is-visible:hover .mvx-row-media img{ transform:scale(1.05); }
        .mvx-row-main{ flex:1 1 auto; min-width:0; }
        .mvx-row-side{ flex:0 0 auto; display:flex; flex-direction:column; gap:.6rem; align-items:flex-start; }
        @media(min-width:768px){ .mvx-row-side{ min-width:220px; align-items:flex-end; text-align:right; } }
        .mvx-row-side .mvx-card-actions{ width:100%; }

        /* ---------------- Empty state ---------------- */
        .mvx-empty{
            padding:4rem 1.5rem; text-align:center;
            background:var(--mvx-surface); border:1px solid var(--mvx-line);
            border-radius:var(--mvx-radius); box-shadow:var(--mvx-shadow);
        }
        .mvx-empty-art{
            width:92px; height:92px; margin:0 auto 1.1rem; border-radius:50%;
            display:grid; place-items:center; font-size:2.1rem; color:var(--mvx-brand);
            background:radial-gradient(circle at 50% 38%, var(--mvx-soft), #fff 72%);
            border:1px solid var(--mvx-soft-line);
            animation:mvx-float 3s ease-in-out infinite;
        }
        @keyframes mvx-float{
            0%,100%{ transform:translateY(0); }
            50%{ transform:translateY(-5px); }
        }
        .mvx-empty h2{ margin:0; font-size:1.1rem; font-weight:800; color:var(--mvx-ink); }
        .mvx-empty p{ margin:.55rem auto 0; max-width:46ch; font-size:.8rem; line-height:1.6; color:var(--mvx-muted); }
        .mvx-empty-actions{ margin-top:1.2rem; display:flex; flex-wrap:wrap; gap:.55rem; justify-content:center; }

        /* ---------------- Pagination ---------------- */
        .mvx-pager{ display:flex; flex-wrap:wrap; justify-content:center; gap:.42rem; margin-top:2rem; }
        .mvx-page{
            display:inline-flex; align-items:center; justify-content:center;
            min-width:40px; height:40px; padding:0 .65rem; border-radius:12px;
            border:1px solid var(--mvx-line); background:var(--mvx-surface);
            color:#334155; font-size:.77rem; font-weight:700; text-decoration:none;
            transition:border-color .18s, color .18s, background-color .18s, transform .15s, box-shadow .18s;
        }
        a.mvx-page:hover{ border-color:var(--mvx-soft-line); color:var(--mvx-brand-2); background:var(--mvx-soft); transform:translateY(-1px); box-shadow:0 6px 14px -8px rgba(124,58,237,.3); }
        .mvx-page.is-active{ background:var(--mvx-brand); border-color:var(--mvx-brand); color:#fff; box-shadow:0 8px 16px -8px rgba(124,58,237,.6); }
        .mvx-page.is-disabled{ color:#CBD5E1; cursor:not-allowed; }

        /* ---------------- Toast ---------------- */
        .mvx-toast{
            position:fixed; left:50%; bottom:1.5rem; z-index:80;
            max-width:min(92vw,26rem); padding:.82rem 1.1rem; border-radius:13px;
            background:var(--mvx-ink); color:#fff; font-size:.79rem; font-weight:600;
            box-shadow:0 24px 50px -22px rgba(15,23,42,.65);
            opacity:0; pointer-events:none; transform:translate(-50%,1.4rem);
            transition:opacity .3s, transform .3s;
        }
        .mvx-toast.is-visible{ opacity:1; transform:translate(-50%,0); }
        .mvx-toast.is-success{ background:#065F46; }
        .mvx-toast.is-error{ background:#991B1B; }
        .mvx-toast.is-info{ background:#1E3A8A; }

        /* ---------------- Reduced motion ---------------- */
        @media(prefers-reduced-motion: reduce){
            .mvx-card, .mvx-card.is-visible, .mvx-row, .mvx-row.is-visible{
                opacity:1 !important;
                transform:none !important;
                transition:none !important;
                transition-delay:0s !important;
            }
            .mvx-card-img, .mvx-row-media img, .mvx-toast, .mvx-btn, .mvx-chip, .mvx-empty-art{
                transition:none !important;
                animation:none !important;
            }
            .mvx-card:hover, .mvx-row:hover, .mvx-btn-primary:hover, .mvx-btn-ghost:hover, .mvx-chip:hover{
                transform:none !important;
            }
        }
    </style>
</head>
<body>

@include('partials.navbar')

{{-- ================= 1. CATEGORY RAIL ================= --}}
<nav class="mvx-rail" aria-label="Browse motorcycles by category">
    <div class="mvx-container">
        <div class="mvx-rail-inner">
            <span class="mvx-rail-label">Browse</span>
            <a href="{{ $drop(['category']) }}" class="mvx-chip {{ ! request('category') ? 'is-active' : '' }}"
               @if(! request('category')) aria-current="true" @endif
                <i class="fa-solid fa-motorcycle" aria-hidden="true"></i> All Models
            </a>
            @foreach($categories as $cat)
                @php $catSlug = strtolower($cat); @endphp
                <a href="{{ $set(['category' => $cat]) }}"
                   class="mvx-chip {{ request('category') === $cat ? 'is-active' : '' }}"
                   @if(request('category') === $cat) aria-current="true" @endif>
                    @if(isset($catIcons[$catSlug]))
                        <i class="fa-solid {{ $catIcons[$catSlug] }}" aria-hidden="true"></i>
                    @else
                        <i class="fa-solid fa-tag" aria-hidden="true"></i>
                    @endif
                    {{ ucfirst($cat) }}
                </a>
            @endforeach
        </div>
    </div>
</nav>

{{-- ================= 2. SHOWROOM INTRO ================= --}}
<section class="mvx-intro">
    <div class="mvx-container">
        <nav class="mvx-crumbs" aria-label="Breadcrumb">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house" aria-hidden="true"></i> Home</a>
            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
            <span aria-current="page">Motorcycle Showroom</span>
        </nav>

        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="min-w-0">
                <h1 class="mvx-title">
                    Motorcycle Showroom
                    <span class="mvx-count-pill">
                        <strong>{{ $catalogueCount }}</strong> {{ $catalogueCount === 1 ? 'model' : 'models' }}
                    </span>
                </h1>
                <p class="mvx-sub">
                    Browse the MOTOVEX catalogue by brand, category and engine capacity. Compare
                    specifications and pricing, then order online or speak with our team.
                </p>
            </div>

            @if($hasFilters)
                <div class="mvx-chips lg:justify-end">
                    @if(request('search'))
                        <a class="mvx-fchip" href="{{ $drop(['search']) }}">
                            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                            {{ \Illuminate\Support\Str::limit(request('search'), 24) }}
                            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                        </a>
                    @endif
                    @if(request('category'))
                        <a class="mvx-fchip" href="{{ $drop(['category']) }}">
                            {{ ucfirst(request('category')) }}
                            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                        </a>
                    @endif
                    @foreach($activeBrands as $ab)
                        <a class="mvx-fchip" href="{{ $cleanUrl(['brand' => array_diff($selectedBrands, [$ab->id])]) }}">
                            {{ $ab->name }}
                            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                        </a>
                    @endforeach
                    @if(request('min_cc') || request('max_cc'))
                        <a class="mvx-fchip" href="{{ $drop(['min_cc','max_cc']) }}">
                            {{ request('min_cc') ?: '0' }}–{{ request('max_cc') ?: '∞' }} cc
                            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                        </a>
                    @endif
                    <a class="mvx-clearall" href="{{ route('motorcycles.index') }}">Clear all</a>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ================= 3. TRUST STRIP (real data only) ================= --}}
<section class="mvx-trust" aria-label="Showroom facts">
    <div class="mvx-container">
        <ul class="mvx-trust-list">
            <li class="mvx-trust-item">
                <span class="mvx-trust-ico" aria-hidden="true"><i class="fa-solid fa-motorcycle"></i></span>
                <span class="mvx-trust-txt"><strong>{{ $catalogueCount }}</strong> Models listed</span>
            </li>
            <li class="mvx-trust-item">
                <span class="mvx-trust-ico" aria-hidden="true"><i class="fa-solid fa-shield-halved"></i></span>
                <span class="mvx-trust-txt"><strong>{{ $brands->count() }}</strong> Brands in catalogue</span>
            </li>
            <li class="mvx-trust-item">
                <span class="mvx-trust-ico" aria-hidden="true"><i class="fa-solid fa-layer-group"></i></span>
                <span class="mvx-trust-txt"><strong>{{ $categories->count() }}</strong> Categories</span>
            </li>
            <li class="mvx-trust-item">
                <span class="mvx-trust-ico" aria-hidden="true"><i class="fa-solid fa-comments"></i></span>
                <span class="mvx-trust-txt"><strong>Order online</strong> We confirm availability &amp; pricing</span>
            </li>
        </ul>
    </div>
</section>

{{-- ================= 4. MAIN: filters + results ================= --}}
<main class="mvx-container pb-16 pt-7">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

        {{-- ---------- Filter sidebar ---------- --}}
        <aside class="lg:col-span-3 xl:col-span-3">
            <button type="button" id="mvxFilterToggle" class="mvx-filter-toggle lg:hidden"
                    aria-expanded="false" aria-controls="mvxFilters">
                <span><i class="fa-solid fa-sliders" aria-hidden="true"></i> Filters</span>
                <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
            </button>

            <div id="mvxFilters" class="mvx-panel">
                <div class="mvx-panel-head">
                    <h2 class="mvx-panel-title"><i class="fa-solid fa-sliders" aria-hidden="true"></i> Filter Vehicles</h2>
                    @if($hasFilters)
                        <a href="{{ route('motorcycles.index') }}" class="mvx-reset-link">Reset all</a>
                    @endif
                </div>

                <form id="mvx-filter-form" method="GET" action="{{ route('motorcycles.index') }}">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <input type="hidden" name="sort" value="{{ $sortKey }}">

                    {{-- Category --}}
                    <fieldset class="mvx-fset">
                        <legend class="mvx-legend">Category</legend>
                        <label class="mvx-opt">
                            <input type="radio" name="category" value=""
                                   {{ ! request('category') ? 'checked' : '' }} onchange="this.form.submit()">
                            <span class="mvx-opt-label">All categories</span>
                        </label>
                        @foreach($categories as $cat)
                            <label class="mvx-opt">
                                <input type="radio" name="category" value="{{ $cat }}"
                                       {{ request('category') === $cat ? 'checked' : '' }} onchange="this.form.submit()">
                                <span class="mvx-opt-label">{{ ucfirst($cat) }}</span>
                            </label>
                        @endforeach
                    </fieldset>

                    {{-- Engine capacity --}}
                    <fieldset class="mvx-fset">
                        <legend class="mvx-legend">Engine capacity (cc)</legend>
                        <div class="mvx-cc-row">
                            <input type="number" name="min_cc" min="0" step="1" inputmode="numeric"
                                   value="{{ request('min_cc') }}" placeholder="Min" class="mvx-input" aria-label="Minimum engine capacity">
                            <span aria-hidden="true">–</span>
                            <input type="number" name="max_cc" min="0" step="1" inputmode="numeric"
                                   value="{{ request('max_cc') }}" placeholder="Max" class="mvx-input" aria-label="Maximum engine capacity">
                        </div>
                    </fieldset>

                    {{-- Brands --}}
                    <fieldset class="mvx-fset">
                        <legend class="mvx-legend">Brand</legend>
                        @foreach($brands as $b)
                            <label class="mvx-opt">
                                <input type="checkbox" name="brand[]" value="{{ $b->id }}"
                                       {{ in_array($b->id, $selectedBrands) ? 'checked' : '' }} onchange="this.form.submit()">
                                <span class="mvx-opt-label">{{ $b->name }}</span>
                                <span class="mvx-opt-count">{{ $b->motorcycles_count }}</span>
                            </label>
                        @endforeach
                    </fieldset>

                    <div class="mvx-filter-foot">
                        <button type="submit" class="mvx-btn mvx-btn-primary mvx-btn-block">
                            <i class="fa-solid fa-filter" aria-hidden="true"></i> Apply Filters
                        </button>
                        @if($hasFilters)
                            <a href="{{ route('motorcycles.index') }}" class="mvx-btn mvx-btn-ghost mvx-btn-block">
                                Clear all filters
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </aside>

        {{-- ---------- Results ---------- --}}
        <section class="lg:col-span-9 xl:col-span-9" aria-label="Motorcycle results">

            {{-- Toolbar --}}
            <div class="mvx-toolbar">
                <form method="GET" action="{{ route('motorcycles.index') }}" class="mvx-toolbar-form">
                    @foreach($selectedBrands as $brandId)
                        <input type="hidden" name="brand[]" value="{{ $brandId }}">
                    @endforeach
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    @if(request('min_cc'))   <input type="hidden" name="min_cc" value="{{ request('min_cc') }}"> @endif
                    @if(request('max_cc'))   <input type="hidden" name="max_cc" value="{{ request('max_cc') }}"> @endif

                    <div class="mvx-search">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        <input type="search" name="search" value="{{ request('search') }}"
                               placeholder="Search by model or brand…" aria-label="Search motorcycles">
                    </div>

                    <span class="mvx-select">
                        <i class="fa-solid fa-arrow-down-wide-short" aria-hidden="true"></i>
                        <select name="sort" onchange="this.form.submit()" aria-label="Sort motorcycles">
                            <option value="name"       @selected($sortKey === 'name')>Name (A–Z)</option>
                            <option value="price_asc"  @selected($sortKey === 'price_asc')>Price: Low to High</option>
                            <option value="price_desc" @selected($sortKey === 'price_desc')>Price: High to Low</option>
                            <option value="power"      @selected($sortKey === 'power')>Engine Power (CC)</option>
                        </select>
                    </span>

                    <button type="submit" class="mvx-btn mvx-btn-primary">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Search
                    </button>
                </form>

                <p class="mvx-result-count" aria-live="polite">
                    @if($motorcycles->total() > 0)
                        Showing <strong>{{ $motorcycles->firstItem() }}</strong>–<strong>{{ $motorcycles->lastItem() }}</strong>
                        of <strong>{{ $motorcycles->total() }}</strong> {{ $motorcycles->total() === 1 ? 'result' : 'results' }}
                    @else
                        <strong>0</strong> results
                    @endif
                </p>

                @if($motorcycles->isNotEmpty())
                    <div class="mvx-viewtoggle" role="group" aria-label="Result layout">
                        <button type="button" id="btnGridView" class="mvx-viewbtn is-active" onclick="mvxSetView('grid')"
                                aria-label="Grid view" title="Grid view">
                            <i class="fa-solid fa-border-all" aria-hidden="true"></i>
                        </button>
                        <button type="button" id="btnListView" class="mvx-viewbtn" onclick="mvxSetView('list')"
                                aria-label="List view" title="List view">
                            <i class="fa-solid fa-list" aria-hidden="true"></i>
                        </button>
                    </div>
                @endif
            </div>

            @if($motorcycles->isEmpty())
                {{-- ---------- Empty state ---------- --}}
                <div class="mvx-empty">
                    <div class="mvx-empty-art" aria-hidden="true"><i class="fa-solid fa-motorcycle"></i></div>
                    <h2>{{ $hasFilters ? 'No motorcycles match your filters' : 'No motorcycles available yet' }}</h2>
                    <p>
                        @if($hasFilters)
                            Nothing in the current catalogue matches this combination of category, engine
                            capacity and brand. Try widening your search.
                        @else
                            New models are added to the showroom regularly. Check back soon or get in touch
                            and we will help you find the right machine.
                        @endif
                    </p>
                    <div class="mvx-empty-actions">
                        @if($hasFilters)
                            <a href="{{ route('motorcycles.index') }}" class="mvx-btn mvx-btn-primary">
                                <i class="fa-solid fa-arrows-rotate" aria-hidden="true"></i> Reset Filters
                            </a>
                        @endif
                        <a href="{{ $hasFilters ? route('brands.index') : route('contact') }}" class="mvx-btn mvx-btn-ghost">
                            <i class="fa-solid {{ $hasFilters ? 'fa-shield-halved' : 'fa-headset' }}" aria-hidden="true"></i>
                            {{ $hasFilters ? 'Browse Brands' : 'Talk to an Expert' }}
                        </a>
                    </div>
                </div>
            @else

                {{-- ---------- Grid view ---------- --}}
                <div id="mvx-grid" class="mvx-grid">
                    @foreach($motorcycles as $moto)
                        @php
                            $status      = $moto->availability_status ?: 'available';
                            $orderable   = $status === 'available';
                            $statusTones = ['available' => 'ok', 'reserved' => 'warn', 'sold' => 'bad'];
                            $tone        = $statusTones[$status] ?? 'neutral';
                            $productUrl  = route('motorcycles.show', $moto->slug);
                            $brandName   = $moto->brand?->name ?? 'MOTOVEX';
                            $specs       = array_values(array_filter([
                                $moto->engine_capacity ? number_format((float) $moto->engine_capacity) . ' cc' : null,
                                $moto->horsepower ? $moto->horsepower . ' hp' : null,
                                $moto->torque ? $moto->torque . ' Nm' : null,
                                $moto->year ? (string) $moto->year : null,
                            ]));
                        @endphp

                        <article class="mvx-card" data-reveal>
                            <a href="{{ $productUrl }}" class="mvx-card-media" tabindex="-1">
                                @if($moto->image_url)
                                    <img src="{{ $moto->image_url }}" alt="{{ $brandName }} {{ $moto->model_name }}"
                                         class="mvx-card-img" loading="lazy" decoding="async" width="1200" height="825">
                                @else
                                    <span class="mvx-noimg">
                                        <i class="fa-solid fa-motorcycle" aria-hidden="true"></i>
                                        <span>Image coming soon</span>
                                    </span>
                                @endif
                                <span class="mvx-card-scrim" aria-hidden="true"></span>
                                <span class="mvx-badges">
                                    @if($moto->is_featured)
                                        <span class="mvx-badge mvx-badge-feat"><i class="fa-solid fa-star" aria-hidden="true"></i> Featured</span>
                                    @endif
                                    <span class="mvx-badge mvx-badge-{{ $tone }}">{{ ucfirst($status) }}</span>
                                </span>
                            </a>

                            <div class="mvx-card-body">
                                <p class="mvx-card-brand">{{ $brandName }}</p>
                                <h3 class="mvx-card-title">
                                    <a href="{{ $productUrl }}">{{ $moto->model_name }}</a>
                                </h3>

                                @if($moto->short_description)
                                    <p class="mvx-card-desc">{{ $moto->short_description }}</p>
                                @endif

                                @if($specs)
                                    <ul class="mvx-specs">
                                        @foreach($specs as $spec)
                                            <li>{{ $spec }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                                <div class="mvx-card-foot">
                                    <div class="mvx-price">
                                        <small>Starting price</small>
                                        @if($moto->price)
                                            <strong><span>{{ $moto->currency ?: 'USD' }}</span>{{ number_format($moto->price) }}</strong>
                                        @else
                                            <strong class="mvx-price-req">Price on request</strong>
                                        @endif
                                    </div>

                                    <div class="mvx-card-actions">
                                        @if($orderable)
                                            <button type="button"
                                                    class="mvx-btn mvx-btn-ghost mvx-btn-block mvx-order-trigger"
                                                    data-id="{{ $moto->id }}"
                                                    data-slug="{{ $moto->slug }}"
                                                    data-name="{{ $moto->model_name }}"
                                                    data-brand="{{ $brandName }}"
                                                    data-price="{{ $moto->price ? ($moto->currency ?: 'USD') . ' ' . number_format($moto->price) : 'Price on request' }}"
                                                    data-img="{{ $moto->image_url ?: '' }}"
                                                    data-cc="{{ $moto->engine_capacity ? number_format($moto->engine_capacity).' cc' : '' }}"
                                                    data-hp="{{ $moto->horsepower ? $moto->horsepower.' hp' : '' }}"
                                                    data-year="{{ $moto->year ?? '' }}"
                                                    data-cart="{{ route('cart.add', $moto) }}">
                                                <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i> Order
                                            </button>
                                        @endif
                                        <a href="{{ $productUrl }}" class="mvx-btn mvx-btn-primary mvx-btn-block">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- ---------- List view ---------- --}}
                <div id="mvx-list" class="mvx-list" style="display:none">
                    @foreach($motorcycles as $moto)
                        @php
                            $status      = $moto->availability_status ?: 'available';
                            $orderable   = $status === 'available';
                            $statusTones = ['available' => 'ok', 'reserved' => 'warn', 'sold' => 'bad'];
                            $tone        = $statusTones[$status] ?? 'neutral';
                            $productUrl  = route('motorcycles.show', $moto->slug);
                            $brandName   = $moto->brand?->name ?? 'MOTOVEX';
                            $specs       = array_values(array_filter([
                                $moto->engine_capacity ? number_format((float) $moto->engine_capacity) . ' cc' : null,
                                $moto->horsepower ? $moto->horsepower . ' hp' : null,
                                $moto->torque ? $moto->torque . ' Nm' : null,
                                $moto->year ? (string) $moto->year : null,
                                $moto->category ? ucfirst($moto->category) : null,
                                $moto->condition ? ucfirst($moto->condition) : null,
                            ]));
                        @endphp

                        <article class="mvx-row" data-reveal>
                            <a href="{{ $productUrl }}" class="mvx-row-media" tabindex="-1">
                                @if($moto->image_url)
                                    <img src="{{ $moto->image_url }}" alt="{{ $brandName }} {{ $moto->model_name }}"
                                         loading="lazy" decoding="async">
                                @else
                                    <span class="mvx-noimg">
                                        <i class="fa-solid fa-motorcycle" aria-hidden="true"></i>
                                        <span>Image coming soon</span>
                                    </span>
                                @endif
                            </a>

                            <div class="mvx-row-main">
                                <p class="mvx-card-brand">{{ $brandName }}</p>
                                <h3 class="mvx-card-title mt-1">
                                    <a href="{{ $productUrl }}">{{ $moto->model_name }}</a>
                                </h3>
                                @if($moto->short_description)
                                    <p class="mvx-card-desc">{{ $moto->short_description }}</p>
                                @endif
                                @if($specs)
                                    <ul class="mvx-specs mt-2.5">
                                        @foreach($specs as $spec)
                                            <li>{{ $spec }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            <div class="mvx-row-side">
                                <span class="mvx-badge mvx-badge-{{ $tone }}">{{ ucfirst($status) }}</span>
                                <div class="mvx-price">
                                    @if($moto->price)
                                        <strong><span>{{ $moto->currency ?: 'USD' }}</span>{{ number_format($moto->price) }}</strong>
                                    @else
                                        <strong class="mvx-price-req">Price on request</strong>
                                    @endif
                                </div>
                                <div class="mvx-card-actions">
                                    @if($orderable)
                                        <button type="button"
                                                class="mvx-btn mvx-btn-ghost mvx-btn-block mvx-order-trigger"
                                                data-id="{{ $moto->id }}"
                                                data-slug="{{ $moto->slug }}"
                                                data-name="{{ $moto->model_name }}"
                                                data-brand="{{ $brandName }}"
                                                data-price="{{ $moto->price ? ($moto->currency ?: 'USD') . ' ' . number_format($moto->price) : 'Price on request' }}"
                                                data-img="{{ $moto->image_url ?: '' }}"
                                                data-cc="{{ $moto->engine_capacity ? number_format($moto->engine_capacity).' cc' : '' }}"
                                                data-hp="{{ $moto->horsepower ? $moto->horsepower.' hp' : '' }}"
                                                data-year="{{ $moto->year ?? '' }}"
                                                data-cart="{{ route('cart.add', $moto) }}">
                                            <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i> Order
                                        </button>
                                    @endif
                                    <a href="{{ $productUrl }}" class="mvx-btn mvx-btn-primary mvx-btn-block">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- ---------- Pagination ---------- --}}
                @if($motorcycles->hasPages())
                    <nav class="mvx-pager" aria-label="Pagination">
                        @if($motorcycles->onFirstPage())
                            <span class="mvx-page is-disabled" aria-disabled="true">&laquo;</span>
                        @else
                            <a href="{{ $motorcycles->previousPageUrl() }}" class="mvx-page" rel="prev" aria-label="Previous page">&laquo;</a>
                        @endif

                        @foreach($motorcycles->getUrlRange(max(1, $motorcycles->currentPage() - 3), min($motorcycles->lastPage(), $motorcycles->currentPage() + 3)) as $page => $url)
                            @if($page == $motorcycles->currentPage())
                                <span class="mvx-page is-active" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="mvx-page">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($motorcycles->hasMorePages())
                            <a href="{{ $motorcycles->nextPageUrl() }}" class="mvx-page" rel="next" aria-label="Next page">&raquo;</a>
                        @else
                            <span class="mvx-page is-disabled" aria-disabled="true">&raquo;</span>
                        @endif
                    </nav>
                @endif
            @endif

        </section>
    </div>
</main>

@include('partials.footer')

<div id="mvxToast" class="mvx-toast" role="status" aria-live="polite"></div>

{{-- ══════════════════════════════════════════════
     ORDER REVIEW MODAL
     Opens when clicking "Order" on any card.
     Shows motorcycle details, confirms add-to-cart.
══════════════════════════════════════════════ --}}
<div id="mvxOrderModal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="mvxModalTitle"
     style="background:rgba(15,23,42,.65);backdrop-filter:blur(6px);">

    {{-- Backdrop --}}
    <div id="mvxModalBackdrop" class="absolute inset-0"></div>

    {{-- Panel --}}
    <div class="relative z-10 w-full max-w-lg rounded-2xl bg-white shadow-2xl overflow-hidden"
         style="border:1px solid #e2e8f0;">

        {{-- Header --}}
        <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl"
                     style="background:#f3e8ff;color:#7c3aed;">
                    <i class="fa-solid fa-cart-shopping text-sm"></i>
                </div>
                <div>
                    <h2 id="mvxModalTitle" class="text-sm font-extrabold text-slate-900">Review Your Motorcycle</h2>
                    <p class="text-[10px] text-slate-500">Please review your selection before continuing.</p>
                </div>
            </div>
            <button id="mvxModalClose" type="button" aria-label="Close"
                    class="flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-6">
            <div class="flex gap-5">
                {{-- Image --}}
                <div id="mvxModalImg"
                     class="w-36 h-28 flex-shrink-0 rounded-xl overflow-hidden border border-slate-200 bg-slate-50 flex items-center justify-center">
                    <i class="fa-solid fa-motorcycle text-3xl text-slate-300"></i>
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p id="mvxModalBrand" class="text-[10px] font-bold uppercase tracking-widest text-purple-600 mb-1"></p>
                    <h3 id="mvxModalName" class="text-xl font-extrabold text-slate-900 leading-tight mb-2"></h3>
                    <div id="mvxModalSpecs" class="flex flex-wrap gap-1.5 mb-3"></div>
                    {{-- In Stock badge --}}
                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[10px] font-bold"
                          style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;">
                        <span style="width:5px;height:5px;border-radius:50%;background:#4ade80;display:inline-block;"></span>
                        In Stock
                    </span>
                </div>
            </div>

            {{-- Price summary --}}
            <div class="mt-5 rounded-xl border border-slate-200 bg-slate-50 p-4">
                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-slate-500 font-medium">Quantity</span>
                    <div class="flex items-center gap-3">
                        <button type="button" disabled
                                class="h-7 w-7 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 text-xs cursor-not-allowed">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                        <span class="font-bold text-slate-900 w-4 text-center">1</span>
                        <button type="button" disabled
                                class="h-7 w-7 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 text-xs cursor-not-allowed">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between text-sm mb-1">
                    <span class="text-slate-500">Subtotal</span>
                    <span id="mvxModalSubtotal" class="font-semibold text-slate-700"></span>
                </div>
                <div class="flex items-center justify-between border-t border-slate-200 pt-2 mt-2">
                    <span class="font-bold text-slate-900">Total</span>
                    <span id="mvxModalTotal" class="font-extrabold text-lg text-slate-900"></span>
                </div>
            </div>

            {{-- Note --}}
            <div class="mt-3 flex items-start gap-2 text-[10.5px] text-slate-500">
                <i class="fa-solid fa-shield-halved text-purple-500 mt-0.5 flex-shrink-0"></i>
                Your motorcycle will not be added to the cart until you confirm.
            </div>
        </div>

        {{-- Footer --}}
        <div class="border-t border-slate-100 px-6 py-4 flex flex-col gap-2.5">
            {{-- Hidden form for actual cart submission --}}
            <form id="mvxModalForm" method="POST" action="">
                @csrf
                <button type="submit" id="mvxModalConfirm"
                        class="flex w-full items-center justify-center gap-2.5 rounded-xl py-3.5 text-sm font-bold text-white transition"
                        style="background:linear-gradient(135deg,#7c3aed,#6d28d9);box-shadow:0 8px 24px -10px rgba(124,58,237,.5);">
                    <i class="fa-solid fa-cart-shopping"></i>
                    Confirm &amp; Add to Cart
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>
            <button id="mvxModalCancel" type="button"
                    class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Continue Shopping
            </button>
        </div>
    </div>
</div>

<script>
    (function () {
        'use strict';

        var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* ---------- Toast ---------- */
        var toast = document.getElementById('mvxToast');
        var toastTimer = null;

        function mvxToast(message, tone) {
            if (!toast || !message) return;
            toast.textContent = message;
            toast.className = 'mvx-toast is-visible' + (tone ? ' is-' + tone : '');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(function () {
                toast.className = 'mvx-toast';
            }, 3200);
        }

        @if(session('success')) mvxToast(@json(session('success')), 'success'); @endif
        @if(session('error'))   mvxToast(@json(session('error')), 'error');     @endif
        @if(session('info'))    mvxToast(@json(session('info')), 'info');       @endif

        /* ---------- Grid / list toggle ---------- */
        var gridView = document.getElementById('mvx-grid');
        var listView = document.getElementById('mvx-list');
        var btnGrid  = document.getElementById('btnGridView');
        var btnList  = document.getElementById('btnListView');

        window.mvxSetView = function (mode) {
            if (!gridView || !listView) return;
            var isGrid = mode !== 'list';
            gridView.style.display = isGrid ? '' : 'none';
            listView.style.display = isGrid ? 'none' : 'flex';
            if (btnGrid) btnGrid.classList.toggle('is-active', isGrid);
            if (btnList) btnList.classList.toggle('is-active', !isGrid);
            try { localStorage.setItem('moto-view', isGrid ? 'grid' : 'list'); } catch (e) {}
        };

        try {
            if (localStorage.getItem('moto-view') === 'list') window.mvxSetView('list');
        } catch (e) {}

        /* ---------- Mobile filter panel ---------- */
        var filterToggle = document.getElementById('mvxFilterToggle');
        var filterPanel  = document.getElementById('mvxFilters');

        if (filterToggle && filterPanel) {
            filterToggle.addEventListener('click', function () {
                var isOpen = filterPanel.classList.toggle('is-open');
                filterToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) {
                    filterPanel.classList.remove('is-open');
                    filterToggle.setAttribute('aria-expanded', 'false');
                }
            });
        }

        /* ---------- Scroll-reveal ---------- */
        var revealItems = document.querySelectorAll('[data-reveal]');
        if (revealItems.length > 0 && !prefersReduced && 'IntersectionObserver' in window) {
            function getGridColumns() {
                var grid = document.getElementById('mvx-grid');
                if (!grid || !window.getComputedStyle) return 3;
                var cols = window.getComputedStyle(grid).gridTemplateColumns;
                if (!cols || cols === 'none') return 3;
                return cols.split(/\s+/).filter(Boolean).length || 3;
            }
            var observer = new IntersectionObserver(function (entries) {
                var cols = getGridColumns();
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        var idx = Array.prototype.indexOf.call(revealItems, entry.target);
                        entry.target.style.setProperty('--stagger', String(idx % cols));
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
            revealItems.forEach(function (item) { observer.observe(item); });
        } else {
            revealItems.forEach(function (item) {
                item.style.setProperty('--stagger', '0');
                item.classList.add('is-visible');
            });
        }

        /* ══════════════════════════════════════════
           ORDER REVIEW MODAL
        ══════════════════════════════════════════ */
        var modal       = document.getElementById('mvxOrderModal');
        var modalForm   = document.getElementById('mvxModalForm');
        var modalImg    = document.getElementById('mvxModalImg');
        var modalBrand  = document.getElementById('mvxModalBrand');
        var modalName   = document.getElementById('mvxModalName');
        var modalSpecs  = document.getElementById('mvxModalSpecs');
        var modalSub    = document.getElementById('mvxModalSubtotal');
        var modalTotal  = document.getElementById('mvxModalTotal');
        var modalClose  = document.getElementById('mvxModalClose');
        var modalCancel = document.getElementById('mvxModalCancel');
        var modalBdrop  = document.getElementById('mvxModalBackdrop');
        var modalConfirm = document.getElementById('mvxModalConfirm');

        function openOrderModal(btn) {
            var d = btn.dataset;

            // Populate image
            if (d.img) {
                modalImg.innerHTML = '<img src="' + d.img + '" alt="' + d.name + '" style="width:100%;height:100%;object-fit:cover;">';
            } else {
                modalImg.innerHTML = '<i class="fa-solid fa-motorcycle text-3xl text-slate-300"></i>';
            }

            // Text content
            modalBrand.textContent = d.brand;
            modalName.textContent  = d.name;

            // Specs chips
            var specHtml = '';
            [d.cc, d.hp, d.year].forEach(function(s) {
                if (s) specHtml += '<span style="padding:.25rem .55rem;border-radius:7px;background:#f1f5f9;border:1px solid #e2e8f0;font-size:.7rem;font-weight:600;color:#475569;">' + s + '</span>';
            });
            modalSpecs.innerHTML = specHtml;

            // Price
            modalSub.textContent   = d.price;
            modalTotal.textContent = d.price;

            // Set form action
            modalForm.action = d.cart;

            // Show modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeOrderModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Open on Order button click
        document.querySelectorAll('.mvx-order-trigger').forEach(function(btn) {
            btn.addEventListener('click', function() { openOrderModal(btn); });
        });

        // Close handlers
        if (modalClose)  modalClose.addEventListener('click', closeOrderModal);
        if (modalCancel) modalCancel.addEventListener('click', closeOrderModal);
        if (modalBdrop)  modalBdrop.addEventListener('click', closeOrderModal);
        document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeOrderModal(); });

        // Prevent double-submission on confirm
        if (modalForm) {
            modalForm.addEventListener('submit', function() {
                if (modalConfirm) {
                    modalConfirm.disabled = true;
                    modalConfirm.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding…';
                }
            });
        }

    })();
</script>
</body>
</html>
