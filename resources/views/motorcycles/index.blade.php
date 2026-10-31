{{-- =============================================================================
     MOTOVEX — MOTORCYCLE SHOWROOM (self-contained dark theme)
     resources/views/motorcycles/index.blade.php

     Same design system as resources/views/spare-parts/index.blade.php:
     identical palette, topbar, hero, trust strip, cards, promo, reviews,
     articles, newsletter and footer, so the two landing pages read as one.

     Bilingual (en / ar): every string goes through lang/{locale}/motorcycles.php
     ('lp_*', 'categories.*' and the pre-existing catalogue keys).
     Direction is dynamic — the design is authored RTL and a [dir="ltr"]
     block flips the few physical CSS properties it uses.

     Data contract (MotorcycleController::index):
       $motorcycles   LengthAwarePaginator of Motorcycle (with brand)
       $brands        Collection of Brand + motorcycles_count
       $categories    Collection of distinct category strings
       $footerBrands  Collection of Brand (6)
     Query contract: search, brand[] (ids), category, min_cc, max_cc,
                     sort (name|price_asc|price_desc|power)
     ============================================================================= --}}
@php
    $locale = app()->getLocale();
    $isRtl  = $locale === 'ar';
    $arrow  = $isRtl ? 'fa-arrow-left' : 'fa-arrow-right';

    $hasFilters     = request()->hasAny(['search', 'brand', 'category', 'min_cc', 'max_cc']);
    $sortKey        = request('sort', 'name');
    $catalogueCount = ($brands ?? collect())->sum('motorcycles_count');
    $selectedBrands = (array) request('brand', []);
    $activeBrands   = ($brands ?? collect())->whereIn('id', $selectedBrands);

    // Clean catalogue URLs — page is always reset, no trailing ? or &.
    $cleanUrl = fn (array $params) => rtrim(rtrim(request()->fullUrlWithQuery($params + ['page' => null]), '&'), '?');
    $drop     = fn (array $keys) => $cleanUrl(array_fill_keys($keys, null));
    $set      = fn (array $params) => $cleanUrl($params);

    // ── Category artwork + icons (categories come from the DB as plain strings)
    $img = fn (string $file) => asset('images/motorcycles/' . $file);
    $defaultCatImage = $img('atlas-nomad-900.jpg');
    $catImages = [
        'urban'      => $img('neo-city-125.jpg'),
        'sport'      => $img('vayu-r1-1100.jpg'),
        'adventure'  => $img('atlas-pioneer-650.jpg'),
        'cruiser'    => $img('aurum-sovereign-1200.jpg'),
        'touring'    => $img('atlas-traverse-1200.jpg'),
        'off-road'   => $img('atlas-ridge-850.jpg'),
        'heritage'   => $img('aurum-heritage-650.jpg'),
        'electric'   => $img('neo-s1.jpg'),
        'scooter'    => $img('neo-sprint-400.jpg'),
        'naked'      => $img('vayu-street-790.jpg'),
    ];
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
        'naked'      => 'fa-motorcycle',
    ];

    $categoryRows = collect($categories ?? [])
        ->map(function ($cat) use ($catImages, $catIcons, $defaultCatImage) {
            $key      = strtolower(trim((string) $cat));
            $labelKey = 'motorcycles.categories.' . $key;
            $label    = __($labelKey);

            return (object) [
                'value' => (string) $cat,
                'key'   => $key,
                'name'  => $label !== $labelKey ? $label : ucwords(str_replace(['-', '_'], ' ', $key)),
                'icon'  => $catIcons[$key] ?? 'fa-tag',
                'image' => $catImages[$key] ?? $defaultCatImage,
            ];
        })
        ->values();

    $statusTones = ['available' => 'b-ok', 'reserved' => 'b-warn', 'sold' => 'b-bad'];
    $statusLabel = function (string $status) {
        $key   = 'motorcycles.' . $status;
        $value = __($key);

        return $value !== $key ? $value : ucfirst(str_replace('_', ' ', $status));
    };

    // Translate a raw DB category value back through the categories map.
    $catName = function ($value) use ($categoryRows) {
        $match = $categoryRows->first(fn ($row) => strcasecmp($row->value, (string) $value) === 0);

        return $match
            ? $match->name
            : ucwords(str_replace(['-', '_'], ' ', strtolower(trim((string) $value))));
    };

    // Brand logos are only rendered when the asset really exists (some rows in
    // brands.logo_url point at files that were never uploaded).
    $resolveLogo = function ($brand) {
        $logo = trim((string) data_get($brand, 'logo_url'));

        if ($logo === '') {
            return null;
        }

        if (preg_match('#^https?://#i', $logo)) {
            return $logo;
        }

        $relative = ltrim($logo, '/');

        return is_file(public_path($relative)) ? asset($relative) : null;
    };

    // ── Cart badge (same session shape used by partials/navbar) ──────────────
    $cart       = session('cart', ['motorcycles' => [], 'spare_parts' => []]);
    $cartCount  = count($cart['motorcycles'] ?? []) + array_sum(array_column($cart['spare_parts'] ?? [], 'quantity'));
    $accountUrl = auth()->check()
        ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('account.dashboard'))
        : route('login');

    $testRideUrl = route('contact', ['enquiry_type' => 'test_drive', 'subject' => __('motorcycles.lp_promo_title')]);

    // Contact details for the footer (footer.* holds the display strings).
    $footerEmail = (string) __('footer.mail_text');
    $footerPhone = (string) __('footer.phone_text');
    $telHref     = 'tel:' . preg_replace('/[^\d+]/', '', $footerPhone);
@endphp
<!DOCTYPE html>
<html lang="{{ $isRtl ? 'ar' : 'en' }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ __('motorcycles.index_meta_desc') }}">
    <title>{{ $title ?? __('motorcycles.index_meta_title') }}</title>
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
        .hero{min-height:430px;position:relative;display:flex;align-items:center;overflow:hidden;background:linear-gradient(90deg,#020918 0%,rgba(2,9,24,.94) 34%,rgba(2,9,24,.2) 100%),url('{{ $img('vayu-r1-1100.jpg') }}') center/cover;border-bottom:1px solid #14508a}.hero:after{content:"";position:absolute;inset:0;background:linear-gradient(0deg,#050b18 0%,transparent 26%,transparent 80%,#07132966);pointer-events:none}.hero-content{position:relative;z-index:1;max-width:560px;padding:62px 0}.eyebrow{color:#a979ff;font-size:12px;font-weight:800;letter-spacing:1px}.hero h1{font-size:clamp(31px,4.8vw,54px);line-height:1.28;margin:12px 0 8px;font-weight:900}.hero h1 span{display:block;color:#a66cff;text-shadow:0 0 24px #8b40ff66}.hero p{color:#c0d2e8;line-height:1.9;font-size:13.5px;max-width:470px}.actions{display:flex;gap:12px;margin-top:22px;flex-wrap:wrap}.btn{border:1px solid #a45cff;background:linear-gradient(110deg,#853dff,#4a35db);color:white;padding:11px 22px;border-radius:9px;font-weight:800;font-size:12px;box-shadow:0 0 20px #713bff66;cursor:pointer;display:inline-flex;align-items:center;gap:8px;justify-content:center;transition:.25s}.btn:hover{transform:translateY(-3px);box-shadow:0 0 28px #853dff99}.btn-outline{background:#07162cbb;border-color:#37618c;box-shadow:none}.btn-disabled{background:#16263b;border-color:#2b4463;color:#9db1c6;box-shadow:none;cursor:default}.btn-disabled:hover{transform:none;box-shadow:none}.hero-glow{position:absolute;width:320px;height:320px;border-radius:50%;border:1px solid #178bff55;box-shadow:0 0 70px #087dff33;left:10%;top:10%}
        .crumbs{display:flex;align-items:center;gap:8px;font-size:10.5px;color:#8ea7c4;margin-bottom:14px}.crumbs i{font-size:8px;opacity:.6}.crumbs a:hover{color:#b78aff}.crumbs span{color:#d6e6f8}
        .pill{display:inline-flex;align-items:center;gap:6px;background:#0d2440;border:1px solid #2b5d8f;border-radius:20px;padding:4px 11px;font-size:11px;color:#b7cde6;font-weight:700;vertical-align:middle}
        .trust-strip{position:relative;margin-top:-1px;background:linear-gradient(90deg,#0b1a31,#071126,#0b1a31);border-block:1px solid #17416c;box-shadow:0 0 28px #087dff22}.trust-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;padding:19px 0}.trust-item{text-align:center;color:#c8d9ed;font-size:11px}.trust-item i{display:block;color:#54baff;font-size:24px;margin-bottom:7px;text-shadow:0 0 15px #169dff}
        .section{padding:45px 0}.section-head{display:flex;align-items:end;justify-content:space-between;gap:15px;margin-bottom:19px}.section-head h2{font-size:22px;margin:0;font-weight:900}.section-head p{font-size:11px;color:var(--muted);margin:3px 0 0}.more{font-size:11px;color:#b88aff}.categories{display:grid;grid-template-columns:repeat(8,1fr);gap:12px}.category{background:linear-gradient(145deg,#0d2039,#071326);border:1px solid #214b73;border-radius:12px;overflow:hidden;text-align:center;transition:.3s;box-shadow:inset 0 0 15px #168bff0c}.category:hover{transform:translateY(-5px);border-color:#42baff;box-shadow:0 0 20px #148fff33}.category img{width:100%;height:100px;object-fit:cover;display:block;filter:saturate(.85)}.category span{display:block;padding:9px 4px;font-size:11px;font-weight:800}.category.is-active{border-color:#8d4cff;box-shadow:0 0 20px #733aff55}
        .showroom{display:grid;grid-template-columns:272px 1fr;gap:16px;align-items:start}
        .fpanel{background:linear-gradient(160deg,#0c1f38,#071326);border:1px solid #1d4c78;border-radius:14px;padding:15px;position:sticky;top:82px}.fpanel h2{margin:0;font-size:14px;font-weight:900}.fpanel .fsub{font-size:10px;color:var(--muted);margin:3px 0 13px;line-height:1.7}.fpanel .reset{font-size:10px;color:#b88aff;font-weight:800}.fset{border:0;padding:0;margin:0 0 15px}.fset legend{font-size:10px;font-weight:800;letter-spacing:.5px;color:#9fb8d3;margin-bottom:7px;padding:0}.fopt{display:flex;align-items:center;gap:8px;font-size:11px;color:#d3e2f2;padding:4px 0;cursor:pointer}.fopt input{accent-color:#853cff;width:14px;height:14px;flex:none;cursor:pointer}.fopt .cnt{margin-inline-start:auto;font-size:9px;color:#8ea9c6;background:#0c2039;border:1px solid #1c4066;border-radius:20px;padding:1px 7px}.finput{width:100%;background:#071326;color:#dceaff;border:1px solid #23466b;border-radius:8px;padding:9px;font-size:11px}.finput:focus{outline:none;border-color:#4c7fc4;box-shadow:0 0 12px #178bff33}.cc{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;gap:6px;color:#8ea9c6;font-size:11px}.fbtns{display:grid;gap:7px}.fbtns .btn,.fbtns .btn-outline{width:100%;padding:9px;font-size:11px}.ftoggle{display:none;width:100%;align-items:center;justify-content:space-between;background:linear-gradient(145deg,#102642,#071326);border:1px solid #1d4c78;color:#eaf3ff;border-radius:12px;padding:11px 14px;font-size:12px;font-weight:800;cursor:pointer;margin-bottom:11px}
        .toolbar{display:grid;grid-template-columns:1fr auto auto auto;gap:8px;align-items:center;background:linear-gradient(145deg,#0c1f38,#071326);border:1px solid #1d4c78;border-radius:12px;padding:10px;margin-bottom:12px}.toolbar-form{display:grid;grid-template-columns:1fr auto auto auto;gap:8px;width:100%;align-items:center}.toolbar .btn{padding:9px 15px;font-size:11px}.vtoggle{display:flex;gap:5px}.vbtn{width:32px;height:32px;border:1px solid #23466b;background:#071326;color:#9db1c6;border-radius:8px;cursor:pointer;display:grid;place-items:center;font-size:12px;transition:.2s}.vbtn.on{background:#173253;color:#fff;border-color:#4c7fc4}.count{font-size:11px;color:var(--muted);margin:0 0 11px}
        .chips{display:flex;flex-wrap:wrap;gap:7px;align-items:center;margin-bottom:12px}.chip-x{display:inline-flex;align-items:center;gap:6px;background:#0c2039;border:1px solid #26547d;color:#c8daf0;border-radius:20px;padding:4px 10px;font-size:10px;transition:.2s}.chip-x:hover{border-color:#8d4cff;color:#dcbcff}.chip-x i{font-size:9px;opacity:.75}.chip-clear{font-size:10px;color:#b88aff;font-weight:800}
        .bikes{display:grid;grid-template-columns:repeat(4,1fr);gap:13px}.bikes.list{grid-template-columns:1fr;gap:11px}.product{position:relative;background:linear-gradient(155deg,#102642,#071326 72%);border:1px solid #1d4c78;border-radius:14px;overflow:hidden;box-shadow:0 8px 24px #0005;transition:.3s;display:flex;flex-direction:column}.product:hover{transform:translateY(-6px);border-color:#8d4cff;box-shadow:0 0 25px #733aff33}.product-image{height:158px;position:relative;background:#071426;overflow:hidden;flex:none}.product-image img{width:100%;height:100%;object-fit:cover;display:block;transition:.5s}.product:hover .product-image img{transform:scale(1.07)}.noimg{height:100%;display:grid;place-items:center;gap:7px;color:#57748f;background:#071426;font-size:10px;text-align:center;padding:12px}.noimg i{font-size:24px}.badges{position:absolute;top:9px;right:9px;display:flex;flex-direction:column;gap:5px;align-items:flex-end;z-index:2}.b{font-size:9px;font-weight:800;border-radius:5px;padding:3px 7px;color:#fff;letter-spacing:.2px}.b-feat{background:#7d39f4;box-shadow:0 0 14px #7d39f488}.b-ok{background:#12a150}.b-warn{background:#bf8413}.b-bad{background:#d33b52}.b-neutral{background:#5c6d84}.product-info{padding:11px;display:block;flex:1}.brandline{font-size:9px;font-weight:800;letter-spacing:.7px;color:#6fb6ff;text-transform:uppercase}.product-info h3{font-size:12.5px;margin:3px 0 0;font-weight:800;line-height:1.45}.product-info h3 a:hover{color:#b78aff}.desc{font-size:10px;color:#9db1c6;line-height:1.7;margin:6px 0 0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}.speclist{display:flex;flex-wrap:wrap;gap:5px;margin:8px 0 0;padding:0;list-style:none}.speclist li{font-size:9px;color:#bcd2ea;background:#0c2039;border:1px solid #1c4066;border-radius:5px;padding:2px 6px}.pm-side{margin-top:10px}.price-row{display:flex;align-items:baseline;gap:6px;flex-wrap:wrap;margin-bottom:8px}.price{color:#ffc13f;font-size:14px;font-weight:900}.price small{font-size:9px;color:#8ea9c6;font-weight:700}.price-req{font-size:11px;color:#b7cde6;font-weight:800}.card-actions{display:grid;gap:6px}.card-actions .btn{width:100%;padding:8px;font-size:10px}@media(min-width:1001px){.bikes.list .product{flex-direction:row}.bikes.list .product-image{width:230px;height:auto;min-height:150px}.bikes.list .product-info{display:grid;grid-template-columns:1fr 216px;gap:16px;align-items:center;padding:14px}.bikes.list .pm-side{margin-top:0}}
        .pager{display:flex;align-items:center;justify-content:center;gap:6px;margin-top:20px;flex-wrap:wrap}.pbtn{min-width:32px;height:32px;padding:0 9px;border:1px solid #23466b;background:#071326;color:#cfe0f2;border-radius:8px;font-size:11px;display:inline-grid;place-items:center;transition:.2s}.pbtn:hover{border-color:#4c7fc4;color:#fff}.pbtn.on{background:#173253;border-color:#4c7fc4;color:#fff}.pbtn.dis{opacity:.4;pointer-events:none}.pager .gap{color:#5f7a95;font-size:11px;padding:0 2px}
        .empty{border:1px dashed #2b5d8f;border-radius:14px;background:linear-gradient(150deg,#0b1e37,#071326);padding:36px 20px;text-align:center}.empty i{font-size:34px;color:#3f6fa0;margin-bottom:12px}.empty h2{margin:0 0 7px;font-size:17px}.empty p{margin:0 auto;font-size:11.5px;color:#9db1c6;max-width:460px;line-height:1.9}.empty .actions{justify-content:center}
        .service-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:12px}.service-grid.four{grid-template-columns:repeat(4,1fr)}.service{background:linear-gradient(145deg,#102642,#071326);border:1px solid #204b75;border-radius:13px;text-align:center;padding:20px 10px;transition:.25s}.service:hover{border-color:#8b51ff;transform:translateY(-4px)}.service i{font-size:29px;color:#63bfff;text-shadow:0 0 16px #168bff;margin-bottom:9px}.service h3{font-size:12px;margin:0 0 4px}.service p{font-size:10px;color:#91a9c6;margin:0}
        .promo{min-height:165px;border:1px solid #285a89;border-radius:17px;overflow:hidden;position:relative;background:linear-gradient(90deg,#09182c 0%,#09182cdd 43%,#07172b55),url('{{ $img('atlas-ridge-850.jpg') }}') center/cover;display:flex;align-items:center;padding:25px 35px;box-shadow:0 0 28px #0a8dff22}.promo-copy{position:relative;z-index:1}.promo h2{margin:0;color:#ffbd3c;font-size:24px}.promo p{margin:5px 0 14px;font-weight:700;font-size:12px;color:#c3d5e8}.discount{position:absolute;left:30px;top:50%;transform:translateY(-50%);background:#ff3154;border-radius:12px;padding:12px 18px;text-align:center;font-size:13px;font-weight:900;box-shadow:0 0 25px #ff315477}.discount strong{display:block;font-size:26px;line-height:1.1}
        .brand-grid{display:grid;grid-template-columns:repeat(8,1fr);gap:12px}.brand-tile{background:linear-gradient(145deg,#0d2039,#071326);border:1px solid #214b73;border-radius:12px;padding:14px 8px;text-align:center;transition:.3s}.brand-tile:hover{transform:translateY(-5px);border-color:#42baff;box-shadow:0 0 20px #148fff33}.brand-tile img{width:100%;height:44px;object-fit:contain;display:block;filter:brightness(0) invert(.86)}.brand-tile .ph{height:44px;display:grid;place-items:center;font-size:17px;color:#7fa8d0}.brand-tile span{display:block;font-size:10px;font-weight:800;margin-top:8px}.brand-tile small{display:block;font-size:9px;color:#7f97b4}
        .reviews{display:grid;grid-template-columns:repeat(3,1fr);gap:13px}.review{background:linear-gradient(145deg,#10243d,#071326);border:1px solid #23496f;border-radius:13px;padding:15px}.review-top{display:flex;align-items:center;gap:10px}.avatar{width:38px;height:38px;border-radius:50%;object-fit:cover}.review h3{font-size:11px;margin:0}.review small{font-size:9px;color:#8da4bf}.review p{font-size:10px;color:#c3d2e4;line-height:1.85;margin:12px 0 0}.review .rating{margin-right:auto}.rating{color:var(--gold);font-size:10px}
        .articles{display:grid;grid-template-columns:repeat(4,1fr);gap:13px}.article{border:1px solid #204a73;border-radius:12px;overflow:hidden;background:#0a1a30;transition:.25s}.article:hover{border-color:#42baff;transform:translateY(-4px)}.article img{width:100%;height:115px;object-fit:cover;display:block}.article div{padding:10px}.article h3{font-size:11px;margin:0 0 5px}.article p{font-size:9px;color:#93a9c2;margin:0}
        .cta{border:1px solid #28587e;border-radius:16px;background:linear-gradient(90deg,#0a1c33,#0e2846);padding:24px 28px;display:flex;align-items:center;justify-content:space-between;gap:20px;box-shadow:0 0 26px #0a8dff1c}.cta h2{margin:0;font-size:19px}.cta p{margin:4px 0 0;font-size:11px;color:#a9bdd5;line-height:1.85;max-width:640px}
        .newsletter{position:relative;overflow:hidden;border:1px solid #315b8b;border-radius:16px;padding:25px 30px;background:linear-gradient(90deg,#08172bdf,#08172be8),url('{{ $img('aurum-grandura.jpg') }}') center/cover;display:flex;justify-content:space-between;align-items:center;gap:20px}.newsletter h2{margin:0;font-size:20px}.newsletter p{margin:3px 0 0;color:#a9bdd5;font-size:11px}.news-form{display:flex;gap:8px;width:min(440px,100%)}.news-form input{flex:1;min-width:0;background:#f1f5fb;border:0;border-radius:8px;padding:11px;color:#101b2b;font-size:11px}
        .footer{background:#030916;border-top:1px solid #1b3c62;margin-top:45px;padding:34px 0 0}.footer-grid{display:grid;grid-template-columns:1.4fr 1fr 1fr 1.2fr 1.1fr;gap:24px;padding-bottom:25px}.footer-logo{font-size:20px;font-weight:900}.footer-logo i{color:#a56bff;margin-left:7px}.footer p,.footer a{color:#8fa6c2;font-size:10px;line-height:2}.footer h4{font-size:12px;margin:4px 0 12px;color:#f2f7ff}.footer-links{display:grid;gap:3px}.footer a:hover{color:#b68aff}.socials{display:flex;gap:8px;margin-top:12px}.socials a{width:27px;height:27px;border:1px solid #25476d;border-radius:7px;display:grid;place-items:center}.copyright{border-top:1px solid #172e4b;padding:11px 0;display:flex;justify-content:space-between;gap:10px;color:#7890ac;font-size:9px}
        .overlay{position:fixed;inset:0;z-index:200;display:none;align-items:center;justify-content:center;padding:16px;background:rgba(2,8,18,.8);backdrop-filter:blur(6px)}.overlay.open{display:flex}.mbox{width:min(490px,100%);background:linear-gradient(160deg,#0d2039,#071326);border:1px solid #2f5b8a;border-radius:16px;overflow:hidden;box-shadow:0 30px 70px #000c}.mhead{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:14px 16px;border-bottom:1px solid #17406a}.mhead h3{margin:0;font-size:13px;font-weight:900}.mhead small{display:block;font-size:10px;color:var(--muted);margin-top:2px}.mclose{background:none;border:1px solid #23466b;color:#9db1c6;width:28px;height:28px;border-radius:8px;cursor:pointer}.mclose:hover{color:#fff;border-color:#4c7fc4}.mbody{padding:16px;display:grid;grid-template-columns:120px 1fr;gap:14px}.mimgwrap{width:120px;height:92px;border-radius:10px;overflow:hidden;background:#071426;border:1px solid #1d4c78;display:grid;place-items:center;color:#4e6a88}.mimgwrap img{width:100%;height:100%;object-fit:cover}.mbox .brandline{font-size:9px}.mbox h4{margin:3px 0 0;font-size:15px}.mspecs{display:flex;flex-wrap:wrap;gap:5px;margin-top:8px;padding:0;list-style:none}.mspecs li{font-size:9px;color:#bcd2ea;background:#0c2039;border:1px solid #1c4066;border-radius:5px;padding:2px 6px}.msum{margin:14px 0 0;border:1px solid #1d4c78;border-radius:10px;background:#081a30;padding:12px;display:grid;gap:6px;font-size:11px}.msum .row{display:flex;justify-content:space-between;gap:10px;color:#a9bdd5}.msum .row strong{color:#eaf3ff}.msum .tot{border-top:1px solid #1d4c78;padding-top:7px;font-weight:900;color:#fff;font-size:13px}.msum .tot span:last-child{color:#ffc13f}.mnote{font-size:10px;color:#8fa6c2;margin:11px 0 0;display:flex;gap:7px;align-items:flex-start;line-height:1.8}.mnote i{color:#63bfff;margin-top:2px}.mfoot{padding:14px 16px;border-top:1px solid #17406a;display:grid;gap:7px}.mfoot .btn{width:100%}.toast{position:fixed;left:50%;bottom:22px;transform:translate(-50%,18px);z-index:300;background:#0d2039;border:1px solid #2f5b8a;color:#fff;padding:11px 16px;border-radius:10px;font-size:11px;opacity:0;pointer-events:none;transition:.3s;max-width:min(92vw,430px);text-align:center}.toast.on{opacity:1;transform:translate(-50%,0)}.toast.ok{background:#0b3b26;border-color:#1c7a4f}.toast.err{background:#3d1220;border-color:#7a2438}.toast.info{background:#0b2a44;border-color:#1d5f96}
        .reveal{opacity:0;transform:translateY(22px);transition:opacity .7s,transform .7s}.reveal.visible{opacity:1;transform:none}
        [dir="rtl"] .num-latin{direction:ltr;unicode-bidi:plaintext;display:inline-block}
        @media(max-width:1000px){.navlinks{gap:12px}.showroom{grid-template-columns:1fr}.fpanel{position:static}.categories{grid-template-columns:repeat(4,1fr)}.bikes{grid-template-columns:repeat(3,1fr)}.brand-grid{grid-template-columns:repeat(4,1fr)}.footer-grid{grid-template-columns:repeat(3,1fr)}.service-grid,.service-grid.four{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:700px){.topbar{height:59px}.navlinks{display:none;position:absolute;top:59px;right:0;left:0;background:#061124;padding:17px;flex-direction:column;align-items:stretch;border-bottom:1px solid #24496e}.navlinks.open{display:flex}.menu-toggle{display:block}.brand{font-size:17px}.navtools{gap:7px}.navtools .navicon:nth-last-child(-n+2){display:none}.hero{min-height:380px;background-position:58% center}.hero-content{padding:44px 0;max-width:92%}.hero h1{font-size:32px}.hero p{font-size:12px}.trust-grid{grid-template-columns:repeat(5,1fr);gap:4px}.trust-item{font-size:8px}.trust-item i{font-size:19px}.section{padding:32px 0}.section-head{display:block}.section-head h2{font-size:18px}.section .more{display:inline-block;margin-top:7px}.categories{grid-template-columns:repeat(4,1fr);gap:8px}.category img{height:78px}.category span{font-size:9px}.toolbar,.toolbar-form{grid-template-columns:1fr 1fr}.toolbar-form .btn{grid-column:1/-1}.ftoggle{display:flex}.fpanel{display:none}.fpanel.open{display:block}.bikes{grid-template-columns:repeat(2,1fr);gap:9px}.product-image{height:126px}.product-info{padding:9px}.product-info h3{font-size:11px}.desc{display:none}.promo{min-height:150px;padding:18px;flex-direction:column;align-items:flex-start}.promo h2{font-size:18px}.promo p{font-size:10.5px}.discount{position:static;transform:none;margin-top:12px;padding:8px 11px;font-size:10px}.discount strong{font-size:22px}.brand-grid{grid-template-columns:repeat(4,1fr);gap:8px}.brand-tile img,.brand-tile .ph{height:32px}.reviews{grid-template-columns:1fr}.articles{grid-template-columns:repeat(2,1fr)}.article img{height:92px}.cta{display:block;padding:20px}.cta .actions{margin-top:14px}.newsletter{display:block;padding:20px}.news-form{margin-top:14px}.footer-grid{grid-template-columns:repeat(2,1fr);gap:18px}.footer-grid>div:first-child{grid-column:1/-1}.copyright{font-size:8px}.mbody{grid-template-columns:1fr}.mimgwrap{width:100%;height:120px}}
        @media(max-width:390px){.navtools .lang{display:none}.bikes{gap:7px}.product-image{height:108px}.brand-grid{grid-template-columns:repeat(3,1fr)}.service-grid,.service-grid.four{grid-template-columns:1fr}}
        @media(prefers-reduced-motion:reduce){.reveal{opacity:1;transform:none;transition:none}.product,.category,.service,.brand-tile,.article,.btn,.vbtn,.chip-x{transition:none}.product:hover,.category:hover,.service:hover,.brand-tile:hover,.article:hover,.btn:hover{transform:none}}
    </style>
    @unless($isRtl)
    {{-- ── LTR mirror for the handful of physical properties the design uses ── --}}
    <style>
        [dir="ltr"] body{font-family:'Plus Jakarta Sans','Cairo',sans-serif}
        [dir="ltr"] .hero{background-image:linear-gradient(270deg,#020918 0%,rgba(2,9,24,.94) 34%,rgba(2,9,24,.2) 100%),url('{{ $img('vayu-r1-1100.jpg') }}')}
        [dir="ltr"] .hero-glow{left:auto;right:10%}
        [dir="ltr"] .promo{background-image:linear-gradient(270deg,#09182c 0%,#09182cdd 43%,#07172b55),url('{{ $img('atlas-ridge-850.jpg') }}')}
        [dir="ltr"] .cta{background-image:linear-gradient(270deg,#0a1c33,#0e2846)}
        [dir="ltr"] .newsletter{background-image:linear-gradient(270deg,#08172bdf,#08172be8),url('{{ $img('aurum-grandura.jpg') }}')}
        [dir="ltr"] .badges{right:auto;left:9px;align-items:flex-start}
        [dir="ltr"] .cart-dot{right:auto;left:-4px}
        [dir="ltr"] .discount{left:auto;right:30px}
        [dir="ltr"] .review .rating{margin-right:0;margin-left:auto}
        [dir="ltr"] .footer-logo i{margin-left:0;margin-right:7px}
        @media(max-width:700px){[dir="ltr"] .discount{position:static;transform:none}[dir="ltr"] .navlinks{right:0;left:0}}
    </style>
    @endunless
    <style>
        .hero {
            background-image: linear-gradient({{ $isRtl ? '270deg' : '90deg' }}, rgba(2,9,24,.76) 0%, rgba(2,9,24,.48) 42%, rgba(2,9,24,.16) 100%), url('{{ asset('storage/motorcycles/kawasaki-ninja-zx-6r.jpg') }}');
        }
    </style>
</head>
<body>
@include('partials.navbar')

<main>
    {{-- ═══════════════ 1. HERO ═══════════════ --}}
    <section class="hero">
        <div class="container"><div class="hero-content">
            <nav class="crumbs" aria-label="{{ __('common.breadcrumb') }}">
                <a href="{{ route('home') }}"><i class="fa-solid fa-house" aria-hidden="true"></i> {{ __('common.home') }}</a>
                <i class="fa-solid {{ $isRtl ? 'fa-chevron-left' : 'fa-chevron-right' }}" aria-hidden="true"></i>
                <span aria-current="page">{{ __('motorcycles.showroom_title') }}</span>
            </nav>
            <div class="eyebrow">{{ __('motorcycles.lp_hero_eyebrow') }}</div>
            <h1>{{ __('motorcycles.lp_hero_title') }} <span>{{ __('motorcycles.lp_hero_title_em') }}</span></h1>
            <p>{{ __('motorcycles.showroom_intro') }}</p>
            <div class="actions">
                <a href="#catalogue" class="btn"><i class="fa-solid fa-motorcycle"></i> {{ __('motorcycles.lp_hero_browse') }}</a>
                <a href="{{ route('contact') }}" class="btn btn-outline"><i class="fa-solid fa-headset"></i> {{ __('motorcycles.talk_to_expert') }}</a>
            </div>
        </div></div>
    </section>

    {{-- ═══════════════ 2. TRUST STRIP (live counts) ═══════════════ --}}
    <section class="trust-strip" aria-label="{{ __('motorcycles.trust_aria') }}"><div class="container trust-grid">
        <div class="trust-item"><i class="fa-solid fa-shield-halved"></i><span class="num-latin">{{ $catalogueCount }}</span> {{ __('motorcycles.models_listed') }}</div>
        <div class="trust-item"><i class="fa-solid fa-award"></i><span class="num-latin">{{ ($brands ?? collect())->count() }}</span> {{ __('motorcycles.brands_in_catalogue') }}</div>
        <div class="trust-item"><i class="fa-solid fa-layer-group"></i><span class="num-latin">{{ $categoryRows->count() }}</span> {{ __('motorcycles.categories_count') }}</div>
        <div class="trust-item"><i class="fa-solid fa-cart-shopping"></i><strong>{{ __('motorcycles.order_online') }}</strong> {{ __('motorcycles.order_online_desc') }}</div>
        <div class="trust-item"><i class="fa-solid fa-headset"></i><strong>{{ __('motorcycles.lp_cta_primary') }}</strong> {{ __('motorcycles.lp_trust_4') }}</div>
    </div></section>

    {{-- ═══════════════ 3. BROWSE BY STYLE ═══════════════ --}}
    @if($categoryRows->isNotEmpty())
    <section class="section container reveal" id="styles">
        <div class="section-head"><div><h2>{{ __('motorcycles.lp_cats_heading') }}</h2><p>{{ __('motorcycles.lp_cats_sub') }}</p></div><a class="more" href="{{ $drop(['category']) }}">{{ __('motorcycles.lp_cats_all') }} <i class="fa-solid {{ $arrow }}"></i></a></div>
        <div class="categories">
            @foreach($categoryRows as $category)
                <a class="category {{ request('category') === $category->value ? 'is-active' : '' }}" href="{{ $set(['category' => $category->value]) }}" @if(request('category') === $category->value) aria-current="true" @endif>
                    <img src="{{ $category->image }}" alt="{{ $category->name }}" loading="lazy" decoding="async">
                    <span>{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ═══════════════ 4. SHOWROOM: filters + results ═══════════════ --}}
    <section class="section container" id="catalogue" style="padding-top:10px">
        <div class="section-head">
            <div>
                <h2>{{ __('motorcycles.lp_catalogue_heading') }} <span class="pill"><i class="fa-solid fa-motorcycle" aria-hidden="true"></i> <span class="num-latin">{{ $catalogueCount }}</span> {{ $catalogueCount === 1 ? __('motorcycles.model') : __('motorcycles.models') }}</span></h2>
                <p>{{ __('motorcycles.lp_catalogue_sub') }}</p>
            </div>
            @if($hasFilters)
                <a class="more" href="{{ route('motorcycles.index') }}">{{ __('motorcycles.clear_all') }} <i class="fa-solid fa-arrows-rotate"></i></a>
            @endif
        </div>

        <div class="showroom">
            {{-- ---------- Filter panel ---------- --}}
            <aside>
                <button type="button" class="ftoggle" id="filterToggle" aria-expanded="false" aria-controls="filterPanel">
                    <span><i class="fa-solid fa-sliders" aria-hidden="true"></i> {{ __('motorcycles.filters') }}</span>
                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                </button>

                <div class="fpanel" id="filterPanel">
                    <h2>{{ __('motorcycles.lp_filter_heading') }}</h2>
                    <p class="fsub">{{ __('motorcycles.lp_filter_sub') }}
                        @if($hasFilters) <br><a class="reset" href="{{ route('motorcycles.index') }}">{{ __('motorcycles.reset_all') }}</a> @endif
                    </p>

                    <form method="GET" action="{{ route('motorcycles.index') }}">
                        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                        <input type="hidden" name="sort" value="{{ $sortKey }}">

                        <fieldset class="fset">
                            <legend>{{ __('motorcycles.category') }}</legend>
                            <label class="fopt">
                                <input type="radio" name="category" value="" {{ ! request('category') ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>{{ __('motorcycles.all_categories') }}</span>
                            </label>
                            @foreach($categoryRows as $category)
                                <label class="fopt">
                                    <input type="radio" name="category" value="{{ $category->value }}" {{ request('category') === $category->value ? 'checked' : '' }} onchange="this.form.submit()">
                                    <span><i class="fa-solid {{ $category->icon }}" aria-hidden="true" style="color:#6fb6ff;margin-inline-end:6px;font-size:9px"></i>{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </fieldset>

                        <fieldset class="fset">
                            <legend>{{ __('motorcycles.engine_capacity_cc') }}</legend>
                            <div class="cc">
                                <input type="number" class="finput" name="min_cc" min="0" step="1" inputmode="numeric" value="{{ request('min_cc') }}" placeholder="{{ __('motorcycles.min') }}" aria-label="{{ __('motorcycles.min_cc_aria') }}">
                                <span aria-hidden="true">–</span>
                                <input type="number" class="finput" name="max_cc" min="0" step="1" inputmode="numeric" value="{{ request('max_cc') }}" placeholder="{{ __('motorcycles.max') }}" aria-label="{{ __('motorcycles.max_cc_aria') }}">
                            </div>
                        </fieldset>

                        <fieldset class="fset">
                            <legend>{{ __('motorcycles.brand') }}</legend>
                            @foreach(($brands ?? collect()) as $brand)
                                <label class="fopt">
                                    <input type="checkbox" name="brand[]" value="{{ $brand->id }}" {{ in_array($brand->id, $selectedBrands) ? 'checked' : '' }} onchange="this.form.submit()">
                                    <span>{{ $brand->name }}</span>
                                    <span class="cnt num-latin">{{ $brand->motorcycles_count }}</span>
                                </label>
                            @endforeach
                        </fieldset>

                        <div class="fbtns">
                            <button type="submit" class="btn"><i class="fa-solid fa-filter" aria-hidden="true"></i> {{ __('motorcycles.apply_filters') }}</button>
                            @if($hasFilters)
                                <a href="{{ route('motorcycles.index') }}" class="btn btn-outline">{{ __('motorcycles.clear_all_filters') }}</a>
                            @endif
                        </div>
                    </form>
                </div>
            </aside>

            {{-- ---------- Results ---------- --}}
            <div aria-label="{{ __('motorcycles.results_aria') }}">
                <form class="toolbar" method="GET" action="{{ route('motorcycles.index') }}">
                    @foreach($selectedBrands as $brandId) <input type="hidden" name="brand[]" value="{{ $brandId }}"> @endforeach
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    @if(request('min_cc'))   <input type="hidden" name="min_cc" value="{{ request('min_cc') }}"> @endif
                    @if(request('max_cc'))   <input type="hidden" name="max_cc" value="{{ request('max_cc') }}"> @endif
                    <input class="finput" type="search" name="search" value="{{ request('search') }}" placeholder="{{ __('motorcycles.search_placeholder') }}" aria-label="{{ __('motorcycles.search_aria') }}">
                    <select class="finput" name="sort" onchange="this.form.submit()" aria-label="{{ __('motorcycles.sort_aria') }}">
                        <option value="name" @selected($sortKey === 'name')>{{ __('motorcycles.sort_name_az') }}</option>
                        <option value="price_asc" @selected($sortKey === 'price_asc')>{{ __('motorcycles.sort_price_asc') }}</option>
                        <option value="price_desc" @selected($sortKey === 'price_desc')>{{ __('motorcycles.sort_price_desc') }}</option>
                        <option value="power" @selected($sortKey === 'power')>{{ __('motorcycles.sort_power') }}</option>
                    </select>
                    <button class="btn" type="submit"><i class="fa-solid fa-magnifying-glass"></i> {{ __('motorcycles.search') }}</button>
                    @if($motorcycles->isNotEmpty())
                        <div class="vtoggle" role="group" aria-label="{{ __('motorcycles.layout_aria') }}">
                            <button type="button" class="vbtn on" id="btnGrid" aria-label="{{ __('motorcycles.grid_view') }}" title="{{ __('motorcycles.grid_view') }}"><i class="fa-solid fa-border-all"></i></button>
                            <button type="button" class="vbtn" id="btnList" aria-label="{{ __('motorcycles.list_view') }}" title="{{ __('motorcycles.list_view') }}"><i class="fa-solid fa-list"></i></button>
                        </div>
                    @endif
                </form>

                {{-- Active filter chips --}}
                @if($hasFilters)
                    <div class="chips">
                        @if(request('search'))
                            <a class="chip-x" href="{{ $drop(['search']) }}"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> {{ \Illuminate\Support\Str::limit(request('search'), 24) }} <i class="fa-solid fa-xmark" aria-hidden="true"></i></a>
                        @endif
                        @if(request('category'))
                            <a class="chip-x" href="{{ $drop(['category']) }}">{{ __('motorcycles.category') }}: {{ $catName(request('category')) }} <i class="fa-solid fa-xmark" aria-hidden="true"></i></a>
                        @endif
                        @foreach($activeBrands as $brand)
                            <a class="chip-x" href="{{ $cleanUrl(['brand' => array_values(array_diff($selectedBrands, [$brand->id]))]) }}">{{ $brand->name }} <i class="fa-solid fa-xmark" aria-hidden="true"></i></a>
                        @endforeach
                        @if(request('min_cc') || request('max_cc'))
                            <a class="chip-x" href="{{ $drop(['min_cc', 'max_cc']) }}"><span class="num-latin">{{ request('min_cc') ?: '0' }}–{{ request('max_cc') ?: '∞' }}</span> cc <i class="fa-solid fa-xmark" aria-hidden="true"></i></a>
                        @endif
                        <a class="chip-clear" href="{{ route('motorcycles.index') }}">{{ __('motorcycles.clear_all') }}</a>
                    </div>
                @endif

                <p class="count" aria-live="polite">
                    @if($motorcycles->total() > 0)
                        {{ __('motorcycles.lp_results_sub') }} · {{ __('motorcycles.showing') }} <strong class="num-latin">{{ $motorcycles->firstItem() }}</strong>–<strong class="num-latin">{{ $motorcycles->lastItem() }}</strong> {{ __('motorcycles.of') }} <strong class="num-latin">{{ $motorcycles->total() }}</strong> {{ $motorcycles->total() === 1 ? __('motorcycles.result') : __('motorcycles.results') }}
                    @else
                        <strong class="num-latin">0</strong> {{ __('motorcycles.results') }}
                    @endif
                </p>

                @if($motorcycles->isEmpty())
                    <div class="empty">
                        <i class="fa-solid fa-motorcycle" aria-hidden="true"></i>
                        <h2>{{ $hasFilters ? __('motorcycles.empty_filtered_heading') : __('motorcycles.empty_heading') }}</h2>
                        <p>{{ $hasFilters ? __('motorcycles.empty_filtered_desc') : __('motorcycles.empty_desc') }}</p>
                        <div class="actions">
                            @if($hasFilters)
                                <a href="{{ route('motorcycles.index') }}" class="btn"><i class="fa-solid fa-arrows-rotate" aria-hidden="true"></i> {{ __('motorcycles.reset_filters') }}</a>
                                <a href="{{ route('brands.index') }}" class="btn btn-outline"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> {{ __('motorcycles.browse_brands') }}</a>
                            @else
                                <a href="{{ route('contact') }}" class="btn"><i class="fa-solid fa-headset" aria-hidden="true"></i> {{ __('motorcycles.talk_to_expert') }}</a>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bikes" id="bikes">
                        @foreach($motorcycles as $moto)
                            @php
                                $status      = (string) ($moto->availability_status ?: 'available');
                                $orderable   = $status === 'available';
                                $tone        = $statusTones[$status] ?? 'b-neutral';
                                $productUrl  = route('motorcycles.show', $moto->slug);
                                $brandName   = $moto->brand?->name ?? 'MOTOVEX';
                                $specs       = array_values(array_filter([
                                    $moto->engine_capacity ? number_format((float) $moto->engine_capacity) . ' cc' : null,
                                    $moto->horsepower ? $moto->horsepower . ' hp' : null,
                                    $moto->torque ? $moto->torque . ' Nm' : null,
                                    $moto->year ? (string) $moto->year : null,
                                ]));
                            @endphp
                            <article class="product">
                                <a class="product-image" href="{{ $productUrl }}" tabindex="-1" aria-label="{{ $brandName }} {{ $moto->model_name }}">
                                    @if($moto->image_url)
                                        <img src="{{ $moto->image_url }}" alt="{{ $brandName }} {{ $moto->model_name }}" loading="lazy" decoding="async">
                                    @else
                                        <span class="noimg"><i class="fa-solid fa-motorcycle" aria-hidden="true"></i><span>{{ __('motorcycles.image_coming_soon') }}</span></span>
                                    @endif
                                    <span class="badges">
                                        @if($moto->is_featured)<span class="b b-feat"><i class="fa-solid fa-star" aria-hidden="true"></i> {{ __('motorcycles.featured') }}</span>@endif
                                        <span class="b {{ $tone }}">{{ $statusLabel($status) }}</span>
                                    </span>
                                </a>
                                <div class="product-info">
                                    <div class="pm-body">
                                        <div class="brandline">{{ $brandName }}</div>
                                        <h3><a href="{{ $productUrl }}">{{ $moto->model_name }}</a></h3>
                                        @if($moto->short_description)
                                            <p class="desc">{{ $moto->short_description }}</p>
                                        @endif
                                        @if($specs)
                                            <ul class="speclist">@foreach($specs as $spec)<li class="num-latin">{{ $spec }}</li>@endforeach</ul>
                                        @endif
                                    </div>
                                    <div class="pm-side">
                                        <div class="price-row">
                                            <small style="font-size:9px;color:#8ea9c6">{{ __('motorcycles.starting_price') }}</small>
                                            @if($moto->price)
                                                <span class="price num-latin">{{ number_format((float) $moto->price) }} {{ $moto->currency ?: 'USD' }}</span>
                                            @else
                                                <span class="price-req">{{ __('motorcycles.price_on_request') }}</span>
                                            @endif
                                        </div>
                                        <div class="card-actions">
                                            @if($orderable)
                                                <button type="button" class="btn btn-outline order-trigger"
                                                        data-name="{{ $moto->model_name }}"
                                                        data-brand="{{ $brandName }}"
                                                        data-img="{{ $moto->image_url ?: '' }}"
                                                        data-price="{{ $moto->price ? ($moto->currency ?: 'USD') . ' ' . number_format((float) $moto->price) : __('motorcycles.price_on_request') }}"
                                                        data-specs="{{ implode('|', $specs) }}"
                                                        data-cart="{{ route('cart.add', $moto) }}">
                                                    <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i> {{ __('motorcycles.order') }}
                                                </button>
                                            @endif
                                            <a href="{{ $productUrl }}" class="btn">{{ __('motorcycles.view_details') }} <i class="fa-solid {{ $arrow }}" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if($motorcycles->hasPages())
                        <nav class="pager" aria-label="{{ __('motorcycles.pagination_aria') }}">
                            @if($motorcycles->onFirstPage())
                                <span class="pbtn dis" aria-disabled="true"><i class="fa-solid {{ $isRtl ? 'fa-arrow-right' : 'fa-arrow-left' }}" aria-hidden="true"></i></span>
                            @else
                                <a class="pbtn" href="{{ $motorcycles->previousPageUrl() }}" rel="prev" aria-label="{{ __('motorcycles.prev_page') }}"><i class="fa-solid {{ $isRtl ? 'fa-arrow-right' : 'fa-arrow-left' }}" aria-hidden="true"></i></a>
                            @endif

                            @foreach($motorcycles->getUrlRange(max(1, $motorcycles->currentPage() - 2), min($motorcycles->lastPage(), $motorcycles->currentPage() + 2)) as $page => $url)
                                @if($page == $motorcycles->currentPage())
                                    <span class="pbtn on" aria-current="page"><span class="num-latin">{{ $page }}</span></span>
                                @else
                                    <a class="pbtn" href="{{ $url }}"><span class="num-latin">{{ $page }}</span></a>
                                @endif
                            @endforeach

                            @if($motorcycles->hasMorePages())
                                <a class="pbtn" href="{{ $motorcycles->nextPageUrl() }}" rel="next" aria-label="{{ __('motorcycles.next_page') }}"><i class="fa-solid {{ $arrow }}" aria-hidden="true"></i></a>
                            @else
                                <span class="pbtn dis" aria-disabled="true"><i class="fa-solid {{ $arrow }}" aria-hidden="true"></i></span>
                            @endif
                        </nav>
                    @endif
                @endif
            </div>
        </div>
    </section>

    {{-- ═══════════════ 5. WHY MOTOVEX ═══════════════ --}}
    <section class="section container reveal">
        <div class="section-head"><div><h2>{{ __('motorcycles.lp_benefits_heading') }}</h2><p>{{ __('motorcycles.lp_benefits_sub') }}</p></div><a class="more" href="{{ route('services') }}">{{ __('navigation.services') }} <i class="fa-solid {{ $arrow }}"></i></a></div>
        <div class="service-grid four">
            @foreach([['fa-magnifying-glass-location', 1],['fa-shield-halved', 2],['fa-headset', 3],['fa-store', 4]] as [$icon, $i])
                <div class="service"><i class="fa-solid {{ $icon }}"></i><h3>{{ __('motorcycles.benefit_' . $i . '_title') }}</h3><p>{{ __('motorcycles.benefit_' . $i . '_sub') }}</p></div>
            @endforeach
        </div>
    </section>

    {{-- ═══════════════ 6. TEST RIDE PROMO ═══════════════ --}}
    <section class="container reveal"><div class="promo">
        <div class="promo-copy"><span class="eyebrow">{{ __('motorcycles.lp_promo_eyebrow') }}</span><h2>{{ __('motorcycles.lp_promo_title') }}</h2><p>{{ __('motorcycles.lp_promo_sub') }}</p><a href="{{ $testRideUrl }}" class="btn">{{ __('motorcycles.lp_promo_cta') }} <i class="fa-solid {{ $arrow }}"></i></a></div>
        <div class="discount">{{ __('motorcycles.lp_promo_badge') }} <strong><i class="fa-solid fa-motorcycle"></i></strong></div>
    </div></section>

    {{-- ═══════════════ 7. BRANDS ═══════════════ --}}
    @if(($brands ?? collect())->isNotEmpty())
    <section class="section container reveal" id="brands">
        <div class="section-head"><div><h2>{{ __('motorcycles.lp_brands_heading') }}</h2><p>{{ __('motorcycles.lp_brands_sub') }}</p></div><a class="more" href="{{ route('brands.index') }}">{{ __('navigation.our_brands') }} <i class="fa-solid {{ $arrow }}"></i></a></div>
        <div class="brand-grid">
            @foreach(($brands ?? collect())->take(8) as $brand)
                @php $logo = $resolveLogo($brand); @endphp
                <a class="brand-tile" href="{{ route('brands.show', $brand->slug) }}">
                    @if($logo)
                        <img src="{{ $logo }}" alt="{{ $brand->name }}" loading="lazy" onerror="this.style.display='none'">
                    @else
                        <span class="ph"><i class="fa-solid fa-motorcycle"></i></span>
                    @endif
                    <span>{{ $brand->name }}</span>
                    <small class="num-latin">{{ $brand->motorcycles_count }} {{ __('motorcycles.models') }}</small>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ═══════════════ 8. RIDER REVIEWS (static editorial strip) ═══════════════ --}}
    <section class="section container reveal">
        <div class="section-head"><div><h2>{{ __('motorcycles.lp_reviews_heading') }}</h2><p>{{ __('motorcycles.lp_reviews_sub') }}</p></div></div>
        <div class="reviews">
            @foreach([1, 2, 3] as $i)
                <article class="review"><div class="review-top"><img class="avatar" src="https://i.pravatar.cc/100?img={{ $i + 12 }}" alt="" loading="lazy"><div><h3>{{ __('motorcycles.lp_review_' . $i . '_name') }}</h3><small>{{ __('motorcycles.lp_review_role') }}</small></div><div class="rating">★★★★★</div></div><p>{{ __('motorcycles.lp_review_' . $i . '_comment') }}</p></article>
            @endforeach
        </div>
    </section>

    {{-- ═══════════════ 9. ARTICLES (static until an articles module exists) ═══════════════ --}}
    <section class="section container reveal">
        <div class="section-head"><div><h2>{{ __('motorcycles.lp_articles_heading') }}</h2><p>{{ __('motorcycles.lp_articles_sub') }}</p></div></div>
        <div class="articles">
            @foreach([
                ['title' => __('motorcycles.lp_article_1_title'), 'desc' => __('motorcycles.lp_article_1_desc'), 'image' => $img('vayu-r3-1000.jpg')],
                ['title' => __('motorcycles.lp_article_2_title'), 'desc' => __('motorcycles.lp_article_2_desc'), 'image' => $img('neo-sprint-400.jpg')],
                ['title' => __('motorcycles.lp_article_3_title'), 'desc' => __('motorcycles.lp_article_3_desc'), 'image' => $img('atlas-pioneer-650.jpg')],
                ['title' => __('motorcycles.lp_article_4_title'), 'desc' => __('motorcycles.lp_article_4_desc'), 'image' => $img('aurum-heritage-650.jpg')],
            ] as $article)
                <div class="article"><img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" loading="lazy"><div><h3>{{ $article['title'] }}</h3><p>{{ $article['desc'] }}</p></div></div>
            @endforeach
        </div>
    </section>

    {{-- ═══════════════ 10. ADVISOR CTA ═══════════════ --}}
    <section class="container reveal"><div class="cta">
        <div><h2>{{ __('motorcycles.lp_cta_heading') }}</h2><p>{{ __('motorcycles.lp_cta_desc') }}</p></div>
        <div class="actions" style="margin:0"><a href="{{ route('contact') }}" class="btn"><i class="fa-solid fa-headset"></i> {{ __('motorcycles.lp_cta_primary') }}</a><a href="{{ route('brands.index') }}" class="btn btn-outline"><i class="fa-solid fa-store"></i> {{ __('motorcycles.lp_cta_secondary') }}</a></div>
    </div></section>

    {{-- ═══════════════ 11. NEWSLETTER ═══════════════ --}}
    <section class="container reveal"><div class="newsletter">
        <div><h2>{{ __('footer.newsletter_heading') }}</h2><p>{{ __('footer.newsletter_text') }}</p></div>
        <form class="news-form" method="GET" action="{{ route('contact') }}">
            <input type="email" name="email" placeholder="{{ __('footer.newsletter_placeholder') }}" aria-label="{{ __('footer.newsletter_placeholder') }}" required>
            <input type="hidden" name="subject" value="{{ __('footer.newsletter_heading') }}">
            <input type="hidden" name="enquiry_type" value="general">
            <button class="btn" type="submit">{{ __('footer.newsletter_button') }} <i class="fa-solid fa-paper-plane"></i></button>
        </form>
    </div></section>
</main>

{{-- ═══════════════ FOOTER ═══════════════ --}}
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div><a class="brand footer-logo" href="{{ route('home') }}"><span class="brand-mark"><i class="fa-solid fa-motorcycle"></i></span><span>MOTOVEX<small>RIDE YOUR DREAM</small></span></a><p>{{ __('footer.tagline') }}</p><div class="socials"><a href="#" aria-label="{{ __('footer.facebook') }}"><i class="fa-brands fa-facebook-f"></i></a><a href="#" aria-label="{{ __('footer.instagram') }}"><i class="fa-brands fa-instagram"></i></a><a href="#" aria-label="{{ __('footer.youtube') }}"><i class="fa-brands fa-youtube"></i></a><a href="#" aria-label="{{ __('footer.twitter') }}"><i class="fa-brands fa-x-twitter"></i></a><a href="#" aria-label="{{ __('footer.whatsapp') }}"><i class="fa-brands fa-whatsapp"></i></a></div></div>
            <div><h4>{{ __('footer.explore') }}</h4><div class="footer-links"><a href="{{ route('home') }}">{{ __('navigation.home') }}</a><a href="{{ route('motorcycles.index') }}">{{ __('footer.all_motorcycles') }}</a><a href="{{ route('brands.index') }}">{{ __('footer.our_brands') }}</a><a href="{{ route('spare-parts.index') }}">{{ __('navigation.spare_parts') }}</a><a href="{{ route('promotions') }}">{{ __('navigation.promotions') }}</a></div></div>
            <div><h4>{{ __('footer.customer_support') }}</h4><div class="footer-links"><a href="{{ route('contact') }}">{{ __('navigation.contact') }}</a><a href="{{ route('services') }}">{{ __('navigation.services') }}</a><a href="{{ route('cart.index') }}">{{ __('navigation.cart') }}</a><a href="{{ $accountUrl }}">{{ __('footer.my_enquiries') }}</a></div></div>
            <div><h4>{{ __('footer.showroom_title') }}</h4><div class="footer-links"><span><i class="fa-solid fa-location-dot"></i> {{ __('footer.showroom_text') }}</span><a href="mailto:{{ $footerEmail }}"><i class="fa-regular fa-envelope"></i> <span class="num-latin">{{ $footerEmail }}</span></a><a href="{{ $telHref }}"><i class="fa-solid fa-phone"></i> <span class="num-latin">{{ $footerPhone }}</span></a><span><i class="fa-solid fa-award"></i> {{ __('footer.group_text') }}</span></div></div>
            <div><h4>{{ __('footer.newsletter_heading') }}</h4><p>{{ __('footer.newsletter_text') }}</p><a class="btn" style="width:100%;padding:8px;font-size:10px" href="{{ route('contact') }}">{{ __('motorcycles.talk_to_expert') }} <i class="fa-solid {{ $arrow }}"></i></a></div>
        </div>
        <div class="copyright"><span>{{ __('navigation.copyright', ['year' => date('Y')]) }}</span><span>{{ __('motorcycles.hero_tagline') }}</span></div>
    </div>
</footer>

{{-- ═══════════════ ORDER REVIEW MODAL ═══════════════ --}}
<div class="overlay" id="orderModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="mbox">
        <div class="mhead">
            <div><h3 id="modalTitle">{{ __('motorcycles.modal_title') }}</h3><small>{{ __('motorcycles.modal_subtitle') }}</small></div>
            <button type="button" class="mclose" id="modalClose" aria-label="{{ __('motorcycles.close') }}"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="mbody">
            <div class="mimgwrap" id="modalImg"><i class="fa-solid fa-motorcycle"></i></div>
            <div>
                <div class="brandline" id="modalBrand"></div>
                <h4 id="modalName"></h4>
                <ul class="mspecs" id="modalSpecs"></ul>
                <span class="b b-ok" style="display:inline-block;margin-top:9px">{{ __('motorcycles.in_stock') }}</span>
            </div>
            <div class="msum" style="grid-column:1/-1">
                <div class="row"><span>{{ __('motorcycles.quantity') }}</span><strong class="num-latin">1</strong></div>
                <div class="row"><span>{{ __('motorcycles.subtotal') }}</span><strong id="modalSub" class="num-latin"></strong></div>
                <div class="row tot"><span>{{ __('motorcycles.total') }}</span><span id="modalTotal" class="num-latin"></span></div>
            </div>
            <p class="mnote" style="grid-column:1/-1"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> {{ __('motorcycles.modal_note') }}</p>
        </div>
        <div class="mfoot">
            <form id="modalForm" method="POST" action="">@csrf
                <button type="submit" class="btn" id="modalConfirm"><i class="fa-solid fa-cart-shopping"></i> {{ __('motorcycles.confirm_add_to_cart') }} <i class="fa-solid {{ $arrow }}"></i></button>
            </form>
            <button type="button" class="btn btn-outline" id="modalCancel"><i class="fa-solid {{ $isRtl ? 'fa-arrow-right' : 'fa-arrow-left' }}"></i> {{ __('motorcycles.continue_shopping') }}</button>
        </div>
    </div>
</div>

<div class="toast" id="toast" role="status" aria-live="polite"></div>

<script>
    (function () {
        'use strict';

        /* ---------- Mobile nav ---------- */
        var menuToggle = document.getElementById('menuToggle');
        var navLinks   = document.getElementById('navLinks');
        if (menuToggle && navLinks) {
            menuToggle.addEventListener('click', function () {
                var open = navLinks.classList.toggle('open');
                menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
        }

        /* ---------- Toast (session flash messages) ---------- */
        var toast = document.getElementById('toast');
        var toastTimer = null;
        function showToast(message, tone) {
            if (!toast || !message) return;
            toast.textContent = message;
            toast.className = 'toast on' + (tone ? ' ' + tone : '');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(function () { toast.className = 'toast'; }, 3400);
        }
        @if(session('success')) showToast(@json(session('success')), 'ok'); @endif
        @if(session('error')) showToast(@json(session('error')), 'err'); @endif
        @if(session('info')) showToast(@json(session('info')), 'info'); @endif

        /* ---------- Grid / list layout ---------- */
        var bikes   = document.getElementById('bikes');
        var btnGrid = document.getElementById('btnGrid');
        var btnList = document.getElementById('btnList');
        function setView(mode) {
            if (!bikes) return;
            var list = mode === 'list';
            bikes.classList.toggle('list', list);
            if (btnGrid) btnGrid.classList.toggle('on', !list);
            if (btnList) btnList.classList.toggle('on', list);
        }
        if (btnGrid) btnGrid.addEventListener('click', function () { setView('grid'); try { localStorage.setItem('moto-view', 'grid'); } catch (e) {} });
        if (btnList) btnList.addEventListener('click', function () { setView('list'); try { localStorage.setItem('moto-view', 'list'); } catch (e) {} });
        try { if (localStorage.getItem('moto-view') === 'list') setView('list'); } catch (e) {}

        /* ---------- Mobile filter panel ---------- */
        var filterToggle = document.getElementById('filterToggle');
        var filterPanel  = document.getElementById('filterPanel');
        if (filterToggle && filterPanel) {
            filterToggle.addEventListener('click', function () {
                var open = filterPanel.classList.toggle('open');
                filterToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
        }

        /* ---------- Scroll reveal ---------- */
        if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            var revealObserver = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) { entry.target.classList.add('visible'); revealObserver.unobserve(entry.target); }
                });
            }, { threshold: .06, rootMargin: '0px 0px -40px 0px' });
            document.querySelectorAll('.reveal').forEach(function (el) { revealObserver.observe(el); });
        } else {
            document.querySelectorAll('.reveal').forEach(function (el) { el.classList.add('visible'); });
        }

        /* ---------- Order review modal ---------- */
        var modal    = document.getElementById('orderModal');
        var form     = document.getElementById('modalForm');
        var confirmB = document.getElementById('modalConfirm');
        function openModal(btn) {
            var d = btn.dataset;
            var imgWrap = document.getElementById('modalImg');
            imgWrap.innerHTML = d.img
                ? '<img src="' + d.img + '" alt="' + d.name + '" loading="lazy">'
                : '<i class="fa-solid fa-motorcycle"></i>';
            document.getElementById('modalBrand').textContent = d.brand;
            document.getElementById('modalName').textContent  = d.name;
            document.getElementById('modalSpecs').innerHTML   = (d.specs ? d.specs.split('|') : [])
                .filter(Boolean)
                .map(function (spec) { return '<li>' + spec + '</li>'; })
                .join('');
            document.getElementById('modalSub').textContent   = d.price;
            document.getElementById('modalTotal').textContent = d.price;
            form.action = d.cart;
            modal.classList.add('open');
            document.body.style.overflow = 'hidden';
            if (confirmB) confirmB.disabled = false;
        }
        function closeModal() {
            modal.classList.remove('open');
            document.body.style.overflow = '';
        }
        document.querySelectorAll('.order-trigger').forEach(function (btn) {
            btn.addEventListener('click', function () { openModal(btn); });
        });
        ['modalClose', 'modalCancel'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.addEventListener('click', closeModal);
        });
        if (modal) modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });
        if (form) form.addEventListener('submit', function () {
            if (confirmB) {
                confirmB.disabled = true;
                confirmB.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + @json(__('motorcycles.adding'));
            }
        });
    })();
</script>
</body>
</html>
