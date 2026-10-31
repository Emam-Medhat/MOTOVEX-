@extends('layouts.app')

@section('title', 'MOTOVEX | ' . __('home.hero_title'))

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
/* ============================================================
   MOTOVEX HOME — UNIFIED COLOR SYSTEM v3
   One palette, infinite depth. Every section lives inside the same
   dark-navy world — light sections use a soft midnight-slate, never
   stark white. Purple accent ties everything together.
   ============================================================ */
:root{
  /* ── Core surfaces (all in the same navy family) ── */
  --mx-bg:       #060C1A;   /* page base — deep midnight */
  --mx-navy:     #060C1A;
  --mx-panel:    #0C1829;   /* cards, dark sections */
  --mx-panel-b:  #0F1E34;   /* slightly lighter panel variant */
  --mx-tile-a:   #10213A;   /* card gradient start */
  --mx-tile-b:   #091525;   /* card gradient end */
  --mx-section-alt:#0E1B2E; /* "alt" dark sections — subtle contrast */
  --mx-soft:     #111E30;   /* replaces stark white sections */
  --mx-card:     #0F1D31;   /* cards inside light-toned sections */
  --mx-line:     #1A3A5C;   /* borders */
  --mx-card-line:#1E3D60;

  /* ── Brand accent — single purple system ── */
  --mx-purple:        #7C3AED;  /* primary CTA */
  --mx-purple-mid:    #8B5CF6;  /* hover / active */
  --mx-purple-deep:   #6D3BE0;  /* gradient start of CTAs, accent text on soft surfaces */
  --mx-purple-bright: #A78BFA;  /* icon tints, eyebrows */
  --mx-purple-glow:   rgba(124,58,237,.18);
  --mx-blue:          #38BDF8;  /* secondary accent — use sparingly */
  --mx-blue-soft:     rgba(56,189,248,.12);

  /* ── Text — all on dark surfaces ── */
  --mx-d1: #F0F4FF;   /* headings — soft warm white */
  --mx-d2: #B8C9E0;   /* body copy */
  --mx-d3: #7A96B4;   /* muted / supporting */
  --mx-d4: #3D5A7A;   /* faintest — borders, dividers text */

  /* ── Text — on mx-soft / mx-section-alt surfaces ── */
  --mx-s1: #E8EEFF;   /* heading on alt section */
  --mx-s2: #A8BDD6;   /* body on alt section */
  --mx-s3: #6A8BAA;   /* muted on alt section */

  /* ── Misc ── */
  --mx-amber:    #F59E0B;
  --mx-success:  #22C55E;
  --mx-shadow:   0 20px 60px rgba(0,0,0,.45);
  --mx-glow-purple: 0 0 40px rgba(124,58,237,.25);

  /* ── Typeface ──
     One static stack: Inter covers latin, Cairo covers arabic (Inter has no
     arabic glyphs, so the browser switches per script automatically).
     Typeface is never built with a Blade echo — echo escapes the quotes,
     which invalidates the custom property and silently drops every font. */
  --mx-font:'Inter','Cairo','Tajawal',system-ui,sans-serif;
}

/* ── LAYOUT ── */
html{scroll-behavior:smooth;scroll-padding-top:92px}
body{
  background:var(--mx-navy);
  color:var(--mx-d1);
  font-family:var(--mx-font);
  font-weight:400;
  line-height:1.65;
  -webkit-font-smoothing:antialiased;
  -moz-osx-font-smoothing:grayscale;
  text-rendering:optimizeLegibility;
}

/* ── TYPEFACE LOCK-IN ──
   This page owns its type. The shared stylesheet puts Orbitron on every
   h1/h2/h3, .font-display, .btn and .eyebrow — unlayered CSS like this block
   always beats app.css @layer base/components, so those are overridden here
   for the whole homepage, navbar and footer included.
   <i> is deliberately excluded so Font Awesome glyphs keep rendering. */
h1,h2,h3,h4,h5,h6,.font-display,.btn,.eyebrow,.slide-counter{text-transform:none;font-family:var(--mx-font)}
main,
main h1,main h2,main h3,main h4,main h5,main h6,
main p,main span,main a,main b,main strong,main li,main small,main em,main label,main td,main th,
main button,main input,main textarea,main select,main option{font-family:var(--mx-font)}
main h1,main h2,main h3,main h4,main h5,main h6{font-weight:800;letter-spacing:-.022em}
main{text-wrap:pretty}

/* Override global green ambient + selection to purple identity */
body:before{background:
 radial-gradient(58% 46% at 18% 0%,rgba(139,92,246,.14),transparent 70%),
 radial-gradient(50% 48% at 100% 100%,rgba(56,189,248,.08),transparent 70%)}
::selection{background:rgba(139,92,246,.45);color:#fff}
:focus-visible{outline:2px solid var(--mx-purple);outline-offset:3px;border-radius:2px}

a{text-decoration:none;color:inherit}
.wrap{width:min(1240px,calc(100% - 52px));margin-inline:auto}
.section{padding:clamp(68px,7.5vw,108px) 0}
.grid{display:grid;gap:18px}

/* ── SECTION HEADERS ── */
.head{display:flex;align-items:flex-end;justify-content:space-between;gap:28px;margin-bottom:38px}
.head>div{max-width:700px}
.head h2{margin:10px 0 9px}
.head p{margin:0}
.eyebrow{display:inline-flex;align-items:center;gap:9px}
.eyebrow:before{content:"";width:20px;height:2px;border-radius:4px;background:currentColor;opacity:.8}

/* ── HERO ── */
.hero{
  position:relative;isolation:isolate;overflow:hidden;
  display:flex;align-items:center;
  min-height:min(820px,calc(100svh - 72px));
  background:var(--mx-navy);
}
/* Cinematic full-bleed background image */
.hero-bg{
  position:absolute;inset:0;z-index:-2;
  background:url('/images/hero-bike.jpg') center 45%/cover;
  filter:brightness(.52) saturate(1.15);
  animation:heroDrift 26s cubic-bezier(.45,0,.55,1) infinite alternate;
  transform-origin:center center;
}
/* Dark gradient overlay — strong left, fades right to show bike */
.hero-overlay{
  position:absolute;inset:0;z-index:-1;
  background:
    linear-gradient(105deg,
      rgba(5,11,24,.98) 0%,
      rgba(5,11,24,.94) 22%,
      rgba(5,11,24,.80) 42%,
      rgba(5,11,24,.40) 65%,
      rgba(5,11,24,.15) 100%
    ),
    linear-gradient(0deg, rgba(5,11,24,.70) 0%, transparent 28%);
}
/* Subtle purple ambient glow on the right */
.hero-glow{
  position:absolute;inset:0;z-index:-1;
  background:radial-gradient(ellipse 55% 60% at 72% 50%,rgba(139,92,246,.12),transparent 65%);
  pointer-events:none;
}
@keyframes heroDrift{from{transform:scale(1) translate(0,0)}to{transform:scale(1.09) translate(-1%,.5%)}}

/* Two-column hero layout */
.hero-inner{
  display:grid;
  grid-template-columns:1fr 1fr;
  align-items:center;
  gap:clamp(32px,4vw,64px);
  width:100%;
}
.heroText{padding:clamp(72px,9vw,120px) 0;max-width:580px}
.hero h1{margin:16px 0 18px;text-wrap:balance}
.hero p{margin:0;max-width:500px}

/* Bike showcase — right column */
.hero-bike-col{
  position:relative;
  display:flex;align-items:center;justify-content:center;
  height:clamp(340px,45vw,580px);
}
.hero-bike-col img{
  position:absolute;
  inset:0;
  width:100%;height:100%;
  object-fit:cover;
  object-position:center center;
  border-radius:20px;
  box-shadow:0 30px 80px rgba(0,0,0,.55),0 0 0 1px rgba(139,92,246,.15);
}
/* Vignette on the bike image */
.hero-bike-col:after{
  content:"";
  position:absolute;inset:0;
  border-radius:20px;
  background:linear-gradient(
    to left,
    transparent 55%,
    rgba(5,11,24,.65) 100%
  ),linear-gradient(
    to top,
    rgba(5,11,24,.45) 0%,
    transparent 35%
  );
  pointer-events:none;
}
/* Floating spec pill */
.hero-spec-pill{
  position:absolute;
  bottom:22px;
  inset-inline-end:22px;
  z-index:2;
  display:flex;gap:18px;
  padding:14px 20px;
  background:rgba(5,11,24,.78);
  backdrop-filter:blur(16px);
  border:1px solid rgba(167,139,250,.25);
  border-radius:14px;
}
.hero-spec-pill>div{
  display:flex;flex-direction:column;align-items:center;gap:3px;
}
.hero-spec-pill b{font-size:18px;font-weight:800;color:#fff;line-height:1}
.hero-spec-pill span{font-size:9px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:rgba(203,213,225,.7)}
.hero-spec-sep{width:1px;background:rgba(255,255,255,.12);align-self:stretch}

.stats{
  display:grid;grid-template-columns:repeat(3,minmax(0,auto));
  gap:0 24px;max-width:none;
  margin-top:40px;padding-top:24px;
  border-top:1px solid rgba(29,76,120,.7);
}
.stats>div{min-width:0;padding-inline:20px;border-inline-start:1px solid rgba(29,64,96,.7)}
.stats>div:first-child{padding-inline-start:0;border-inline-start:0}
.stats b{display:block;font-size:clamp(22px,2.3vw,30px);line-height:1.15;font-weight:800}
.stats span{display:block;margin-top:6px;font-size:11.5px;line-height:1.45;white-space:nowrap}

/* ── BUTTONS ── */
.btn-legacy{display:inline-flex;align-items:center;justify-content:center;gap:10px;min-height:47px;padding:12px 22px;border:0;border-radius:10px;font:inherit;font-size:13px;cursor:pointer;transition:transform .28s ease,box-shadow .28s ease,background .28s ease,border-color .28s ease,color .28s ease}
.btn-legacy.outline{backdrop-filter:blur(10px)}

/* ── BRANDS ── */
.brands{grid-template-columns:repeat(8,1fr);gap:13px}
.brand{display:grid;place-items:center;min-height:78px;padding:16px 10px;border:1px solid var(--mx-line);border-radius:12px;background:linear-gradient(145deg,var(--mx-tile-a),var(--mx-tile-b));color:var(--mx-d1);font-size:14px;font-weight:800;letter-spacing:.4px;text-align:center;transition:transform .3s,box-shadow .3s,color .3s,border-color .3s,background .3s}
.brand:hover{transform:translateY(-4px)}

/* ── CATEGORY TILES ── */
.cats{grid-template-columns:repeat(4,1fr)}
.cat{position:relative;display:block;overflow:hidden;height:clamp(220px,24vw,300px);border-radius:16px;background:var(--mx-panel);color:#fff}
.cat img{width:100%;height:100%;object-fit:cover;transition:transform .7s}
.cat:hover img{transform:scale(1.1)}
.cat:after{content:"";position:absolute;inset:0;background:linear-gradient(0deg,rgba(5,11,24,.94) 0%,rgba(5,11,24,.52) 42%,transparent 80%)}
.cat>div{position:absolute;z-index:1;bottom:20px;inset-inline:20px}
.cat h3{margin:0;line-height:1.3;color:#fff}
.cat p{margin:6px 0 0;max-width:240px;line-height:1.6;color:#DCE4F0}

/* ── PRODUCT / PART CARDS ── */
.products{grid-template-columns:repeat(4,1fr)}
.product{display:block;overflow:hidden;border:1px solid var(--mx-line);border-radius:16px;background:linear-gradient(155deg,var(--mx-tile-a) 0%,var(--mx-tile-b) 72%);transition:transform .35s,box-shadow .35s,border-color .35s,background .35s}
.product img{display:block;width:100%;height:clamp(165px,18vw,215px);object-fit:cover;background:var(--mx-tile-b);transition:transform .5s}
.product:hover{transform:translateY(-5px);border-color:rgba(139,92,246,.5);box-shadow:0 18px 38px rgba(0,0,0,.4)}
.product:hover img{transform:scale(1.05)}
.productInfo{padding:18px 18px 20px}
.product h3{margin:8px 0 5px;line-height:1.35}
.product p{margin:0 0 9px}
.price{display:inline-block;font-weight:800}

/* ── PROMO BAND ── */
.promo{position:relative;overflow:hidden;display:flex;align-items:center;min-height:340px;padding:clamp(28px,5vw,62px);border-radius:20px;color:#fff;background:linear-gradient(90deg,rgba(109,59,224,.94),rgba(109,59,224,.6)),url('https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=1800&q=85') center/cover;box-shadow:var(--mx-shadow)}
.promo:after{content:"";position:absolute;inset:0;pointer-events:none;background:linear-gradient(90deg,rgba(5,11,24,.42),transparent 76%)}
.promo>div{position:relative;z-index:1;max-width:610px}
.promo h2{margin:10px 0;font-size:clamp(26px,3.6vw,42px);font-weight:800;line-height:1.22;letter-spacing:-.022em;color:#fff;text-wrap:balance}
.promo p{margin:10px 0 20px;max-width:490px;line-height:1.85}

/* ── SERVICE CARDS ── */
.services{grid-template-columns:repeat(4,1fr)}
.service{display:block;min-height:190px;padding:24px 22px;border:1px solid var(--mx-line);border-radius:16px;background:linear-gradient(145deg,var(--mx-tile-a),var(--mx-tile-b));transition:transform .3s,box-shadow .3s,border-color .3s,background .3s}
.service:hover{transform:translateY(-5px);border-color:rgba(139,92,246,.55);box-shadow:0 15px 32px rgba(0,0,0,.32)}
.ico-wrap{width:48px;height:48px;margin-bottom:18px;border-radius:13px;display:grid;place-items:center;font-size:19px;color:var(--mx-purple-bright);background:linear-gradient(135deg,rgba(139,92,246,.24),rgba(56,189,248,.16))}
.service h3{margin:0 0 7px}
.service p{margin:0}

/* ── ABOUT ── */
.about{display:grid;grid-template-columns:1fr 1fr;gap:clamp(28px,5vw,72px);align-items:center}
.about>div{max-width:560px}
.about h2{font-size:clamp(28px,3.6vw,44px);font-weight:800;line-height:1.25;letter-spacing:-.022em;text-wrap:balance}
.about img{width:100%;height:clamp(300px,37vw,460px);object-fit:cover;border-radius:18px;box-shadow:var(--mx-shadow)}
.metrics{display:grid;grid-template-columns:repeat(4,1fr);gap:15px;margin-top:48px;padding:clamp(22px,3vw,38px);border:1px solid #1d3f65;border-radius:18px;background:linear-gradient(110deg,#0d1e36,var(--mx-panel))}
.metrics>div{padding-inline:12px;border-inline-start:1px solid #1d3f65}
.metrics>div:first-child{border-inline-start:0}
.metrics b{display:block;font-size:clamp(23px,2.8vw,34px);line-height:1.2;font-weight:800;color:var(--mx-d1)}
.metrics span{display:block;margin-top:7px;font-size:12px;color:var(--mx-d3)}

/* ── REVIEWS ── */
.reviews{grid-template-columns:repeat(3,1fr)}
.review{padding:25px;border:1px solid var(--mx-line);border-radius:16px;background:linear-gradient(145deg,var(--mx-tile-a),var(--mx-tile-b));transition:transform .3s,box-shadow .3s,border-color .3s,background .3s}
.review:hover{transform:translateY(-4px);box-shadow:0 14px 30px rgba(0,0,0,.32)}
.stars{margin-bottom:12px;font-size:14px;letter-spacing:2px;color:var(--mx-amber)}
.review b{display:block;margin-top:12px;font-weight:700}

/* ── FAQ ── */
.faq{max-width:900px;margin-inline:auto}
.faqItem{padding:21px 0;border-bottom:1px solid #15385d}
.faqQ{width:100%;display:flex;align-items:center;justify-content:space-between;gap:20px;background:none;border:0;text-align:inherit;font:inherit;cursor:pointer}
.faqQ i{flex:0 0 28px;width:28px;height:28px;border-radius:50%;display:grid;place-items:center;font-size:12px;background:rgba(139,92,246,.14)}
.faqA{max-height:0;overflow:hidden;transition:max-height .35s ease}
.faqItem.open .faqA{max-height:220px;padding-top:12px}

/* ── MARQUEE ── */
.marquee{overflow:hidden;padding:15px 0;white-space:nowrap;background:linear-gradient(100deg,#5E36D8,#885CFF 50%,#6037DB);color:#fff}
.marquee-track{display:inline-flex;gap:45px;font-size:13px;font-weight:800;letter-spacing:.06em;animation:marquee 30s linear infinite}
.marquee:hover .marquee-track{animation-play-state:paused}
@keyframes marquee{to{transform:translateX(-50%)}}

/* ── SPLIT FEATURE ── */
.split-feature{display:grid;grid-template-columns:1fr 1fr;gap:clamp(28px,5vw,70px);align-items:center}
.feature-photo{position:relative;min-height:clamp(320px,40vw,480px);border-radius:18px;overflow:hidden;box-shadow:var(--mx-shadow)}
.feature-photo img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.feature-photo:after{content:"";position:absolute;inset:0;background:linear-gradient(0deg,rgba(5,11,24,.72),transparent 50%)}
.floating-note{position:absolute;z-index:2;bottom:22px;inset-inline-start:22px;padding:16px 20px;border:1px solid rgba(255,255,255,.18);border-radius:12px;background:rgba(5,11,24,.78);backdrop-filter:blur(14px);color:var(--mx-d1)}
.floating-note b{display:block;font-size:27px;line-height:1.2;font-weight:800;color:var(--mx-d1)}
.floating-note span{font-size:12px;color:var(--mx-d2)}
.feature-copy{max-width:560px}
.feature-copy h2{font-size:clamp(27px,3.4vw,42px);font-weight:800;line-height:1.3;letter-spacing:-.022em;text-wrap:balance}
.checkline{display:flex;align-items:flex-start;gap:12px;margin:14px 0}
.checkline i{margin-top:4px}

/* ── TIMELINE ── */
.timeline{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;counter-reset:steps}
.step{padding:23px;border:1px solid var(--mx-line);border-radius:16px;background:linear-gradient(145deg,var(--mx-tile-a),var(--mx-tile-b));counter-increment:steps;transition:transform .3s,box-shadow .3s,border-color .3s}
.step:hover{transform:translateY(-4px);border-color:rgba(139,92,246,.5);box-shadow:0 14px 30px rgba(0,0,0,.3)}
.step:before{content:"0" counter(steps);display:block;font-size:29px;font-weight:800;line-height:1;color:var(--mx-purple-bright)}
.step h3{margin:12px 0 6px}
.step p{margin:0}

/* ── BENEFITS ── */
.benefit-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:13px}
.benefit{display:flex;align-items:flex-start;gap:13px;padding:18px;border:1px solid var(--mx-line);border-radius:14px;background:linear-gradient(145deg,var(--mx-tile-a),var(--mx-tile-b));transition:transform .3s,border-color .3s}
.benefit:hover{transform:translateY(-4px);border-color:rgba(139,92,246,.5)}
.benefit .ico-wrap{flex:0 0 48px;margin:0}
.benefit h3{margin:2px 0 0}
.benefit p{margin:5px 0 0}

/* ── GALLERY ── */
.gallery{display:grid;grid-template-columns:1.3fr 1fr 1fr;grid-template-rows:clamp(150px,17vw,210px) clamp(150px,17vw,210px);gap:12px}
.gallery a{position:relative;display:block;overflow:hidden;border-radius:14px}
.gallery a:first-child{grid-row:span 2}
.gallery img{width:100%;height:100%;object-fit:cover;transition:transform .6s}
.gallery a:hover img{transform:scale(1.1)}
.gallery a:after{content:"";position:absolute;inset:42% 0 0;background:linear-gradient(transparent,rgba(5,11,24,.82))}
.gallery span{position:absolute;z-index:2;bottom:16px;inset-inline-start:18px;font-size:13px;font-weight:700;color:#fff}

/* ── STORY CARDS ── */
.brand-story{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
.story-card{overflow:hidden;border:1px solid var(--mx-line);border-radius:16px;background:linear-gradient(145deg,var(--mx-tile-a),var(--mx-tile-b));transition:transform .3s,box-shadow .3s,border-color .3s,background .3s}
.story-card:hover{transform:translateY(-5px);box-shadow:0 15px 35px rgba(0,0,0,.34)}
.story-card img{display:block;width:100%;height:clamp(145px,17vw,195px);object-fit:cover}
.story-card>div{padding:19px}

/* ── VIDEO COVER ── */
.video-cover{position:relative;display:grid;place-items:center;min-height:clamp(270px,32vw,390px);border-radius:18px;overflow:hidden;text-align:center;background:linear-gradient(rgba(5,11,24,.4),rgba(5,11,24,.8)),url('https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1800&q=90') center/cover}
.video-cover:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at center,rgba(139,92,246,.22),transparent 55%)}
.video-cover>div{position:relative;z-index:1;padding:25px}
.video-cover h2{margin:23px 0 7px;font-size:clamp(25px,3.5vw,40px);font-weight:800;line-height:1.3}
.play-circle{width:68px;height:68px;margin-inline:auto;display:grid;place-items:center;border:1px solid rgba(167,139,250,.7);border-radius:50%;background:var(--mx-purple);font-size:22px;color:#fff;box-shadow:0 0 0 13px rgba(139,92,246,.16);animation:pulse 2s infinite;transition:transform .3s}
.video-cover:hover .play-circle{transform:scale(1.08)}
@keyframes pulse{50%{box-shadow:0 0 0 26px rgba(139,92,246,.05)}}

/* ── CONTACT ── */
.contact-panel{display:grid;grid-template-columns:1fr 1fr;gap:17px}
.contact-card{padding:clamp(20px,3vw,30px);border:1px solid var(--mx-line);border-radius:16px;background:linear-gradient(145deg,var(--mx-tile-a),var(--mx-tile-b))}
.contact-card h3{margin:0 0 18px;font-size:17px;font-weight:700;color:var(--mx-d1)}
.contact-card input,.contact-card textarea,.contact-card select{width:100%;margin:7px 0 13px;padding:12px 14px;border:1.5px solid #1d3a5e;border-radius:10px;background:#071326;font:inherit;font-size:13px;color:#DCEAFF;transition:border-color .2s,box-shadow .2s}
.contact-card input::placeholder,.contact-card textarea::placeholder{color:#5F7D9C}
.contact-card textarea{min-height:110px;resize:vertical}
.contact-details{display:grid;gap:14px}
.contact-line{display:flex;align-items:center;gap:13px;padding:14px;border:1px solid #1d3a5e;border-radius:12px;background:#071326;font-size:13px;color:#DCEAFF}
.contact-line i{font-size:18px;color:var(--mx-purple-bright)}

/* ── APP BANNER ── */
.app-banner{display:flex;align-items:center;justify-content:space-between;gap:25px;padding:clamp(24px,4vw,42px);border:1px solid #1d3f65;border-radius:18px;background:linear-gradient(105deg,#0d1e36,var(--mx-panel))}
.app-banner h2{margin:6px 0;font-size:clamp(22px,3vw,32px);font-weight:800;line-height:1.35;letter-spacing:-.015em}
.app-banner p{max-width:560px;font-size:14px;line-height:1.9}
.app-badges{display:flex;gap:10px;flex-wrap:wrap}
.app-badges a{display:inline-flex;align-items:center;gap:9px;padding:12px 17px;border:1px solid rgba(167,139,250,.5);border-radius:10px;font-size:13px;font-weight:700;transition:background .25s,color .25s,border-color .25s}

/* ── SPARE PARTS STRIP ── */
.tag-legacy{display:inline-block;padding:6px 12px;border-radius:30px;font-size:11px;font-weight:800;letter-spacing:.3px;background:rgba(139,92,246,.22);color:var(--mx-purple-bright)}
.category-strip{display:flex;gap:10px;overflow:auto;padding:5px 0 15px}
.category-strip span{white-space:nowrap;padding:10px 17px;border:1px solid var(--mx-line);border-radius:30px;background:var(--mx-tile-b);font-size:13px;font-weight:700;color:var(--mx-d2);transition:background .2s,color .2s,border-color .2s}
.category-strip span:hover{background:var(--mx-purple);border-color:var(--mx-purple);color:#fff}
.season-card{display:flex;align-items:center;justify-content:space-between;gap:20px;padding:27px;border:1px solid #1d3f65;border-radius:16px;background:linear-gradient(110deg,#0d1e36,var(--mx-panel))}
.season-card h3{margin:0 0 8px;font-size:21px;font-weight:800;color:var(--mx-d1)}
.season-card p{margin:0;font-size:14px;line-height:1.8;color:var(--mx-d3)}

/* ── NEWSLETTER ── */
.newsletter{display:flex;align-items:center;justify-content:space-between;gap:25px;padding:clamp(24px,4vw,42px);border:1px solid #1d3f65;border-radius:18px;background:linear-gradient(110deg,#0d1e36,var(--mx-panel))}
.newsletter h2{margin:6px 0;font-size:clamp(20px,3vw,30px);font-weight:800;line-height:1.35;letter-spacing:-.015em;color:var(--mx-d1)}
.newsletter p{max-width:560px;font-size:14px;line-height:1.9;color:var(--mx-d2)}
.newsletter form{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.newsletter input{min-width:260px;min-height:47px;padding:14px 18px;border:1px solid #1d3a5e;border-radius:10px;background:#071326;font:inherit;font-size:13px;color:#DCEAFF;transition:border-color .2s,box-shadow .2s}
.newsletter input::placeholder{color:#5F7D9C}
.newsletter input:focus,.contact-card input:focus,.contact-card textarea:focus,.contact-card select:focus{outline:0;border-color:var(--mx-purple);box-shadow:0 0 0 3px rgba(139,92,246,.2)}

/* ── SCROLL PROGRESS ── */
.progress-bar-top{position:fixed;z-index:100;top:0;inset-inline-start:0;width:0;height:3px;background:linear-gradient(90deg,var(--mx-purple),var(--mx-blue));box-shadow:0 0 10px rgba(139,92,246,.6)}

/* ── REVEAL ── */
.reveal{opacity:0;transform:translate3d(0,26px,0);filter:blur(2px);transition:opacity .7s ease,transform .7s cubic-bezier(.2,.75,.25,1),filter .7s ease;will-change:opacity,transform}
.reveal.show{opacity:1;transform:none;filter:blur(0)}
.delay{transition-delay:.14s}
.grid>.reveal:nth-child(2),.reviews>.reveal:nth-child(2),.brand-story>.reveal:nth-child(2),.timeline>.reveal:nth-child(2),.benefit-grid>.reveal:nth-child(2){transition-delay:.08s}
.grid>.reveal:nth-child(3),.reviews>.reveal:nth-child(3),.brand-story>.reveal:nth-child(3),.timeline>.reveal:nth-child(3),.benefit-grid>.reveal:nth-child(3){transition-delay:.16s}
.grid>.reveal:nth-child(4),.timeline>.reveal:nth-child(4),.benefit-grid>.reveal:nth-child(4){transition-delay:.24s}
.grid>.reveal:nth-child(5){transition-delay:.32s}

/* ── TYPOGRAPHY SCALE ──
   One ramp, em-based tracking: display 800 / heading 700-800 /
   body 400 at 13.5-16.5px / label 700 with positive tracking. */
.head h2{font-size:clamp(25px,2.9vw,38px);font-weight:800;letter-spacing:-.022em;line-height:1.2;color:var(--mx-d1)}
.head p{font-size:15.5px;font-weight:400;line-height:1.75;color:var(--mx-d2);max-width:560px}
.eyebrow{font-family:var(--mx-font);font-size:11.5px;font-weight:700;letter-spacing:.14em;text-transform:none;color:var(--mx-purple-bright)}
.productInfo .eyebrow:before{display:none}
.productInfo .eyebrow{gap:0}
.gradient{background:linear-gradient(92deg,var(--mx-purple-bright),var(--mx-purple) 55%,var(--mx-blue));-webkit-background-clip:text;background-clip:text;color:transparent}
.purple{font-size:14px;font-weight:700;letter-spacing:0;color:var(--mx-purple-bright)}
.purple:hover{color:var(--mx-blue)}
.hero h1{font-size:clamp(37px,4.9vw,62px);font-weight:800;letter-spacing:-.028em;line-height:1.12;color:var(--mx-d1)}
.hero p{font-size:16.5px;font-weight:400;line-height:1.8;color:var(--mx-d2)}
.stats b{font-weight:800;letter-spacing:-.02em;color:var(--mx-d1)}
.stats span{font-size:11.5px;font-weight:500;letter-spacing:.01em;color:var(--mx-d3)}
.btn-legacy{font-size:13.5px;font-weight:600;letter-spacing:.005em;background:linear-gradient(120deg,var(--mx-purple-deep),var(--mx-purple));color:#fff;box-shadow:0 10px 26px rgba(109,59,224,.34)}
.btn-legacy:hover{box-shadow:0 16px 34px rgba(109,59,224,.44)}
.btn-legacy.outline{background:rgba(139,92,246,.08);border:1px solid rgba(167,139,250,.55);color:var(--mx-d1)}
.brand{font-size:13.5px;letter-spacing:.04em}
.cat h3{font-size:clamp(16px,1.5vw,20px);font-weight:800;letter-spacing:-.015em}
.cat p{font-size:clamp(12px,1vw,13.5px);font-weight:400;line-height:1.6}
.product h3{font-size:clamp(14px,1.15vw,17px);font-weight:700;letter-spacing:-.01em;line-height:1.4;color:var(--mx-d1)}
.product p{font-size:13px;font-weight:400;line-height:1.6;color:var(--mx-d3)}
.price{font-size:clamp(14px,1.1vw,16px);font-weight:800;letter-spacing:-.005em;color:var(--mx-purple-bright)}
.service h3{font-size:clamp(15px,1.2vw,17.5px);font-weight:700;letter-spacing:-.01em;color:var(--mx-d1)}
.service p{font-size:clamp(12.5px,1vw,14px);font-weight:400;line-height:1.75;color:var(--mx-d3)}
.step h3{font-size:clamp(14px,1.15vw,17px);font-weight:700;letter-spacing:-.01em;color:var(--mx-d1)}
.step p{font-size:13.5px;font-weight:400;line-height:1.7;color:var(--mx-d3)}
.benefit h3{font-size:clamp(14px,1.15vw,16.5px);font-weight:700;letter-spacing:-.01em;color:var(--mx-d1)}
.benefit p{font-size:13.5px;font-weight:400;line-height:1.7;color:var(--mx-d3)}
.review p{font-size:14.5px;font-weight:400;line-height:1.8;color:var(--mx-d2)}
.review b{font-size:13.5px;font-weight:700;letter-spacing:.01em;color:var(--mx-d1)}
.story-card h3{font-size:clamp(15px,1.2vw,18px);font-weight:700;letter-spacing:-.012em;line-height:1.42;color:var(--mx-d1)}
.story-card p{font-size:13.5px;font-weight:400;line-height:1.75;color:var(--mx-d3)}
.faqQ{font-size:clamp(15px,1.25vw,17px);font-weight:700;letter-spacing:-.008em;color:var(--mx-d1)}
.faqA{font-size:14.5px;font-weight:400;line-height:1.9;color:var(--mx-d3)}
.about h2,.feature-copy h2,.promo h2,.video-cover h2,.newsletter h2,.app-banner h2{color:var(--mx-d1)}
.about p{font-size:16px;font-weight:400;line-height:1.95;color:var(--mx-d2)}
.feature-copy p{font-size:15px;font-weight:400;line-height:1.9;color:var(--mx-d2)}
.checkline{font-size:14px;font-weight:500;line-height:1.7;color:var(--mx-d2)}
.checkline i{color:var(--mx-purple-bright)}
.video-cover p{font-size:14.5px;font-weight:400;color:var(--mx-d2)}
.app-banner p,.newsletter p{font-size:14px;font-weight:400;color:var(--mx-d2)}
.app-badges a{background:rgba(139,92,246,.12);border-color:rgba(167,139,250,.5);color:var(--mx-d1);font-weight:600}
.app-badges a:hover{background:var(--mx-purple);border-color:var(--mx-purple);color:#fff}
.metrics b{letter-spacing:-.02em}
.metrics span{font-weight:500;letter-spacing:.01em}
.tag-legacy{font-weight:700;letter-spacing:.03em}
.category-strip span{font-weight:600}
.gallery span{font-weight:700;letter-spacing:-.005em}
.floating-note b{letter-spacing:-.02em}
.floating-note span{font-size:12px;font-weight:500;letter-spacing:.01em}
.contact-card input,.contact-card textarea,.contact-card select,.newsletter input{font-size:13.5px}
.contact-line,.contact-card p{font-size:13.5px}

/* ── ARABIC TYPOGRAPHY ──
   Cairo carries the script (Inter has no arabic glyphs, so the browser
   switches per glyph). The shared RTL partial asks for 'Noto Kufi Arabic',
   which is never web-loaded — it silently falls back to Segoe/Tahoma, so this
   page out-ranks it. Arabic letters join into words, therefore every tracking
   value tuned for latin goes back to zero and lines need more air. */
html[dir="rtl"] body,
html[dir="rtl"] main,
html[dir="rtl"] main h1,html[dir="rtl"] main h2,html[dir="rtl"] main h3,html[dir="rtl"] main h4,
html[dir="rtl"] main h5,html[dir="rtl"] main h6,
html[dir="rtl"] main p,html[dir="rtl"] main span,html[dir="rtl"] main a,html[dir="rtl"] main b,
html[dir="rtl"] main strong,html[dir="rtl"] main li,html[dir="rtl"] main small,html[dir="rtl"] main label,
html[dir="rtl"] main td,html[dir="rtl"] main th,
html[dir="rtl"] main button,html[dir="rtl"] main input,html[dir="rtl"] main textarea,html[dir="rtl"] main select{font-family:var(--mx-font)}
html[dir="rtl"] main{line-height:1.9}
html[dir="rtl"] main h1,html[dir="rtl"] main h2,html[dir="rtl"] main h3,html[dir="rtl"] main h4,
html[dir="rtl"] main p,html[dir="rtl"] main span,html[dir="rtl"] main a,html[dir="rtl"] main b,
html[dir="rtl"] main strong,html[dir="rtl"] main li,html[dir="rtl"] main label,
html[dir="rtl"] main .eyebrow,html[dir="rtl"] main .price,html[dir="rtl"] main .tag-legacy,
html[dir="rtl"] main .brand,html[dir="rtl"] main .faqQ,html[dir="rtl"] main .faqA,
html[dir="rtl"] main .stats b,html[dir="rtl"] main .metrics b,
html[dir="rtl"] main .btn-legacy,html[dir="rtl"] main .gradient{letter-spacing:normal;text-transform:none}
html[dir="rtl"] main h1,html[dir="rtl"] main h2,html[dir="rtl"] main h3,html[dir="rtl"] main h4{line-height:1.5}
html[dir="rtl"] .hero h1{font-size:clamp(31px,4.2vw,50px);line-height:1.35}
html[dir="rtl"] .hero p{font-size:16px;line-height:1.95}
html[dir="rtl"] .head h2{font-size:clamp(23px,2.6vw,33px)}
html[dir="rtl"] .head p{font-size:15px;line-height:1.85}
html[dir="rtl"] .stats span{font-size:12px;line-height:1.5}
html[dir="rtl"] .product h3,html[dir="rtl"] .service h3,html[dir="rtl"] .step h3,
html[dir="rtl"] .benefit h3,html[dir="rtl"] .story-card h3,html[dir="rtl"] .cat h3{line-height:1.55}

/* ── SOFT / ALT SECTIONS ─
   These sections sit one step lighter than the page base (--mx-soft) and use
   the v3 slate text ramp (--mx-s1 / --mx-s2 / --mx-s3). Cards stay dark so
   the dark and light worlds never mix. */
.mx-light{background:var(--mx-soft);color:var(--mx-s1)}
.mx-light .head h2{color:var(--mx-s1)}
.mx-light .head p{color:var(--mx-s2)}
.mx-light .eyebrow,.mx-light .purple{color:var(--mx-purple-bright)}
.mx-light .purple:hover{color:var(--mx-blue)}
.mx-light .brand,
.mx-light .product,
.mx-light .service,
.mx-light .review,
.mx-light .story-card,
.mx-light .season-card{background:var(--mx-card);border-color:var(--mx-card-line);color:var(--mx-s1);box-shadow:0 4px 18px rgba(0,0,0,.3)}
.mx-light .brand:hover,
.mx-light .product:hover,
.mx-light .service:hover,
.mx-light .review:hover,
.mx-light .story-card:hover{border-color:rgba(139,92,246,.45);box-shadow:0 16px 34px rgba(139,92,246,.14)}
.mx-light .brand{color:var(--mx-s1)}
.mx-light .brand:hover{color:var(--mx-purple-bright)}
.mx-light .product img,.mx-light .story-card img{background:var(--mx-tile-b)}
.mx-light .product h3,
.mx-light .service h3,
.mx-light .story-card h3,
.mx-light .review b,
.mx-light .season-card h3{color:var(--mx-s1)}
.mx-light .product p,
.mx-light .service p,
.mx-light .story-card p,
.mx-light .review p,
.mx-light .season-card p{color:var(--mx-s2)}
.mx-light .price{color:var(--mx-purple-bright)}
.mx-light .ico-wrap{background:linear-gradient(135deg,rgba(139,92,246,.2),rgba(56,189,248,.12));color:var(--mx-purple-bright)}
.mx-light .tag-legacy{background:rgba(139,92,246,.16);color:var(--mx-purple-bright)}
.mx-light .category-strip span{background:var(--mx-tile-b);border-color:var(--mx-line);color:var(--mx-s2)}
.mx-light .category-strip span:hover{background:var(--mx-purple);border-color:var(--mx-purple);color:#fff}
.mx-light .faqItem{border-bottom-color:var(--mx-card-line)}
.mx-light .faqQ{color:var(--mx-s1)}
.mx-light .faqQ i{background:rgba(139,92,246,.14);color:var(--mx-purple-bright)}
.mx-light .faqA{color:var(--mx-s2)}

/* ── RESPONSIVE LAYOUT ── */
@media(min-width:951px){
  .heroText{margin-inline-start:clamp(0px,2vw,28px)}
}
@media(max-width:950px){
  .wrap{width:min(100% - 36px,760px)}
  .hero{min-height:580px}
  .hero-inner{grid-template-columns:1fr}
  .hero-bike-col{display:none} /* hide right column, bg image does the job */
  .hero-bg{filter:brightness(.42) saturate(1.15)}
  .hero-overlay{background:
    linear-gradient(180deg,rgba(5,11,24,.92) 0%,rgba(5,11,24,.75) 60%,rgba(5,11,24,.92) 100%),
    linear-gradient(90deg,rgba(5,11,24,.85) 0%,rgba(5,11,24,.30) 100%)}
  .head{flex-direction:column;align-items:flex-start;gap:16px}
  .brands{grid-template-columns:repeat(4,1fr)}
  .cats,.products,.services,.timeline{grid-template-columns:repeat(2,1fr)}
  .benefit-grid,.brand-story{grid-template-columns:repeat(2,1fr)}
  .about,.split-feature,.contact-panel{grid-template-columns:1fr}
  .about img{height:350px}
  .feature-photo{min-height:340px}
}
@media(max-width:600px){
  .section{padding:56px 0}
  .wrap{width:calc(100% - 30px)}
  .grid,.cats,.products,.services,.brands,.reviews,.timeline,.benefit-grid,.brand-story{gap:10px}
  .hero{min-height:600px}
  .heroText{padding:72px 0 60px}
  .hero h1{letter-spacing:-.02em;line-height:1.18}
  .stats{margin-top:32px;padding-top:18px;gap:0 12px}
  .stats>div{min-width:0;padding-inline:10px}
  .stats span{white-space:normal}
  .head{margin-bottom:24px}
  .brands{grid-template-columns:repeat(4,1fr)}
  .brand{min-height:62px;padding:12px 6px}
  .cats,.products,.services,.timeline{grid-template-columns:repeat(2,minmax(0,1fr))}
  .reviews,.benefit-grid,.brand-story{grid-template-columns:1fr}
  .cat{height:200px}
  .cat>div{inset-inline:14px;bottom:15px}
  .product img{height:148px}
  .productInfo,.service{padding:15px}
  .service{min-height:0;padding:18px 15px}
  .ico-wrap{width:42px;height:42px;margin-bottom:14px;font-size:17px}
  .promo{min-height:310px;padding:25px}
  .metrics{grid-template-columns:repeat(2,1fr);gap:14px;padding:20px;margin-top:32px}
  .step{padding:18px}
  .benefit{padding:15px}
  .gallery{grid-template-columns:1fr 1fr;grid-template-rows:165px 125px 125px}
  .gallery a:first-child{grid-column:span 2;grid-row:auto}
  .story-card{display:grid;grid-template-columns:104px 1fr}
  .story-card img{height:100%;min-height:158px}
  .story-card>div{padding:14px}
  .video-cover{min-height:270px}
  .contact-card{padding:19px}
  .contact-line{font-size:12px;overflow-wrap:anywhere}
  .app-banner,.season-card{display:block}
  .app-badges{margin-top:18px;gap:8px}
  .newsletter{display:block;padding:23px}
  .newsletter form{display:grid!important;grid-template-columns:1fr;margin-top:18px}
  .newsletter input{width:100%;min-width:0}
  .hero-spec-pill{display:none}
}

/* ── REDUCED MOTION & JS-OFF SAFETY ── */
@media(prefers-reduced-motion:reduce){
  .hero-bg,.play-circle,.marquee-track{animation:none}
  .reveal{opacity:1;transform:none;filter:none;transition:none}
}

</style>
<noscript><style>.reveal{opacity:1;transform:none;filter:none}</style></noscript>
@endpush

@section('content')
<div class="progress-bar-top" id="progress"></div>

<main>

{{-- ══ HERO ══ --}}
<section class="hero" id="home">
  {{-- Layered background --}}
  <div class="hero-bg" aria-hidden="true"></div>
  <div class="hero-overlay" aria-hidden="true"></div>
  <div class="hero-glow" aria-hidden="true"></div>

  <div class="wrap" style="position:relative;z-index:1">
    <div class="hero-inner">

      {{-- LEFT: Text content --}}
      <div class="heroText">
        <span class="eyebrow reveal">{{ __('home.hero_eyebrow_full') }}</span>
        <h1 class="reveal delay">
          {{ __('home.hero_title_a') }}<br>
          <span class="gradient">{{ __('home.hero_title_b') }}</span>
        </h1>
        <p class="reveal delay">{{ __('home.hero_desc') }}</p>
        <div class="reveal delay" style="display:flex;gap:12px;flex-wrap:wrap;margin-top:28px">
          <a class="btn-legacy" href="#motorcycles">{{ __('home.hero_cta_explore') }}</a>
          <a class="btn-legacy outline" href="#about">{{ __('home.hero_cta_about') }}</a>
        </div>
        <div class="stats reveal">
          <div><b data-count="10000">0</b><span>{{ __('home.stat_trusted') }}</span></div>
          <div><b data-count="25">0</b><span>{{ __('home.stat_brand_models') }}</span></div>
          <div><b data-count="12">0</b><span>{{ __('home.stat_service_centers') }}</span></div>
        </div>
      </div>

      {{-- RIGHT: Bike image showcase --}}
      <div class="hero-bike-col reveal delay">
        <img
          src="/images/hero-bike.jpg"
          alt="MOTOVEX Motorcycle"
          loading="eager"
        >
        {{-- Floating spec pill --}}
        <div class="hero-spec-pill">
          <div>
            <b style="color:var(--mx-purple-bright)">10K+</b>
            <span>Customers</span>
          </div>
          <div class="hero-spec-sep"></div>
          <div>
            <b>25+</b>
            <span>Brands</span>
          </div>
          <div class="hero-spec-sep"></div>
          <div>
            <b>12+</b>
            <span>Centers</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ══ BRANDS ══ --}}
<section class="section mx-light" style="padding:35px 0">
  <div class="wrap">
    <div class="head">
      <div>
        <span class="eyebrow">{{ __('home.brands_eyebrow') }}</span>
        <h2>{{ __('home.brands_heading') }}</h2>
      </div>
    </div>
    <div class="grid brands">
      @forelse(($brands ?? collect()) as $brand)
        <a class="brand" href="{{ route('brands.show', $brand->slug) }}">{{ $brand->name }}</a>
      @empty
        @foreach(['HONDA','YAMAHA','SUZUKI','Kawasaki','BMW','Benelli','aprilia','KTM'] as $b)
          <div class="brand">{{ $b }}</div>
        @endforeach
      @endforelse
    </div>
  </div>
</section>

{{-- ══ CATEGORIES ══ --}}
<section class="section" id="categories">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.cats_eyebrow') }}</span>
        <h2>{{ __('home.cats_heading') }}</h2>
        <p>{{ __('home.cats_subheading') }}</p>
      </div>
      <a class="purple" href="{{ route('motorcycles.index') }}">{{ __('home.cats_all_link') }} ←</a>
    </div>
    <div class="grid cats">
      @foreach([
        ['cats_sport',   'cats_sport_desc',   'photo-1558981806-ec527fa84c39'],
        ['cats_touring', 'cats_touring_desc',  'photo-1558981403-c5f9899a28bc'],
        ['cats_adventure','cats_adventure_desc','photo-1568772585407-9361f9bf3a87'],
        ['cats_scooter', 'cats_scooter_desc',  'photo-1558981806-ec527fa84c39'],
      ] as [$titleKey,$descKey,$photo])
      <a class="cat reveal" href="{{ route('motorcycles.index') }}">
        <img loading="lazy" src="https://images.unsplash.com/{{ $photo }}?auto=format&fit=crop&w=700&q=85" alt="{{ __('home.'.$titleKey) }}">
        <div><h3>{{ __('home.'.$titleKey) }}</h3><p>{{ __('home.'.$descKey) }}</p></div>
      </a>
      @endforeach
    </div>
  </div>
</section>

{{-- ══ MOTORCYCLES ══ --}}
<section class="section mx-light" id="motorcycles">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.latest_eyebrow') }}</span>
        <h2>{{ __('home.latest_heading') }}</h2>
        <p>{{ __('home.latest_subheading') }}</p>
      </div>
      <a class="btn-legacy" href="{{ route('motorcycles.index') }}">{{ __('home.latest_view_all') }} ←</a>
    </div>
    <div class="grid products">
      @forelse(($motorcycles ?? collect())->take(8) as $m)
      <a class="product reveal" href="{{ route('motorcycles.show', $m->slug) }}">
        <img loading="lazy" src="{{ $m->image_url ?? 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=700&q=80' }}" alt="{{ $m->model_name }}">
        <div class="productInfo">
          <span class="eyebrow">{{ $m->brand->name ?? 'MOTOVEX' }}</span>
          <h3>{{ $m->model_name }}</h3>
          <p>{{ $m->year ?? '' }}</p>
          @if($m->price)
            <span class="price">{{ number_format($m->price) }} {{ $m->currency ?? 'EGP' }}</span>
          @else
            <span class="price">{{ __('home.latest_ask_price') }}</span>
          @endif
        </div>
      </a>
      @empty
      @foreach(['Street Sport','Adventure 650','Urban Scooter','Touring Pro'] as $m)
      <a class="product reveal" href="{{ route('motorcycles.index') }}">
        <img loading="lazy" src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=700&q=80" alt="Motorcycle">
        <div class="productInfo">
          <span class="eyebrow">MOTOVEX</span>
          <h3>{{ $m }}</h3>
          <p>{{ __('home.latest_empty') }}</p>
          <span class="price">{{ __('home.latest_learn_more') }} ←</span>
        </div>
      </a>
      @endforeach
      @endforelse
    </div>
  </div>
</section>

{{-- ══ PROMO ══ --}}
<section class="section">
  <div class="wrap">
    <div class="promo reveal">
      <div>
        <span class="eyebrow">{{ __('home.promo_eyebrow') }}</span>
        <h2>{{ __('home.promo_heading') }}</h2>
        <p style="color:#e0d6ff;font-size:13px;margin:10px 0 20px;line-height:1.8">{{ __('home.promo_subheading') }}</p>
        <a class="btn-legacy" href="{{ route('promotions') }}" style="background:#fff;color:#7847ff;box-shadow:none">{{ __('home.promo_cta') }} ←</a>
      </div>
    </div>
  </div>
</section>

{{-- ══ SERVICES ══ --}}
<section class="section mx-light" id="services">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.services_eyebrow') }}</span>
        <h2>{{ __('home.services_heading') }}</h2>
        <p>{{ __('home.services_subheading') }}</p>
      </div>
      <a class="purple" href="{{ route('services') }}">{{ __('home.services_view_all') }} ←</a>
    </div>
    <div class="grid services">
      @forelse(($services ?? collect())->take(8) as $s)
      <a class="service reveal" href="{{ route('services') }}">
        <div class="ico-wrap"><i class="fa-solid {{ $s->iconClass ?? 'fa-star' }}"></i></div>
        <h3>{{ $s->title }}</h3>
        <p>{{ $s->description }}</p>
      </a>
      @empty
      @foreach([
        ['fa-screwdriver-wrench','svc_fallback_1_title','svc_fallback_1_desc'],
        ['fa-gears',             'svc_fallback_2_title','svc_fallback_2_desc'],
        ['fa-wallet',            'svc_fallback_3_title','svc_fallback_3_desc'],
        ['fa-road',              'svc_fallback_4_title','svc_fallback_4_desc'],
      ] as $s)
      <div class="service reveal">
        <div class="ico-wrap"><i class="fa-solid {{ $s[0] }}"></i></div>
        <h3>{{ __('home.'.$s[1]) }}</h3>
        <p>{{ __('home.'.$s[2]) }}</p>
      </div>
      @endforeach
      @endforelse
    </div>
  </div>
</section>

{{-- ══ ABOUT ══ --}}
<section class="section" id="about">
  <div class="wrap">
    <div class="about">
      <div class="reveal">
        <span class="eyebrow">{{ __('home.about_eyebrow') }}</span>
        <h2 style="font-size:clamp(32px,4vw,50px);line-height:1.3;color:#f4f8ff">
          {{ __('home.about_heading_a') }}<br>
          <span class="gradient">{{ __('home.about_heading_b') }}</span>
        </h2>
        <p>{{ __('home.about_text') }}</p>
        <a class="btn-legacy" href="#contact">{{ __('home.about_cta') }} ←</a>
      </div>
      <img class="reveal delay" loading="lazy" src="https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=1000&q=85" alt="Motorcycle riding">
    </div>
    <div class="metrics reveal">
      <div><b data-count="10000">0</b><span>{{ __('home.stat_trusted') }}</span></div>
      <div><b data-count="25">0</b><span>{{ __('home.stat_brand_models') }}</span></div>
      <div><b data-count="12">0</b><span>{{ __('home.stat_service_centers') }}</span></div>
      <div><b>24/7</b><span>{{ app()->getLocale() === 'ar' ? 'دعم ومساندة' : 'Support' }}</span></div>
    </div>
  </div>
</section>

{{-- ══ REVIEWS ══ --}}
<section class="section mx-light">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.testimonials_eyebrow') }}</span>
        <h2>{{ __('home.testimonials_heading') }}</h2>
      </div>
    </div>
    <div class="grid reviews">
      <div class="review reveal"><div class="stars">★★★★★</div><p>{{ __('home.review_1') }}</p><b>{{ __('home.review_customer') }}</b></div>
      <div class="review reveal"><div class="stars">★★★★★</div><p>{{ __('home.review_2') }}</p><b>{{ __('home.review_customer') }}</b></div>
      <div class="review reveal"><div class="stars">★★★★★</div><p>{{ __('home.review_3') }}</p><b>{{ __('home.review_customer') }}</b></div>
    </div>
  </div>
</section>

{{-- ══ FAQ ══ --}}
<section class="section mx-light">
  <div class="wrap faq">
    <div class="head">
      <div>
        <span class="eyebrow">{{ __('home.faq_eyebrow') }}</span>
        <h2>{{ __('home.faq_heading') }}</h2>
      </div>
    </div>
    @foreach(['faq_q1'=>'faq_a1','faq_q2'=>'faq_a2','faq_q3'=>'faq_a3','faq_q4'=>'faq_a4'] as $qk=>$ak)
    <div class="faqItem">
      <button class="faqQ">{{ __('home.'.$qk) }} <i class="fa fa-plus purple"></i></button>
      <div class="faqA">{{ __('home.'.$ak) }}</div>
    </div>
    @endforeach
  </div>
</section>

{{-- ══ MARQUEE ══ --}}
<section class="marquee">
  <div class="marquee-track">
    <span>{{ __('home.marquee_text') }} </span>
    <span>{{ __('home.marquee_text') }} </span>
  </div>
</section>

{{-- ══ WHY MOTOVEX ══ --}}
<section class="section" id="why-motovex">
  <div class="wrap split-feature">
    <div class="feature-photo reveal">
      <img loading="lazy" src="https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=1100&q=90" alt="Motorcycle detail">
      <div class="floating-note"><b>360°</b><span>{{ __('home.why_floating_note') }}</span></div>
    </div>
    <div class="feature-copy reveal delay">
      <span class="eyebrow">{{ __('home.why_eyebrow') }}</span>
      <h2>{{ __('home.why_heading_a') }} <span class="purple">{{ __('home.why_heading_b') }}</span></h2>
      <p>{{ __('home.why_text') }}</p>
      <div class="checkline"><i class="fa-solid fa-circle-check"></i><span>{{ __('home.why_check_1') }}</span></div>
      <div class="checkline"><i class="fa-solid fa-circle-check"></i><span>{{ __('home.why_check_2') }}</span></div>
      <div class="checkline"><i class="fa-solid fa-circle-check"></i><span>{{ __('home.why_check_3') }}</span></div>
      <a class="btn-legacy" href="#about">{{ __('home.why_cta') }} ←</a>
    </div>
  </div>
</section>

{{-- ══ TIMELINE ══ --}}
<section class="section">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.timeline_eyebrow') }}</span>
        <h2>{{ __('home.timeline_heading') }}</h2>
        <p>{{ __('home.timeline_subheading') }}</p>
      </div>
    </div>
    <div class="timeline">
      @foreach([['step1_title','step1_desc'],['step2_title','step2_desc'],['step3_title','step3_desc'],['step4_title','step4_desc']] as $s)
      <div class="step reveal">
        <h3>{{ __('home.'.$s[0]) }}</h3>
        <p>{{ __('home.'.$s[1]) }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ══ SMART CHOICE CATEGORIES ══ --}}
<section class="section">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.smart_eyebrow') }}</span>
        <h2>{{ __('home.smart_heading') }}</h2>
        <p>{{ __('home.smart_subheading') }}</p>
      </div>
    </div>
    <div class="grid cats">
      @foreach([
        ['smart_tag_perf',   'smart_title_sport',   'smart_desc_sport',   'photo-1558981806-ec527fa84c39'],
        ['smart_tag_adv',    'smart_title_adv',     'smart_desc_adv',     'photo-1568772585407-9361f9bf3a87'],
        ['smart_tag_comfort','smart_title_touring', 'smart_desc_touring', 'photo-1558981403-c5f9899a28bc'],
        ['smart_tag_city',   'smart_title_urban',   'smart_desc_urban',   'photo-1558981806-ec527fa84c39'],
      ] as [$tagKey,$titleKey,$descKey,$photo])
      <a class="cat reveal" href="{{ route('motorcycles.index') }}">
        <img loading="lazy" src="https://images.unsplash.com/{{ $photo }}?auto=format&fit=crop&w=700&q=85" alt="{{ __('home.'.$titleKey) }}">
        <div>
          <span class="tag-legacy">{{ __('home.'.$tagKey) }}</span>
          <h3>{{ __('home.'.$titleKey) }}</h3>
          <p>{{ __('home.'.$descKey) }}</p>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</section>

{{-- ══ SPARE PARTS ══ --}}
<section class="section mx-light">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.parts_ext_eyebrow') }}</span>
        <h2>{{ __('home.parts_ext_heading') }}</h2>
        <p>{{ __('home.parts_ext_subheading') }}</p>
      </div>
      <a class="btn-legacy" href="{{ route('spare-parts.index') }}">{{ __('home.parts_shop_cta') }} ←</a>
    </div>
    <div class="category-strip reveal">
      @foreach(['parts_cat_oils','parts_cat_filters','parts_cat_brakes','parts_cat_tires','parts_cat_chains','parts_cat_batteries','parts_cat_accessories','parts_cat_protection'] as $cat)
        <span>{{ __('home.'.$cat) }}</span>
      @endforeach
    </div>
    <div class="grid products" style="margin-bottom:25px">
      @forelse(($spareParts ?? collect())->take(4) as $sp)
      <a class="product reveal" href="{{ route('spare-parts.show', $sp->slug) }}">
        <img loading="lazy" src="{{ $sp->image_url ?? 'https://images.unsplash.com/photo-1611821064430-0d40291d0f0d?auto=format&fit=crop&w=700&q=80' }}" alt="{{ $sp->name }}">
        <div class="productInfo">
          <span class="eyebrow">{{ $sp->categoryLabel() }}</span>
          <h3>{{ $sp->name }}</h3>
          <p>{{ $sp->brand->name ?? 'MOTOVEX' }}</p>
          @if($sp->price)
            <span class="price">{{ number_format($sp->price) }} {{ $sp->currency ?? 'EGP' }}</span>
          @else
            <span class="price">{{ __('home.latest_ask_price') }}</span>
          @endif
        </div>
      </a>
      @empty
      @foreach(['Brake Pad Set','Oil Filter Pro','Chain Kit Premium','Sport Battery'] as $sp)
      <a class="product reveal" href="{{ route('spare-parts.index') }}">
        <img loading="lazy" src="https://images.unsplash.com/photo-1611821064430-0d40291d0f0d?auto=format&fit=crop&w=700&q=80" alt="{{ $sp }}">
        <div class="productInfo">
          <span class="eyebrow">MOTOVEX</span>
          <h3>{{ $sp }}</h3>
          <p>{{ __('home.parts_genuine') }}</p>
          <span class="price">{{ __('home.latest_learn_more') }} ←</span>
        </div>
      </a>
      @endforeach
      @endforelse
    </div>
    <div class="season-card reveal">
      <div>
        <h3>{{ __('home.parts_season_title') }}</h3>
        <p>{{ __('home.parts_season_text') }}</p>
      </div>
      <a class="btn-legacy" href="{{ route('spare-parts.index') }}">{{ __('home.parts_browse_cta') }} ←</a>
    </div>
  </div>
</section>

{{-- ══ BENEFITS ══ --}}
<section class="section">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.commit_eyebrow') }}</span>
        <h2>{{ __('home.commit_heading') }}</h2>
      </div>
    </div>
    <div class="benefit-grid">
      @foreach([
        ['fa-shield-halved','benefit_1_title','benefit_1_desc'],
        ['fa-headset',      'benefit_2_title','benefit_2_desc'],
        ['fa-screwdriver-wrench','benefit_3_title','benefit_3_desc'],
        ['fa-tags',         'benefit_4_title','benefit_4_desc'],
        ['fa-store',        'benefit_5_title','benefit_5_desc'],
        ['fa-motorcycle',   'benefit_6_title','benefit_6_desc'],
      ] as $b)
      <div class="benefit reveal">
        <div class="ico-wrap"><i class="fa-solid {{ $b[0] }}"></i></div>
        <div><h3>{{ __('home.'.$b[1]) }}</h3><p>{{ __('home.'.$b[2]) }}</p></div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ══ GALLERY ══ --}}
<section class="section">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.gallery_eyebrow') }}</span>
        <h2>{{ __('home.gallery_heading') }}</h2>
        <p>{{ __('home.gallery_subheading') }}</p>
      </div>
    </div>
    <div class="gallery reveal">
      <a href="{{ route('motorcycles.index') }}"><img loading="lazy" src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=1000&q=85" alt="Motorcycle"><span>{{ __('home.gallery_perf') }}</span></a>
      <a href="{{ route('motorcycles.index') }}"><img loading="lazy" src="https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=700&q=85" alt="Adventure"><span>{{ __('home.gallery_adv') }}</span></a>
      <a href="{{ route('services') }}"><img loading="lazy" src="https://images.unsplash.com/photo-1558981403-c5f9899a28bc?auto=format&fit=crop&w=700&q=85" alt="Service"><span>{{ __('home.gallery_care') }}</span></a>
      <a href="{{ route('contact') }}"><img loading="lazy" src="https://images.unsplash.com/photo-1485965120184-e220f721d03e?auto=format&fit=crop&w=700&q=85" alt="Bike"><span>{{ __('home.gallery_details') }}</span></a>
      <a href="{{ route('motorcycles.index') }}"><img loading="lazy" src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=700&q=85" alt="Ride"><span>{{ __('home.gallery_freedom') }}</span></a>
    </div>
  </div>
</section>

{{-- ══ STORIES/ARTICLES ══ --}}
<section class="section mx-light">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.articles_eyebrow') }}</span>
        <h2>{{ __('home.articles_heading') }}</h2>
      </div>
    </div>
    <div class="brand-story">
      <article class="story-card reveal">
        <img loading="lazy" src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=85" alt="Article 1">
        <div>
          <span class="tag-legacy">{{ __('home.article_tag_buyer') }}</span>
          <h3>{{ __('home.article_title_1') }}</h3>
          <p>{{ __('home.article_desc_1') }}</p>
          <a class="purple" href="{{ route('contact') }}">{{ __('home.article_read_more') }} ←</a>
        </div>
      </article>
      <article class="story-card reveal">
        <img loading="lazy" src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?auto=format&fit=crop&w=800&q=85" alt="Article 2">
        <div>
          <span class="tag-legacy">{{ __('home.article_tag_maint') }}</span>
          <h3>{{ __('home.article_title_2') }}</h3>
          <p>{{ __('home.article_desc_2') }}</p>
          <a class="purple" href="{{ route('services') }}">{{ __('home.article_read_more') }} ←</a>
        </div>
      </article>
      <article class="story-card reveal">
        <img loading="lazy" src="https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?auto=format&fit=crop&w=800&q=85" alt="Article 3">
        <div>
          <span class="tag-legacy">{{ __('home.article_tag_safety') }}</span>
          <h3>{{ __('home.article_title_3') }}</h3>
          <p>{{ __('home.article_desc_3') }}</p>
          <a class="purple" href="{{ route('contact') }}">{{ __('home.article_read_more') }} ←</a>
        </div>
      </article>
    </div>
  </div>
</section>

{{-- ══ VIDEO COVER ══ --}}
<section class="section">
  <div class="wrap">
    <div class="video-cover reveal">
      <div>
        <div class="play-circle"><i class="fa-solid fa-play"></i></div>
        <h2>{{ __('home.video_heading') }}</h2>
        <p>{{ __('home.video_sub') }}</p>
      </div>
    </div>
  </div>
</section>

{{-- ══ CONTACT ══ --}}
<section class="section" id="contact">
  <div class="wrap">
    <div class="head reveal">
      <div>
        <span class="eyebrow">{{ __('home.contact_eyebrow') }}</span>
        <h2>{{ __('home.contact_heading') }}</h2>
        <p>{{ __('home.contact_subheading') }}</p>
      </div>
    </div>
    <div class="contact-panel">
      <div class="contact-card reveal">
        <h3>{{ __('home.contact_form_title') }}</h3>
        <form method="POST" action="{{ route('contact.store') }}">
          @csrf
          <input type="hidden" name="enquiry_type" value="general">
          <input name="name" required placeholder="{{ __('home.contact_name_ph') }}">
          <input type="email" name="email" required placeholder="{{ __('home.contact_email_ph') }}">
          <input name="phone" placeholder="{{ __('home.contact_phone_ph') }}">
          <select name="subject" required>
            <option value="">{{ __('home.contact_type_label') }}</option>
            <option value="{{ __('home.contact_type_buy') }}">{{ __('home.contact_type_buy') }}</option>
            <option value="{{ __('home.contact_type_parts') }}">{{ __('home.contact_type_parts') }}</option>
            <option value="{{ __('home.contact_type_service') }}">{{ __('home.contact_type_service') }}</option>
            <option value="{{ __('home.contact_type_other') }}">{{ __('home.contact_type_other') }}</option>
          </select>
          <textarea name="message" required placeholder="{{ __('home.contact_msg_ph') }}"></textarea>
          <button class="btn-legacy" type="submit">{{ __('home.contact_send_btn') }} ←</button>
        </form>
      </div>
      <div class="contact-card reveal delay">
        <h3>{{ __('home.contact_info_title') }}</h3>
        <p style="color:#91a9c6;font-size:14px;line-height:1.9">{{ __('home.contact_info_desc') }}</p>
        <div class="contact-details">
          <div class="contact-line"><i class="fa-solid fa-envelope"></i><span>{{ config('motovex.contact_email','info@motovex.com') }}</span></div>
          <div class="contact-line"><i class="fa-solid fa-phone"></i><span>{{ config('motovex.phone','+20 100 000 0000') }}</span></div>
          <div class="contact-line"><i class="fa-solid fa-location-dot"></i><span>{{ config('motovex.address','Egypt') }}</span></div>
          <div class="contact-line"><i class="fa-regular fa-clock"></i><span>{{ __('home.contact_hours') }}</span></div>
        </div>
        <div style="margin-top:25px">
          <a class="btn-legacy" href="{{ route('contact') }}">{{ __('home.contact_page_cta') }} <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ══ APP BANNER ══ --}}
<section class="section">
  <div class="wrap">
    <div class="app-banner reveal">
      <div>
        <span class="eyebrow">{{ __('home.app_eyebrow') }}</span>
        <h2>{{ __('home.app_heading') }}</h2>
        <p>{{ __('home.app_text') }}</p>
      </div>
      <div class="app-badges">
        <a href="{{ route('motorcycles.index') }}"><i class="fa-solid fa-motorcycle"></i> {{ __('home.app_cta_bike') }}</a>
        <a href="{{ route('spare-parts.index') }}"><i class="fa-solid fa-gears"></i> {{ __('home.app_cta_parts') }}</a>
      </div>
    </div>
  </div>
</section>

{{-- ══ NEWSLETTER ══ --}}
<section class="section">
  <div class="wrap">
    <div class="newsletter reveal">
      <div>
        <span class="eyebrow">{{ __('home.news_eyebrow') }}</span>
        <h2>{{ __('home.news_heading') }}</h2>
        <p>{{ __('home.news_text') }}</p>
      </div>
      <form method="POST" action="{{ route('contact.store') }}" style="display:flex;gap:10px;flex-wrap:wrap">
        @csrf
        <input type="hidden" name="enquiry_type" value="general">
        <input type="hidden" name="subject" value="{{ __('home.news_subject') }}">
        <input type="hidden" name="name" value="Newsletter">
        <input type="hidden" name="message" value="{{ __('home.news_subject') }}">
        <input type="email" name="email" required placeholder="{{ __('home.news_placeholder') }}">
        <button class="btn-legacy">{{ __('home.news_btn') }} ←</button>
      </form>
    </div>
  </div>
</section>

</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Scroll reveal with a small, natural stagger for groups.
  const revealItems = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window && !reducedMotion) {
    const revealObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('show');
        observer.unobserve(entry.target);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -35px 0px' });
    revealItems.forEach(item => revealObserver.observe(item));
  } else {
    revealItems.forEach(item => item.classList.add('show'));
  }

  // Count-up animation starts only when the statistic enters view.
  const counters = document.querySelectorAll('[data-count]');
  if ('IntersectionObserver' in window && !reducedMotion) {
    const counterObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        const end = Number(el.dataset.count || 0);
        const duration = 1450;
        const start = performance.now();
        const tick = now => {
          const progress = Math.min((now - start) / duration, 1);
          const eased = 1 - Math.pow(1 - progress, 4);
          el.textContent = Math.floor(end * eased).toLocaleString() + (progress >= 1 ? '+' : '');
          if (progress < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
        observer.unobserve(el);
      });
    }, { threshold: 0.55 });
    counters.forEach(counter => counterObserver.observe(counter));
  } else {
    counters.forEach(counter => counter.textContent = Number(counter.dataset.count || 0).toLocaleString() + '+');
  }

  // Accessible FAQ accordion: one open item at a time.
  document.querySelectorAll('.faqQ').forEach((button, index) => {
    const answer = button.nextElementSibling;
    const answerId = `motovex-faq-answer-${index + 1}`;
    answer.id = answerId;
    button.setAttribute('aria-controls', answerId);
    button.setAttribute('aria-expanded', 'false');
    button.addEventListener('click', () => {
      const item = button.closest('.faqItem');
      const willOpen = !item.classList.contains('open');
      document.querySelectorAll('.faqItem.open').forEach(openItem => {
        openItem.classList.remove('open');
        const openButton = openItem.querySelector('.faqQ');
        openButton?.setAttribute('aria-expanded', 'false');
        const icon = openButton?.querySelector('i');
        if (icon) icon.className = 'fa fa-plus purple';
      });
      if (willOpen) {
        item.classList.add('open');
        button.setAttribute('aria-expanded', 'true');
        const icon = button.querySelector('i');
        if (icon) icon.className = 'fa fa-minus purple';
      }
    });
  });

  // Page reading progress.
  const progress = document.getElementById('progress');
  let ticking = false;
  const updateProgress = () => {
    if (!progress) return;
    const maxScroll = document.documentElement.scrollHeight - window.innerHeight;
    progress.style.width = `${maxScroll > 0 ? (window.scrollY / maxScroll) * 100 : 0}%`;
    ticking = false;
  };
  window.addEventListener('scroll', () => {
    if (!ticking) {
      window.requestAnimationFrame(updateProgress);
      ticking = true;
    }
  }, { passive: true });
  updateProgress();

  // Pause decorative marquee while the page is hidden.
  const marquee = document.querySelector('.marquee-track');
  document.addEventListener('visibilitychange', () => {
    if (!marquee) return;
    marquee.style.animationPlayState = document.hidden ? 'paused' : '';
  });
});
</script>
@endpush
