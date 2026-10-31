{{-- =============================================================================
     MOTOVEX — SPARE PARTS LANDING PAGE (self-contained dark theme)
     resources/views/spare-parts/index.blade.php

     Bilingual (en / ar): every string is resolved through
     lang/{locale}/spare_parts.php ('lp_*' and 'categories.*' keys).
     Direction is dynamic — the design is authored RTL, and a [dir="ltr"]
     override block flips the few physical CSS properties it uses.

     Data contract (SparePartsController::index):
       $spareParts   LengthAwarePaginator of SparePart
       $brands       Collection of Brand
       $motorcycles  Collection of Motorcycle
       $categories   array  slug => label  (SparePart::$categories)
     ============================================================================= --}}
@php
    $locale = app()->getLocale();
    $isRtl  = $locale === 'ar';
    $arrow  = $isRtl ? 'fa-arrow-left' : 'fa-arrow-right';

    // ── Background artwork: same local dark photography as the showroom page ──
    $img = fn (string $file) => asset('images/motorcycles/' . $file);

    // ── Category artwork (DB categories carry no image) ───────────────────────
    $defaultCatImage = 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=500&q=85';
    $catImages = [
        'engine'          => 'https://images.unsplash.com/photo-1635784063388-4e9c7f6f2d6b?auto=format&fit=crop&w=500&q=85',
        'brakes'          => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=500&q=85',
        'suspension'      => 'https://images.unsplash.com/photo-1517524206127-48bbd363f3d7?auto=format&fit=crop&w=500&q=85',
        'exhaust'         => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=500&q=85',
        'electrical'      => 'https://images.unsplash.com/photo-1621905251918-48416bd8575a?auto=format&fit=crop&w=500&q=85',
        'body'            => 'https://images.unsplash.com/photo-1568772774686-cba0cbf93b7e?auto=format&fit=crop&w=500&q=85',
        'tires_wheels'    => 'https://images.unsplash.com/photo-1517524206127-48bbd363f3d7?auto=format&fit=crop&w=500&q=85',
        'transmission'    => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=500&q=85',
        'filters'         => 'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&w=500&q=85',
        'accessories'     => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=500&q=85',
        'other'           => 'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&w=500&q=85',
    ];

    $catLabel = function ($slug, $label = null) {
        $key   = 'spare_parts.categories.' . $slug;
        $value = __($key);

        return $value !== $key ? $value : ($label ?: ucfirst(str_replace('_', ' ', (string) $slug)));
    };

    // Accept both the associative `slug => label` array and a list of models.
    $categoryRows = collect($categories ?? \App\Models\SparePart::$categories)
        ->map(function ($item, $slug) use ($catLabel, $defaultCatImage, $catImages) {
            $isStructured = is_object($item) || is_array($item);
            $key   = $isStructured ? (data_get($item, 'slug') ?? $slug) : $slug;
            $label = $isStructured ? data_get($item, 'name') : $item;

            return (object) [
                'slug'  => $key,
                'name'  => $catLabel($key, $label),
                'image' => data_get($item, 'image') ?: data_get($item, 'image_url') ?: ($catImages[$key] ?? $defaultCatImage),
            ];
        })
        ->values();

    // ── Parts: live catalogue first, demo set only when nothing is listed ────
    $liveParts = isset($spareParts) && method_exists($spareParts, 'items')
        ? collect($spareParts->items())
        : (isset($spareParts) ? collect($spareParts) : collect());

    if ($liveParts->isEmpty() && isset($products)) {
        $liveParts = $products instanceof \Illuminate\Pagination\AbstractPaginator
            ? collect($products->items())
            : collect($products);
    }

    $demoParts = collect([
        (object) ['name' => __('spare_parts.lp_demo_part_1'), 'sku' => 'MV-BRK-204', 'price' => 950,  'old_price' => 1200, 'image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=700&q=90', 'badge' => __('spare_parts.lp_demo_badge_1')],
        (object) ['name' => __('spare_parts.lp_demo_part_2'), 'sku' => 'MV-SPK-118', 'price' => 550,  'old_price' => 690,  'image' => 'https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?auto=format&fit=crop&w=700&q=90', 'badge' => __('spare_parts.lp_demo_badge_2')],
        (object) ['name' => __('spare_parts.lp_demo_part_3'), 'sku' => 'MV-FLT-090', 'price' => 190,  'old_price' => 250,  'image' => 'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&w=700&q=90', 'badge' => __('spare_parts.lp_demo_badge_3')],
        (object) ['name' => __('spare_parts.lp_demo_part_4'), 'sku' => 'MV-CHN-310', 'price' => 990,  'old_price' => 1150, 'image' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=700&q=90', 'badge' => __('spare_parts.lp_demo_badge_4')],
        (object) ['name' => __('spare_parts.lp_demo_part_5'), 'sku' => 'MV-PAD-052', 'price' => 390,  'old_price' => 480,  'image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=700&q=90', 'badge' => __('spare_parts.lp_demo_badge_5')],
    ]);

    $featuredParts = $liveParts->isNotEmpty() ? $liveParts : $demoParts;

    $defaultPartImage = $defaultCatImage;
    $partImage = fn ($item) => data_get($item, 'image_url') ?: data_get($item, 'image') ?: data_get($item, 'primary_image') ?: $defaultPartImage;
    $partName  = fn ($item) => data_get($item, 'name') ?: data_get($item, 'title') ?: __('spare_parts.lp_part_fallback');
    $partPrice = fn ($item) => (float) (data_get($item, 'price') ?? 0);
    $partCur   = fn ($item) => data_get($item, 'currency') ?: __('spare_parts.lp_currency');
    $motoLabel = function ($m) {
        $brand = trim((string) (data_get($m, 'brand.name') ?? ''));
        $model = trim((string) (data_get($m, 'model_name') ?? data_get($m, 'name') ?? ''));

        // model_name often already starts with the brand — avoid "Aprilia Aprilia RS 660".
        if ($brand === '' || mb_stripos($model, $brand) === 0) {
            return $model !== '' ? $model : $brand;
        }

        return trim($brand . ' ' . $model);
    };

    // ── Cart badge (same session shape used by partials/navbar) ──────────────
    $cart      = session('cart', ['motorcycles' => [], 'spare_parts' => []]);
    $cartCount = count($cart['motorcycles'] ?? []) + array_sum(array_column($cart['spare_parts'] ?? [], 'quantity'));
    $accountUrl = auth()->check()
        ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('account.dashboard'))
        : route('login');

    $activeCategory = request('category');
    $activeBrand    = request('brand');
    $activeMotorcycle = request('motorcycle');
@endphp
<!DOCTYPE html>
<html lang="{{ $isRtl ? 'ar' : 'en' }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ __('spare_parts.lp_meta_desc') }}">
    <title>{{ $title ?? __('spare_parts.lp_meta_title') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @unless($isRtl)
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @endunless
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root{--bg:#050b18;--panel:#08162a;--panel2:#0b1c34;--line:#15385d;--blue:#13a8ff;--cyan:#4bd8ff;--purple:#853cff;--text:#f4f8ff;--muted:#91a9c6;--gold:#ffc247;--radius:17px}
        *{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:var(--bg);color:var(--text);font-family:'Cairo',sans-serif;overflow-x:hidden}a{color:inherit;text-decoration:none}button,input,select{font:inherit}.container{width:min(1240px,calc(100% - 34px));margin:auto}
        .hero{min-height:410px;position:relative;display:flex;align-items:center;overflow:hidden;background:linear-gradient(90deg,#020918 0%,rgba(2,9,24,.94) 34%,rgba(2,9,24,.2) 100%),url('{{ $img('vayu-r1-1100.jpg') }}') center/cover;border-bottom:1px solid #14508a}.hero:after{content:"";position:absolute;inset:0;background:linear-gradient(0deg,#050b18 0%,transparent 26%,transparent 80%,#07132966);pointer-events:none}.hero-content{position:relative;z-index:1;max-width:600px;padding:65px 0}.eyebrow{color:#a979ff;font-size:12px;font-weight:800;letter-spacing:1px}.hero h1{font-size:clamp(28px,3.2vw,46px);line-height:1.3;margin:12px 0 8px;font-weight:900;white-space:normal;text-wrap:balance}.hero h1 span{display:inline;color:#a66cff;text-shadow:0 0 24px #8b40ff66}.hero p{color:#c0d2e8;line-height:1.9;font-size:14px;max-width:480px}.actions{display:flex;gap:12px;margin-top:23px;flex-wrap:wrap}.btn{border:1px solid #a45cff;background:linear-gradient(110deg,#853dff,#4a35db);color:white;padding:11px 22px;border-radius:9px;font-weight:800;font-size:12px;box-shadow:0 0 20px #713bff66;cursor:pointer;display:inline-flex;align-items:center;gap:8px;justify-content:center;transition:.25s}.btn:hover{transform:translateY(-3px);box-shadow:0 0 28px #853dff99}.btn-outline{background:#07162cbb;border-color:#37618c;box-shadow:none}.hero-glow{position:absolute;width:320px;height:320px;border-radius:50%;border:1px solid #178bff55;box-shadow:0 0 70px #087dff33;left:10%;top:10%}
        .trust-strip{position:relative;margin-top:-1px;background:linear-gradient(90deg,#0b1a31,#071126,#0b1a31);border-block:1px solid #17416c;box-shadow:0 0 28px #087dff22}.trust-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;padding:19px 0}.trust-item{text-align:center;color:#c8d9ed;font-size:11px}.trust-item i{display:block;color:#54baff;font-size:24px;margin-bottom:7px;text-shadow:0 0 15px #169dff}
        .section{padding:45px 0}.section-head{display:flex;align-items:end;justify-content:space-between;gap:15px;margin-bottom:19px}.section-head h2{font-size:22px;margin:0;font-weight:900}.section-head p{font-size:11px;color:var(--muted);margin:3px 0 0}.more{font-size:11px;color:#b88aff}.categories{display:grid;grid-template-columns:repeat(8,1fr);gap:12px}.category{background:linear-gradient(145deg,#0d2039,#071326);border:1px solid #214b73;border-radius:12px;overflow:hidden;text-align:center;transition:.3s;box-shadow:inset 0 0 15px #168bff0c}.category:hover{transform:translateY(-5px);border-color:#42baff;box-shadow:0 0 20px #148fff33}.category img{width:100%;height:100px;object-fit:cover;display:block;filter:saturate(.85)}.category span{display:block;padding:9px 4px;font-size:11px;font-weight:800}.category.is-active{border-color:#8d4cff;box-shadow:0 0 20px #733aff55}
        .showroom{display:grid;grid-template-columns:258px 1fr;gap:16px;align-items:start}
        .fpanel{background:linear-gradient(160deg,#0c1f38,#071326);border:1px solid #1d4c78;border-radius:14px;padding:15px;position:sticky;top:82px}.fpanel h2{margin:0;font-size:14px;font-weight:900}.fpanel .fsub{font-size:10px;color:var(--muted);margin:3px 0 13px;line-height:1.7}.fpanel .reset{font-size:10px;color:#b88aff;font-weight:800}.fset{border:0;padding:0;margin:0 0 15px}.fset legend{font-size:10px;font-weight:700;color:#c8d9ed;text-transform:uppercase;letter-spacing:.8px;margin-bottom:9px;display:block}.fopt{display:flex;align-items:center;gap:7px;padding:5px 0;cursor:pointer;font-size:11px;color:#c8d9ed}.fopt input[type=radio],.fopt input[type=checkbox]{accent-color:#853cff;width:13px;height:13px;flex-shrink:0}.fopt .cnt{margin-inline-start:auto;font-size:9px;color:#6f8fad}.fbtns{display:flex;flex-direction:column;gap:7px;margin-top:4px}.ftoggle{display:none;width:100%;background:linear-gradient(145deg,#0c1f38,#071326);border:1px solid #1d4c78;border-radius:12px;padding:11px 14px;color:#c8d9ed;font-size:12px;font-weight:700;cursor:pointer;align-items:center;justify-content:space-between}.finput{background:#071326;color:#dceaff;border:1px solid #23466b;border-radius:8px;padding:8px 10px;font-size:11px;width:100%;outline:none;transition:.2s}.finput:focus{border-color:#853cff;box-shadow:0 0 0 3px #853cff22}
        .products{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}.product{position:relative;background:linear-gradient(155deg,#102642,#071326 72%);border:1px solid #1d4c78;border-radius:14px;overflow:hidden;box-shadow:0 8px 24px #0005;transition:.3s}.product:hover{transform:translateY(-6px);border-color:#8d4cff;box-shadow:0 0 25px #733aff33}.product-image{height:155px;position:relative;background:#071426;overflow:hidden}.product-image img{width:100%;height:100%;object-fit:cover;transition:.5s}.product:hover .product-image img{transform:scale(1.08)}.tag{position:absolute;top:9px;right:9px;background:#7d39f4;color:white;border-radius:5px;padding:3px 7px;font-size:9px;font-weight:800}.tag-out{background:#5c6d84}.heart{position:absolute;left:9px;top:9px;width:27px;height:27px;border-radius:50%;background:#061326cc;border:1px solid #45617d;color:#fff;display:grid;place-items:center}.product-info{padding:11px}.product-info h3{font-size:12px;margin:0 0 4px}.product-info h3 a:hover{color:#b78aff}.sku{font-size:9px;color:#819ab7}.rating{color:var(--gold);font-size:10px;margin:5px 0}.price-row{display:flex;align-items:center;gap:8px;margin:6px 0 10px}.price{color:#ffc13f;font-size:14px;font-weight:900}.old-price{font-size:10px;color:#8193aa;text-decoration:line-through}.product .btn{width:100%;padding:8px;font-size:10px}.btn-disabled{background:#16263b;border-color:#2b4463;color:#9db1c6;box-shadow:none;cursor:default}.btn-disabled:hover{transform:none;box-shadow:none}.pager{display:flex;align-items:center;justify-content:center;gap:14px;margin-top:24px;flex-wrap:wrap}.pager .btn{padding:9px 18px;font-size:11px}.pager-info{font-size:11px;color:var(--muted)}
        .promo{min-height:165px;border:1px solid #285a89;border-radius:17px;overflow:hidden;position:relative;background:linear-gradient(90deg,#09182c 0%,#09182cdd 43%,#07172b55),url('{{ $img('atlas-ridge-850.jpg') }}') center/cover;display:flex;align-items:center;padding:25px 35px;box-shadow:0 0 28px #0a8dff22}.promo-copy{position:relative;z-index:1}.promo h2{margin:0;color:#ffbd3c;font-size:25px}.promo p{margin:5px 0 14px;font-weight:700}.discount{position:absolute;left:30px;top:50%;transform:translateY(-50%);background:#ff3154;border-radius:12px;padding:12px 18px;text-align:center;font-size:14px;font-weight:900;box-shadow:0 0 25px #ff315477}.discount strong{display:block;font-size:35px;line-height:1}
        .service-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px}.service{background:linear-gradient(145deg,#102642,#071326);border:1px solid #204b75;border-radius:13px;text-align:center;padding:20px 10px;transition:.25s}.service:hover{border-color:#8b51ff;transform:translateY(-4px)}.service i{font-size:29px;color:#63bfff;text-shadow:0 0 16px #168bff;margin-bottom:9px}.service h3{font-size:12px;margin:0 0 4px}.service p{font-size:10px;color:#91a9c6;margin:0}
        .fitment{border:1px solid #23527e;border-radius:16px;background:linear-gradient(90deg,#07162b,#0b1d35);overflow:hidden;display:grid;grid-template-columns:34% 1fr;min-height:210px}.fitment-art{background:url('{{ $img('vayu-street-790.jpg') }}') center/cover;min-height:210px}.fitment-content{padding:23px}.fitment-content h2{margin:0 0 5px;font-size:20px}.fitment-content p{font-size:11px;color:var(--muted);margin:0 0 16px}.fitment-form{display:grid;grid-template-columns:repeat(3,1fr);gap:9px}.fitment-form select,.search-input{min-width:0;background:#071326;color:#dceaff;border:1px solid #23466b;border-radius:8px;padding:10px;font-size:11px}.fitment-form .btn{grid-column:1/-1;justify-self:start}
        .reviews{display:grid;grid-template-columns:repeat(3,1fr);gap:13px}.review{background:linear-gradient(145deg,#10243d,#071326);border:1px solid #23496f;border-radius:13px;padding:15px}.review-top{display:flex;align-items:center;gap:10px}.avatar{width:38px;height:38px;border-radius:50%;object-fit:cover}.review h3{font-size:11px;margin:0}.review small{font-size:9px;color:#8da4bf}.review p{font-size:10px;color:#c3d2e4;line-height:1.8;margin:12px 0 0}.review .rating{margin-right:auto}
        .articles{display:grid;grid-template-columns:repeat(4,1fr);gap:13px}.article{border:1px solid #204a73;border-radius:12px;overflow:hidden;background:#0a1a30}.article img{width:100%;height:115px;object-fit:cover;display:block}.article div{padding:10px}.article h3{font-size:11px;margin:0 0 5px}.article p{font-size:9px;color:#93a9c2;margin:0}
        .newsletter{position:relative;overflow:hidden;border:1px solid #315b8b;border-radius:16px;padding:25px 30px;background:linear-gradient(90deg,#08172bdf,#08172be8),url('{{ $img('aurum-grandura.jpg') }}') center/cover;display:flex;justify-content:space-between;align-items:center;gap:20px}.newsletter h2{margin:0;font-size:20px}.newsletter p{margin:3px 0 0;color:#a9bdd5;font-size:11px}.news-form{display:flex;gap:8px;width:min(440px,100%)}.news-form input{flex:1;min-width:0;background:#f1f5fb;border:0;border-radius:8px;padding:11px;color:#101b2b;font-size:11px}
        .footer{background:#030916;border-top:1px solid #1b3c62;margin-top:45px;padding:34px 0 0}.footer-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr 1.2fr 1.1fr;gap:24px;padding-bottom:25px}.footer-logo{font-size:20px;font-weight:900}.footer-logo i{color:#a56bff;margin-left:7px}.footer p,.footer a{color:#8fa6c2;font-size:10px;line-height:2}.footer h4{font-size:12px;margin:4px 0 12px;color:#f2f7ff}.footer-links{display:grid;gap:3px}.footer a:hover{color:#b68aff}.socials{display:flex;gap:8px;margin-top:12px}.socials a{width:27px;height:27px;border:1px solid #25476d;border-radius:7px;display:grid;place-items:center}.app-badge{display:inline-flex;align-items:center;gap:7px;background:#101e31;border:1px solid #284664;border-radius:7px;padding:6px 9px;margin:4px 0;font-size:9px!important}.copyright{border-top:1px solid #172e4b;padding:11px 0;display:flex;justify-content:space-between;gap:10px;color:#7890ac;font-size:9px}
        .reveal{opacity:0;transform:translateY(22px);transition:opacity .7s,transform .7s}.reveal.visible{opacity:1;transform:none}.mobile-search{display:none}
        [dir="rtl"] .num-latin{direction:ltr;unicode-bidi:plaintext;display:inline-block}
        @media(max-width:1000px){.showroom{grid-template-columns:1fr}.fpanel{position:static}.categories{grid-template-columns:repeat(4,1fr)}.products{grid-template-columns:repeat(3,1fr)}.footer-grid{grid-template-columns:repeat(3,1fr)}}
        @media(max-width:700px){.topbar{height:59px}.navlinks{display:none;position:absolute;top:59px;right:0;left:0;background:#061124;padding:17px;flex-direction:column;align-items:stretch;border-bottom:1px solid #24496e}.navlinks.open{display:flex}.menu-toggle{display:block}.brand{font-size:17px}.navtools{gap:7px}.navtools .navicon:nth-last-child(-n+2){display:none}.hero{min-height:370px;background-position:58% center}.hero-content{padding:45px 0;max-width:90%}.hero h1{font-size:26px;line-height:1.35}.hero p{font-size:12px}.trust-grid{grid-template-columns:repeat(5,1fr);gap:4px}.trust-item{font-size:8px}.trust-item i{font-size:19px}.section{padding:32px 0}.section-head h2{font-size:18px}.categories{grid-template-columns:repeat(4,1fr);gap:8px}.category img{height:80px}.category span{font-size:9px}.products{grid-template-columns:repeat(2,1fr);gap:9px}.product-image{height:125px}.product-info{padding:9px}.product-info h3{font-size:10px}.promo{min-height:150px;padding:18px}.promo h2{font-size:19px}.promo p{font-size:10px}.discount{left:12px;padding:8px 11px;font-size:10px}.discount strong{font-size:26px}.service-grid{grid-template-columns:repeat(3,1fr);gap:8px}.service{padding:15px 6px}.service i{font-size:23px}.service h3{font-size:10px}.fitment{grid-template-columns:1fr}.fitment-art{min-height:130px}.fitment-content{padding:17px}.fitment-form{grid-template-columns:1fr 1fr}.reviews{grid-template-columns:1fr}.articles{grid-template-columns:repeat(2,1fr)}.article img{height:95px}.newsletter{display:block;padding:20px}.news-form{margin-top:14px}.footer-grid{grid-template-columns:repeat(2,1fr);gap:18px}.footer-grid>div:first-child{grid-column:1/-1}.copyright{font-size:8px}.fitment-content h2{font-size:17px}.ftoggle{display:flex}.fpanel{display:none}.fpanel.open{display:block}}
        @media(max-width:390px){.navtools .lang{display:none}.trust-item{font-size:7px}.products{gap:7px}.product-image{height:108px}.articles{grid-template-columns:1fr 1fr}.footer-grid{gap:12px}}
    </style>
    @unless($isRtl)
    {{-- ── LTR mirror for the handful of physical properties the design uses ── --}}
    <style>
        [dir="ltr"] body{font-family:'Plus Jakarta Sans','Cairo',sans-serif}
        [dir="ltr"] .hero{background-image:linear-gradient(270deg,#020918 0%,rgba(2,9,24,.94) 34%,rgba(2,9,24,.2) 100%),url('{{ $img('vayu-r1-1100.jpg') }}')}
        [dir="ltr"] .hero h1 span{display:inline}
        [dir="ltr"] .hero-glow{left:auto;right:10%}
        [dir="ltr"] .promo{background-image:linear-gradient(270deg,#09182c 0%,#09182cdd 43%,#07172b55),url('{{ $img('atlas-ridge-850.jpg') }}')}
        [dir="ltr"] .newsletter{background-image:linear-gradient(270deg,#08172bdf,#08172be8),url('{{ $img('aurum-grandura.jpg') }}')}
        [dir="ltr"] .tag{right:auto;left:9px}
        [dir="ltr"] .heart{left:auto;right:9px}
        [dir="ltr"] .cart-dot{right:auto;left:-4px}
        [dir="ltr"] .discount{left:auto;right:30px}
        [dir="ltr"] .review .rating{margin-right:0;margin-left:auto}
        [dir="ltr"] .footer-logo i{margin-left:0;margin-right:7px}
        @media(max-width:700px){[dir="ltr"] .discount{left:auto;right:12px}}
    </style>
    @endunless
</head>
<body>
@include('partials.navbar')

<main>
    <section class="hero">
        <div class="hero-glow"></div>
        <div class="container"><div class="hero-content">
            <div class="eyebrow">{{ __('spare_parts.lp_eyebrow') }}</div>
            <h1>{{ __('spare_parts.lp_hero_title') }} <span>{{ __('spare_parts.lp_hero_title_em') }}</span></h1>
            <p>{{ __('spare_parts.lp_hero_desc') }}</p>
            <div class="actions"><a href="#featured" class="btn"><i class="fa-solid fa-bag-shopping"></i> {{ __('spare_parts.lp_hero_shop') }}</a><a href="#fitment" class="btn btn-outline"><i class="fa-solid fa-motorcycle"></i> {{ __('spare_parts.lp_hero_fitment') }}</a></div>
        </div></div>
    </section>

    <section class="trust-strip"><div class="container trust-grid">
        <div class="trust-item"><i class="fa-solid fa-shield-halved"></i>{{ __('spare_parts.lp_trust_1') }}</div>
        <div class="trust-item"><i class="fa-solid fa-truck-fast"></i>{{ __('spare_parts.lp_trust_2') }}</div>
        <div class="trust-item"><i class="fa-solid fa-headset"></i>{{ __('spare_parts.lp_trust_3') }}</div>
        <div class="trust-item"><i class="fa-solid fa-screwdriver-wrench"></i>{{ __('spare_parts.lp_trust_4') }}</div>
        <div class="trust-item"><i class="fa-solid fa-award"></i>{{ __('spare_parts.lp_trust_5') }}</div>
    </div></section>

    <section class="section container reveal" id="categories">
        <div class="section-head"><div><h2>{{ __('spare_parts.lp_cats_heading') }}</h2><p>{{ __('spare_parts.lp_cats_sub') }}</p></div><a class="more" href="{{ route('spare-parts.index') }}">{{ __('spare_parts.lp_cats_all') }} <i class="fa-solid {{ $arrow }}"></i></a></div>
        <div class="categories">
            @foreach($categoryRows as $category)
                <a class="category {{ $activeCategory === $category->slug ? 'is-active' : '' }}" href="{{ route('spare-parts.index', ['category' => $category->slug]) }}">
                    <img src="{{ $category->image }}" alt="{{ $category->name }}" loading="lazy">
                    <span>{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="section container reveal" id="featured">
        <div class="section-head">
            <div>
                <h2>{{ __('spare_parts.lp_featured_heading') }}</h2>
                <p>{{ __('spare_parts.lp_featured_sub') }}</p>
            </div>
            @if(request()->hasAny(['search','category','brand','motorcycle','sort']))
                <a class="more" href="{{ route('spare-parts.index') }}">{{ __('motorcycles.clear_all') }} <i class="fa-solid fa-arrows-rotate"></i></a>
            @endif
        </div>

        <div class="showroom">
            {{-- ── Sidebar filter ── --}}
            <aside>
                <button type="button" class="ftoggle" id="filterToggle" aria-expanded="false" aria-controls="filterPanel">
                    <span><i class="fa-solid fa-sliders"></i> {{ __('motorcycles.filters') }}</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>

                <div class="fpanel" id="filterPanel">
                    <h2><i class="fa-solid fa-sliders" style="color:#853cff;margin-inline-end:7px"></i> {{ __('motorcycles.lp_filter_heading') }}</h2>
                    <p class="fsub">{{ __('motorcycles.lp_filter_sub') }}
                        @if(request()->hasAny(['search','category','brand','motorcycle']))
                            <br><a class="reset" href="{{ route('spare-parts.index') }}">{{ __('motorcycles.reset_all') }}</a>
                        @endif
                    </p>

                    <form method="GET" action="{{ route('spare-parts.index') }}">
                        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                        @if(request('sort'))   <input type="hidden" name="sort"   value="{{ request('sort') }}"> @endif

                        {{-- Category --}}
                        <fieldset class="fset">
                            <legend>{{ __('motorcycles.category') }}</legend>
                            <label class="fopt">
                                <input type="radio" name="category" value="" {{ !$activeCategory ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>{{ __('motorcycles.all_categories') }}</span>
                            </label>
                            @foreach($categoryRows as $cat)
                                <label class="fopt">
                                    <input type="radio" name="category" value="{{ $cat->slug }}"
                                           {{ $activeCategory === $cat->slug ? 'checked' : '' }} onchange="this.form.submit()">
                                    <span>{{ $cat->name }}</span>
                                </label>
                            @endforeach
                        </fieldset>

                        {{-- Brand --}}
                        @if(($brands ?? collect())->isNotEmpty())
                        <fieldset class="fset">
                            <legend>{{ __('motorcycles.brand') }}</legend>
                            @foreach(($brands ?? collect()) as $brand)
                                <label class="fopt">
                                    <input type="checkbox" name="brand" value="{{ $brand->id }}"
                                           {{ (string)$activeBrand === (string)$brand->id ? 'checked' : '' }} onchange="this.form.submit()">
                                    <span>{{ $brand->name }}</span>
                                </label>
                            @endforeach
                        </fieldset>
                        @endif

                        {{-- Motorcycle compatibility --}}
                        @if(($motorcycles ?? collect())->isNotEmpty())
                        <fieldset class="fset">
                            <legend>{{ __('spare_parts.lp_fitment_model') }}</legend>
                            <select class="finput" name="motorcycle" onchange="this.form.submit()">
                                <option value="">{{ __('spare_parts.lp_fitment_model') }}</option>
                                @foreach(($motorcycles ?? collect()) as $motorcycle)
                                    <option value="{{ $motorcycle->id }}"
                                            {{ (string)$activeMotorcycle === (string)$motorcycle->id ? 'selected' : '' }}>
                                        {{ $motoLabel($motorcycle) }}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                        @endif

                        <div class="fbtns">
                            <button type="submit" class="btn"><i class="fa-solid fa-filter"></i> {{ __('motorcycles.apply_filters') }}</button>
                            @if(request()->hasAny(['category','brand','motorcycle']))
                                <a href="{{ route('spare-parts.index') }}" class="btn btn-outline">{{ __('motorcycles.clear_all_filters') }}</a>
                            @endif
                        </div>
                    </form>
                </div>
            </aside>

            {{-- ── Products ── --}}
            <div>
                {{-- Toolbar: search + sort --}}
                <form class="toolbar" method="GET" action="{{ route('spare-parts.index') }}" style="display:flex;gap:8px;align-items:center;background:linear-gradient(145deg,#0c1f38,#071326);border:1px solid #1d4c78;border-radius:12px;padding:10px;margin-bottom:12px">
                    @if($activeCategory)    <input type="hidden" name="category"   value="{{ $activeCategory }}"> @endif
                    @if($activeBrand)       <input type="hidden" name="brand"       value="{{ $activeBrand }}"> @endif
                    @if($activeMotorcycle)  <input type="hidden" name="motorcycle"  value="{{ $activeMotorcycle }}"> @endif
                    <input class="finput" style="flex:1" type="search" name="search" value="{{ request('search') }}"
                           placeholder="{{ __('spare_parts.lp_hero_shop') }}…">
                    <select class="finput" style="min-width:140px" name="sort" onchange="this.form.submit()">
                        <option value="newest"     {{ request('sort','newest') === 'newest'     ? 'selected' : '' }}>{{ __('motorcycles.sort_newest') ?? 'Newest' }}</option>
                        <option value="price_asc"  {{ request('sort') === 'price_asc'  ? 'selected' : '' }}>{{ __('motorcycles.sort_price_asc') }}</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>{{ __('motorcycles.sort_price_desc') }}</option>
                        <option value="name_asc"   {{ request('sort') === 'name_asc'   ? 'selected' : '' }}>{{ __('motorcycles.sort_name_az') }}</option>
                    </select>
                    <button class="btn" type="submit" style="padding:9px 15px;font-size:11px"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>

                @if(isset($spareParts) && method_exists($spareParts, 'total'))
                    <p style="font-size:11px;color:var(--muted);margin:0 0 11px">
                        {{ __('motorcycles.showing') }} <strong class="num-latin">{{ $spareParts->firstItem() }}</strong>–<strong class="num-latin">{{ $spareParts->lastItem() }}</strong>
                        {{ __('motorcycles.of') }} <strong class="num-latin">{{ $spareParts->total() }}</strong>
                    </p>
                @endif

                <div class="products">
                    @foreach($featuredParts as $part)
                        @php
                            $slug        = data_get($part, 'slug');
                            $detailUrl   = $slug ? route('spare-parts.show', $slug) : null;
                            $unavailable = in_array(data_get($part, 'availability_status'), ['out_of_stock', 'discontinued'], true);
                            $badge       = $unavailable ? __('spare_parts.lp_out_of_stock_short') : (data_get($part, 'badge') ?: __('spare_parts.lp_badge_featured'));
                        @endphp
                        <article class="product">
                            <div class="product-image"><img src="{{ $partImage($part) }}" alt="{{ $partName($part) }}" loading="lazy"><span class="tag {{ $unavailable ? 'tag-out' : '' }}">{{ $badge }}</span><span class="heart"><i class="fa-regular fa-heart"></i></span></div>
                            <div class="product-info">
                                <h3>@if($detailUrl)<a href="{{ $detailUrl }}">{{ $partName($part) }}</a>@else{{ $partName($part) }}@endif</h3>
                                <div class="sku num-latin">{{ __('spare_parts.lp_sku_label', ['sku' => data_get($part, 'sku') ?: 'MV-PART']) }}</div>
                                <div class="rating">★★★★★ <span style="color:#8da4bf">{{ __('spare_parts.lp_reviews_count', ['count' => data_get($part, 'reviews_count') ?? 12]) }}</span></div>
                                <div class="price-row"><span class="price num-latin">{{ number_format($partPrice($part)) }} {{ $partCur($part) }}</span>@if(data_get($part, 'old_price'))<span class="old-price num-latin">{{ number_format((float) data_get($part, 'old_price')) }}</span>@endif</div>
                                @if($slug && !$unavailable)
                                    <form method="POST" action="{{ route('cart.add-part', $slug) }}" data-confirm-cart data-part-name="{{ $partName($part) }}" data-part-price="{{ $partPrice($part) }}" data-part-currency="{{ $partCur($part) }}">@csrf<input type="hidden" name="quantity" value="1"><button class="btn" type="submit"><i class="fa-solid fa-cart-plus"></i> {{ __('spare_parts.lp_add_to_cart') }}</button></form>
                                @else
                                    <a class="btn {{ $unavailable ? 'btn-disabled' : 'btn-outline' }}" href="{{ $detailUrl ?? route('spare-parts.index') }}"><i class="fa-solid {{ $unavailable ? 'fa-ban' : $arrow }}"></i> {{ $unavailable ? __('spare_parts.lp_out_of_stock_short') : __('spare_parts.view_details') }}</a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                @if(isset($spareParts) && method_exists($spareParts, 'hasPages') && $spareParts->hasPages())
                    <div class="pager">
                        @if($spareParts->onFirstPage())
                            <span class="btn btn-outline btn-disabled"><i class="fa-solid {{ $isRtl ? 'fa-arrow-right' : 'fa-arrow-left' }}"></i> {{ __('navigation.previous') }}</span>
                        @else
                            <a class="btn btn-outline" href="{{ $spareParts->previousPageUrl() }}"><i class="fa-solid {{ $isRtl ? 'fa-arrow-right' : 'fa-arrow-left' }}"></i> {{ __('navigation.previous') }}</a>
                        @endif
                        <span class="pager-info num-latin">{{ __('spare_parts.lp_showing_parts', ['from' => $spareParts->firstItem(), 'to' => $spareParts->lastItem(), 'total' => $spareParts->total()]) }}</span>
                        @if($spareParts->hasMorePages())
                            <a class="btn" href="{{ $spareParts->nextPageUrl() }}">{{ __('navigation.next') }} <i class="fa-solid {{ $arrow }}"></i></a>
                        @else
                            <span class="btn btn-disabled">{{ __('navigation.next') }} <i class="fa-solid {{ $arrow }}"></i></span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="container reveal"><div class="promo"><div class="promo-copy"><span class="eyebrow">{{ __('spare_parts.lp_promo_eyebrow') }}</span><h2>{{ __('spare_parts.lp_promo_title') }}</h2><p>{{ __('spare_parts.lp_promo_sub') }}</p><a href="#featured" class="btn">{{ __('spare_parts.lp_promo_cta') }} <i class="fa-solid {{ $arrow }}"></i></a></div><div class="discount">{{ __('spare_parts.lp_promo_discount') }} <strong class="num-latin">50%</strong></div></div></section>

    <section class="section container reveal">
        <div class="section-head"><div><h2>{{ __('spare_parts.lp_services_heading') }}</h2><p>{{ __('spare_parts.lp_services_sub') }}</p></div><a href="{{ route('services') }}" class="more">{{ __('spare_parts.lp_services_all') }} <i class="fa-solid {{ $arrow }}"></i></a></div>
        <div class="service-grid">
            @foreach([['fa-screwdriver-wrench', 1],['fa-truck-fast', 2],['fa-people-group', 3],['fa-headset', 4],['fa-gear', 5]] as [$icon, $i])
                <div class="service"><i class="fa-solid {{ $icon }}"></i><h3>{{ __('spare_parts.lp_service_' . $i . '_title') }}</h3><p>{{ __('spare_parts.lp_service_' . $i . '_desc') }}</p></div>
            @endforeach
        </div>
    </section>

    <section class="container reveal" id="fitment"><div class="fitment"><div class="fitment-art"></div><div class="fitment-content"><span class="eyebrow">{{ __('spare_parts.lp_fitment_eyebrow') }}</span><h2>{{ __('spare_parts.lp_fitment_title') }}</h2><p>{{ __('spare_parts.lp_fitment_desc') }}</p>
        <form class="fitment-form" method="GET" action="{{ route('spare-parts.index') }}">
            <select name="brand">
                <option value="">{{ __('spare_parts.lp_fitment_brand') }}</option>
                @foreach(($brands ?? collect()) as $brand)
                    <option value="{{ data_get($brand, 'id') }}" {{ (string) $activeBrand === (string) data_get($brand, 'id') ? 'selected' : '' }}>{{ data_get($brand, 'name') }}</option>
                @endforeach
            </select>
            <select name="motorcycle">
                <option value="">{{ __('spare_parts.lp_fitment_model') }}</option>
                @foreach(($motorcycles ?? collect()) as $motorcycle)
                    <option value="{{ data_get($motorcycle, 'id') }}" {{ (string) $activeMotorcycle === (string) data_get($motorcycle, 'id') ? 'selected' : '' }}>{{ $motoLabel($motorcycle) }}</option>
                @endforeach
            </select>
            <select name="category">
                <option value="">{{ __('spare_parts.lp_fitment_category') }}</option>
                @foreach($categoryRows as $category)
                    <option value="{{ $category->slug }}" {{ (string) $activeCategory === (string) $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            <button class="btn" type="submit"><i class="fa-solid fa-magnifying-glass"></i> {{ __('spare_parts.lp_fitment_submit') }}</button>
        </form></div></div>
    </section>

    <section class="section container reveal"><div class="section-head"><div><h2>{{ __('spare_parts.lp_reviews_heading') }}</h2><p>{{ __('spare_parts.lp_reviews_sub') }}</p></div></div>
        <div class="reviews">
            @foreach([1, 2, 3] as $i)
                <article class="review"><div class="review-top"><img class="avatar" src="https://i.pravatar.cc/100?img={{ $i + 10 }}" alt="" loading="lazy"><div><h3>{{ __('spare_parts.lp_review_' . $i . '_name') }}</h3><small>{{ __('spare_parts.lp_review_role') }}</small></div><div class="rating">★★★★★</div></div><p>{{ __('spare_parts.lp_review_' . $i . '_comment') }}</p></article>
            @endforeach
        </div>
    </section>

    {{-- Editorial strip: static content until an articles module is wired up. --}}
    <section class="section container reveal" id="articles"><div class="section-head"><div><h2>{{ __('spare_parts.lp_articles_heading') }}</h2><p>{{ __('spare_parts.lp_articles_sub') }}</p></div></div>
        <div class="articles">
            @foreach([
                ['title' => __('spare_parts.lp_article_1_title'), 'desc' => __('spare_parts.lp_article_1_desc'), 'image' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=600&q=80'],
                ['title' => __('spare_parts.lp_article_2_title'), 'desc' => __('spare_parts.lp_article_2_desc'), 'image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=600&q=80'],
                ['title' => __('spare_parts.lp_article_3_title'), 'desc' => __('spare_parts.lp_article_3_desc'), 'image' => 'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&w=600&q=80'],
                ['title' => __('spare_parts.lp_article_4_title'), 'desc' => __('spare_parts.lp_article_4_desc'), 'image' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=600&q=80'],
            ] as $article)
                <div class="article"><img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" loading="lazy"><div><h3>{{ $article['title'] }}</h3><p>{{ $article['desc'] }}</p></div></div>
            @endforeach
        </div>
    </section>

    <section class="container reveal"><div class="newsletter"><div><h2>{{ __('spare_parts.lp_news_heading') }}</h2><p>{{ __('spare_parts.lp_news_sub') }}</p></div><form class="news-form" method="GET" action="{{ route('contact') }}"><input type="email" name="email" placeholder="{{ __('spare_parts.lp_news_placeholder') }}" aria-label="{{ __('spare_parts.lp_news_placeholder') }}" required><input type="hidden" name="subject" value="{{ __('spare_parts.lp_news_subject') }}"><input type="hidden" name="enquiry_type" value="general"><button class="btn" type="submit">{{ __('spare_parts.lp_news_btn') }} <i class="fa-solid fa-paper-plane"></i></button></form></div></section>
</main>

<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div><a class="brand footer-logo" href="{{ route('home') }}"><span class="brand-mark"><i class="fa-solid fa-motorcycle"></i></span><span>MOTOVEX<small>RIDE YOUR DREAM</small></span></a><p>{{ __('spare_parts.lp_foot_tagline') }}</p><div class="socials"><a href="#" aria-label="{{ __('footer.facebook') }}"><i class="fa-brands fa-facebook-f"></i></a><a href="#" aria-label="{{ __('footer.instagram') }}"><i class="fa-brands fa-instagram"></i></a><a href="#" aria-label="{{ __('footer.youtube') }}"><i class="fa-brands fa-youtube"></i></a><a href="#" aria-label="{{ __('footer.twitter') }}"><i class="fa-brands fa-x-twitter"></i></a><a href="#" aria-label="{{ __('footer.whatsapp') }}"><i class="fa-brands fa-whatsapp"></i></a></div></div>
            <div><h4>{{ __('spare_parts.lp_foot_links') }}</h4><div class="footer-links"><a href="{{ route('home') }}">{{ __('navigation.home') }}</a><a href="{{ route('motorcycles.index') }}">{{ __('spare_parts.lp_nav_motorcycles') }}</a><a href="{{ route('brands.index') }}">{{ __('navigation.brands') }}</a><a href="{{ route('spare-parts.index') }}">{{ __('navigation.spare_parts') }}</a><a href="{{ route('promotions') }}">{{ __('navigation.promotions') }}</a></div></div>
            <div><h4>{{ __('spare_parts.lp_foot_support') }}</h4><div class="footer-links"><a href="{{ route('contact') }}">{{ __('navigation.contact') }}</a><a href="{{ route('services') }}">{{ __('navigation.services') }}</a><a href="{{ route('cart.index') }}">{{ __('navigation.cart') }}</a><a href="{{ $accountUrl }}">{{ __('navigation.my_account') }}</a></div></div>
            <div><h4>{{ __('spare_parts.lp_foot_contact') }}</h4><div class="footer-links"><a href="tel:+201000000000"><i class="fa-solid fa-phone"></i> <span class="num-latin">+20 100 000 0000</span></a><a href="mailto:info@motovex.com"><i class="fa-regular fa-envelope"></i> <span class="num-latin">info@motovex.com</span></a><a href="{{ route('contact') }}"><i class="fa-solid fa-location-dot"></i> {{ __('spare_parts.lp_foot_address') }}</a><span><i class="fa-regular fa-clock"></i> {{ __('spare_parts.lp_foot_hours') }}</span></div></div>
            <div><h4>{{ __('spare_parts.lp_foot_app') }}</h4><p>{{ __('spare_parts.lp_foot_app_text') }}</p><a class="app-badge" href="#"><i class="fa-brands fa-google-play"></i> Google Play</a><br><a class="app-badge" href="#"><i class="fa-brands fa-apple"></i> App Store</a></div>
        </div>
        <div class="copyright"><span>{{ __('spare_parts.lp_foot_copyright', ['year' => date('Y')]) }}</span><span>{{ __('spare_parts.lp_foot_made') }} <i class="fa-solid fa-heart" style="color:#a45cff"></i></span></div>
    </div>
</footer>
<script>
    const menuToggle=document.getElementById('menuToggle');
    const navLinks=document.getElementById('navLinks');
    menuToggle?.addEventListener('click',()=>navLinks.classList.toggle('open'));
    const revealObserver=new IntersectionObserver(entries=>entries.forEach(entry=>{if(entry.isIntersecting){entry.target.classList.add('visible');revealObserver.unobserve(entry.target)}}),{threshold:.08});
    document.querySelectorAll('.reveal').forEach(el=>revealObserver.observe(el));
    // Mobile filter toggle
    const filterToggle = document.getElementById('filterToggle');
    const filterPanel  = document.getElementById('filterPanel');
    if (filterToggle && filterPanel) {
        filterToggle.addEventListener('click', () => {
            const open = filterPanel.classList.toggle('open');
            filterToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            filterToggle.querySelector('.fa-chevron-down').style.transform = open ? 'rotate(180deg)' : '';
        });
    }
</script>
@include('partials.cart-confirm', ['theme' => 'dark'])
</body>
</html>
