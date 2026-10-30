<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Order Received | MOTOVEX</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: #0a0614;
      color: #f8fafc;
      min-height: 100vh;
      overflow-x: hidden;
      -webkit-font-smoothing: antialiased;
    }

    /* ── Animated mesh background ── */
    .bg-mesh {
      position: fixed; inset: 0; z-index: 0; pointer-events: none;
      background:
        radial-gradient(ellipse 80% 60% at 20% 0%, rgba(124,58,237,.35) 0%, transparent 60%),
        radial-gradient(ellipse 60% 50% at 80% 100%, rgba(99,102,241,.25) 0%, transparent 55%),
        radial-gradient(ellipse 50% 40% at 50% 50%, rgba(167,139,250,.1) 0%, transparent 70%),
        linear-gradient(160deg, #0a0614 0%, #110d22 40%, #0d0a1e 100%);
    }

    /* Animated particles */
    .particle {
      position: fixed; border-radius: 50%; pointer-events: none; z-index: 0;
      animation: drift linear infinite;
    }
    @keyframes drift {
      0%   { transform: translateY(100vh) rotate(0deg); opacity: 0; }
      10%  { opacity: 1; }
      90%  { opacity: 1; }
      100% { transform: translateY(-10vh) rotate(720deg); opacity: 0; }
    }

    /* ── Confetti burst ── */
    .confetti-wrap {
      position: fixed; inset: 0; pointer-events: none; z-index: 2;
      overflow: hidden;
    }
    .confetti-piece {
      position: absolute; top: -20px;
      width: 8px; height: 8px;
      border-radius: 2px;
      animation: confettiFall linear forwards;
    }
    @keyframes confettiFall {
      0%   { transform: translateY(0) rotate(0deg); opacity: 1; }
      100% { transform: translateY(110vh) rotate(720deg); opacity: 0; }
    }

    /* ── Hero checkmark ── */
    @keyframes circleGrow {
      0%   { transform: scale(0); opacity: 0; }
      60%  { transform: scale(1.12); }
      100% { transform: scale(1); opacity: 1; }
    }
    @keyframes checkStroke {
      0%   { stroke-dashoffset: 120; }
      100% { stroke-dashoffset: 0; }
    }
    @keyframes pulseRing {
      0%   { transform: scale(1); opacity: .6; }
      100% { transform: scale(2.2); opacity: 0; }
    }
    .check-wrap { animation: circleGrow .7s cubic-bezier(.16,1,.3,1) .1s both; }
    .check-path { stroke-dasharray: 120; stroke-dashoffset: 120; animation: checkStroke .5s ease .7s forwards; }
    .pulse-ring  { animation: pulseRing 1.4s ease .8s infinite; }

    /* ── Slide-up stagger ── */
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(32px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .su { opacity: 0; animation: slideUp .65s cubic-bezier(.16,1,.3,1) forwards; }
    .su-1 { animation-delay: .25s; }
    .su-2 { animation-delay: .4s;  }
    .su-3 { animation-delay: .55s; }
    .su-4 { animation-delay: .7s;  }
    .su-5 { animation-delay: .85s; }

    /* ── Glass cards ── */
    .glass {
      background: rgba(255,255,255,.055);
      backdrop-filter: blur(20px) saturate(1.5);
      border: 1px solid rgba(255,255,255,.09);
      border-radius: 20px;
    }
    .glass-light {
      background: rgba(255,255,255,.07);
      backdrop-filter: blur(16px);
      border: 1px solid rgba(255,255,255,.1);
      border-radius: 16px;
    }

    /* ── Reference badge ── */
    .ref-badge {
      font-family: ui-monospace, 'JetBrains Mono', monospace;
      font-size: 1.45rem;
      font-weight: 900;
      letter-spacing: .12em;
      background: linear-gradient(135deg, #a78bfa, #7c3aed, #818cf8);
      -webkit-background-clip: text; -webkit-text-fill-color: transparent;
      padding: .6rem 1.4rem;
      border-radius: 12px;
      background-clip: text;
      display: inline-block;
      border: 1px solid rgba(167,139,250,.25);
      backdrop-filter: blur(8px);
      background-color: rgba(124,58,237,.08);
    }

    /* ── Step items ── */
    .step-item { display: flex; align-items: flex-start; gap: 1rem; }
    .step-ico {
      width: 40px; height: 40px; flex-shrink: 0;
      border-radius: 12px; display: grid; place-items: center;
      background: linear-gradient(135deg, rgba(124,58,237,.3), rgba(99,102,241,.2));
      border: 1px solid rgba(167,139,250,.2);
      font-size: .9rem; color: #a78bfa;
    }

    /* ── Stat pill ── */
    .stat-pill {
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      padding: 1rem .75rem;
      border-radius: 14px;
      background: rgba(124,58,237,.12);
      border: 1px solid rgba(167,139,250,.18);
    }

    /* ── Buttons ── */
    .btn-primary {
      display: inline-flex; align-items: center; gap: .65rem;
      padding: .95rem 1.75rem;
      border-radius: 14px; font-size: .92rem; font-weight: 700; text-decoration: none;
      background: linear-gradient(135deg, #7c3aed, #6d28d9);
      color: #fff;
      border: 1px solid rgba(167,139,250,.3);
      box-shadow: 0 12px 32px rgba(124,58,237,.4), 0 4px 12px rgba(0,0,0,.2);
      transition: all .3s cubic-bezier(.16,1,.3,1);
      position: relative; overflow: hidden;
    }
    .btn-primary::before {
      content: ''; position: absolute; inset: 0;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,.15), transparent);
      transform: translateX(-100%); transition: transform .5s;
    }
    .btn-primary:hover::before { transform: translateX(100%); }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 18px 42px rgba(124,58,237,.55); }

    .btn-ghost {
      display: inline-flex; align-items: center; gap: .65rem;
      padding: .93rem 1.6rem;
      border-radius: 14px; font-size: .92rem; font-weight: 700; text-decoration: none;
      color: #c4b5fd;
      border: 1px solid rgba(167,139,250,.22);
      background: rgba(255,255,255,.04);
      backdrop-filter: blur(8px);
      transition: all .25s;
    }
    .btn-ghost:hover {
      background: rgba(167,139,250,.12);
      border-color: rgba(167,139,250,.4);
      color: #fff;
      transform: translateY(-1px);
    }

    /* ── Divider ── */
    .divider { border: none; border-top: 1px solid rgba(255,255,255,.07); }
  </style>
</head>
<body>

{{-- Background --}}
<div class="bg-mesh" aria-hidden="true"></div>

{{-- Confetti --}}
<div class="confetti-wrap" id="confettiWrap" aria-hidden="true"></div>

{{-- Navbar --}}
@include('partials.navbar')

<main style="position:relative;z-index:1;padding:2.5rem 1.25rem 4rem;">
  <div style="width:100%;max-width:780px;margin:0 auto;">

    {{-- ── Hero section ── --}}
    <div style="text-align:center;margin-bottom:2.75rem;">

      {{-- Animated check circle --}}
      <div style="display:inline-block;position:relative;margin-bottom:1.75rem;">
        {{-- Pulse rings --}}
        <div class="pulse-ring" style="position:absolute;inset:-16px;border-radius:50%;border:2px solid rgba(74,222,128,.35);"></div>
        <div class="pulse-ring" style="position:absolute;inset:-8px;border-radius:50%;border:2px solid rgba(74,222,128,.2);animation-delay:.3s;"></div>

        <div class="check-wrap" style="width:96px;height:96px;border-radius:50%;background:linear-gradient(135deg,rgba(22,163,74,.25),rgba(16,185,129,.15));border:2px solid rgba(74,222,128,.4);display:grid;place-items:center;box-shadow:0 20px 60px rgba(74,222,128,.2),0 0 0 1px rgba(74,222,128,.1);">
          <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
            <path class="check-path" d="M12 25L21 34L37 16"
                  stroke="#4ade80" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
      </div>

      {{-- Badge --}}
      <div class="su su-1" style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(74,222,128,.1);border:1px solid rgba(74,222,128,.25);border-radius:999px;padding:.38rem 1rem;font-size:.72rem;font-weight:700;letter-spacing:.08em;color:#4ade80;text-transform:uppercase;margin-bottom:1rem;">
        <span style="width:6px;height:6px;border-radius:50%;background:#4ade80;animation:pulse 2s infinite;display:inline-block;"></span>
        Order Successfully Submitted
      </div>

      <h1 class="su su-1" style="font-size:clamp(2rem,5vw,3rem);font-weight:900;letter-spacing:-.04em;line-height:1.1;background:linear-gradient(135deg,#f8fafc 30%,#c4b5fd 70%,#a78bfa);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:.75rem;">
        Order Request Received!
      </h1>
      <p class="su su-2" style="color:#94a3b8;font-size:1rem;max-width:480px;margin:0 auto;line-height:1.7;">
        Thank you for your enquiry. Our team will review your request and contact you within
        <strong style="color:#a78bfa;">24 hours</strong> to confirm availability and next steps.
      </p>
    </div>

    {{-- ── Reference card ── --}}
    @if($reference ?? false)
    <div class="glass su su-2" style="padding:2rem;margin-bottom:1.5rem;text-align:center;">
      <p style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.14em;color:#6d28d9;margin-bottom:.85rem;display:flex;align-items:center;justify-content:center;gap:.5rem;">
        <i class="fa-solid fa-hashtag" style="color:#a78bfa;"></i>
        Your Order Reference
      </p>
      <div class="ref-badge">{{ $reference }}</div>
      <p style="font-size:.76rem;color:#64748b;margin-top:.85rem;display:flex;align-items:center;justify-content:center;gap:.45rem;">
        <i class="fa-solid fa-copy" style="color:#6d28d9;cursor:pointer;" onclick="copyRef('{{ $reference }}')" title="Copy reference"></i>
        <span id="refCopyMsg">Click the icon to copy · Save this to track your order</span>
      </p>
    </div>
    @endif

    {{-- ── Stats row ── --}}
    <div class="su su-3" style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;">
      <div class="stat-pill">
        <i class="fa-solid fa-clock-rotate-left" style="color:#a78bfa;font-size:1.1rem;margin-bottom:.5rem;"></i>
        <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#64748b;margin-bottom:.2rem;">Status</span>
        <span style="font-size:.9rem;font-weight:800;color:#fbbf24;">Pending Review</span>
      </div>
      <div class="stat-pill">
        <i class="fa-solid fa-shield-halved" style="color:#4ade80;font-size:1.1rem;margin-bottom:.5rem;"></i>
        <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#64748b;margin-bottom:.2rem;">Payment</span>
        <span style="font-size:.9rem;font-weight:800;color:#4ade80;">Not Required</span>
      </div>
      <div class="stat-pill">
        <i class="fa-solid fa-headset" style="color:#818cf8;font-size:1.1rem;margin-bottom:.5rem;"></i>
        <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#64748b;margin-bottom:.2rem;">Response</span>
        <span style="font-size:.9rem;font-weight:800;color:#818cf8;">Within 24h</span>
      </div>
    </div>

    {{-- ── Two-column layout (notice + steps) ── --}}
    <div class="su su-3" style="display:grid;gap:1.25rem;margin-bottom:1.5rem;">

      {{-- Notice --}}
      <div class="glass-light" style="padding:1.25rem 1.4rem;display:flex;align-items:flex-start;gap:.9rem;background:rgba(251,191,36,.06);border-color:rgba(251,191,36,.2);">
        <div style="width:36px;height:36px;border-radius:10px;background:rgba(251,191,36,.15);border:1px solid rgba(251,191,36,.25);display:grid;place-items:center;flex-shrink:0;">
          <i class="fa-solid fa-circle-info" style="color:#fbbf24;font-size:.85rem;"></i>
        </div>
        <div>
          <p style="font-size:.84rem;font-weight:800;color:#fde68a;margin-bottom:.25rem;">No Payment Taken</p>
          <p style="font-size:.78rem;color:#92400e;color:#d97706;line-height:1.6;">
            This is a purchase <em>enquiry</em>, not a confirmed sale. No card has been charged.
            Our team will contact you to finalise availability and payment details.
          </p>
        </div>
      </div>
    </div>

    {{-- ── What happens next ── --}}
    <div class="glass su su-4" style="padding:1.75rem 2rem;margin-bottom:1.5rem;">
      <p style="font-size:.7rem;font-weight:800;text-transform:uppercase;letter-spacing:.12em;color:#6d28d9;margin-bottom:1.4rem;display:flex;align-items:center;gap:.5rem;">
        <i class="fa-solid fa-list-check" style="color:#a78bfa;"></i>
        What Happens Next?
      </p>

      <div style="display:flex;flex-direction:column;gap:1.1rem;">
        @foreach([
          ['fa-phone-volume',  '#a78bfa', 'We Call You',            'Our team contacts you within 24 hours to confirm motorcycle availability and discuss details.'],
          ['fa-file-invoice',  '#818cf8', 'Order Confirmation',     'You receive a formal confirmation with final pricing and payment instructions.'],
          ['fa-truck-fast',    '#4ade80', 'Delivery or Pickup',     'Coordinate nationwide delivery to your door or collection from our nearest showroom.'],
        ] as $i => [$icon, $color, $title, $desc])
        <div class="step-item" style="{{ !$loop->last ? 'padding-bottom:1.1rem;border-bottom:1px solid rgba(255,255,255,.06);' : '' }}">
          <div style="width:40px;height:40px;flex-shrink:0;border-radius:12px;background:linear-gradient(135deg,rgba(124,58,237,.2),rgba(99,102,241,.12));border:1px solid rgba(167,139,250,.18);display:grid;place-items:center;">
            <i class="fa-solid {{ $icon }}" style="color:{{ $color }};font-size:.88rem;"></i>
          </div>
          <div style="padding-top:.15rem;">
            <p style="font-size:.88rem;font-weight:800;color:#f1f5f9;margin-bottom:.25rem;">{{ $title }}</p>
            <p style="font-size:.78rem;color:#64748b;line-height:1.6;">{{ $desc }}</p>
          </div>
        </div>
        @endforeach
      </div>
    </div>

    {{-- ── Action buttons ── --}}
    <div class="su su-5" style="display:flex;flex-wrap:wrap;gap:.9rem;justify-content:center;padding-top:.5rem;">
      @auth
        <a href="{{ route('account.orders') }}" class="btn-primary">
          <i class="fa-solid fa-receipt"></i>
          View My Orders
        </a>
      @endauth
      <a href="{{ route('motorcycles.index') }}" class="btn-ghost">
        <i class="fa-solid fa-motorcycle"></i>
        Browse More Motorcycles
      </a>
      <a href="{{ route('home') }}" class="btn-ghost">
        <i class="fa-solid fa-house"></i>
        Back to Home
      </a>
    </div>

  </div>
</main>

@include('partials.footer')

<style>
  @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }
</style>

<script>
// ── Copy reference ────────────────────────────────────────────
function copyRef(ref) {
  navigator.clipboard.writeText(ref).then(function() {
    var msg = document.getElementById('refCopyMsg');
    if (msg) {
      msg.textContent = '✓ Copied to clipboard!';
      msg.style.color = '#4ade80';
      setTimeout(function() {
        msg.textContent = 'Click the icon to copy · Save this to track your order';
        msg.style.color = '';
      }, 2500);
    }
  }).catch(function() {
    var msg = document.getElementById('refCopyMsg');
    if (msg) msg.textContent = ref;
  });
}

// ── Confetti burst ────────────────────────────────────────────
(function spawnConfetti() {
  var colors = ['#a78bfa','#7c3aed','#4ade80','#818cf8','#fbbf24','#f472b6','#38bdf8'];
  var wrap   = document.getElementById('confettiWrap');
  var count  = 90;

  for (var i = 0; i < count; i++) {
    (function(i) {
      setTimeout(function() {
        var el = document.createElement('div');
        el.className = 'confetti-piece';
        var size   = Math.random() * 8 + 5;
        var left   = Math.random() * 100;
        var dur    = Math.random() * 2.5 + 2;
        var delay  = Math.random() * 1.2;
        var color  = colors[Math.floor(Math.random() * colors.length)];
        var shapes = ['2px','50%','0'];
        var radius = shapes[Math.floor(Math.random() * shapes.length)];

        el.style.cssText = [
          'left:' + left + '%',
          'width:' + size + 'px',
          'height:' + (size * (Math.random() * 1.5 + .5)) + 'px',
          'background:' + color,
          'border-radius:' + radius,
          'animation-duration:' + dur + 's',
          'animation-delay:' + delay + 's',
          'opacity:0'
        ].join(';');

        wrap.appendChild(el);
        setTimeout(function() { el.remove(); }, (dur + delay + .5) * 1000);
      }, i * 15);
    })(i);
  }
})();

// ── Floating particles ────────────────────────────────────────
(function spawnParticles() {
  var colors = ['rgba(167,139,250,.4)','rgba(99,102,241,.3)','rgba(124,58,237,.35)'];
  for (var i = 0; i < 12; i++) {
    var el   = document.createElement('div');
    el.className = 'particle';
    var size = Math.random() * 4 + 2;
    var dur  = Math.random() * 18 + 12;
    el.style.cssText = [
      'width:' + size + 'px', 'height:' + size + 'px',
      'left:' + (Math.random() * 100) + '%',
      'background:' + colors[Math.floor(Math.random() * colors.length)],
      'animation-duration:' + dur + 's',
      'animation-delay:-' + (Math.random() * dur) + 's'
    ].join(';');
    document.body.appendChild(el);
  }
})();
</script>

</body>
</html>
