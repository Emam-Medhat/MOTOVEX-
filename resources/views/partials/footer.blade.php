{{-- =====================================================================
     UNIFIED FOOTER — MOTOVEX | Dark themed, bilingual, RTL-aware.
     ===================================================================== --}}
@php
    $isAr = app()->getLocale() === 'ar';
@endphp

<style>
.mvfoot {
    background: #030916;
    border-top: 1px solid #152c4a;
    color: #8fa8c4;
    font-size: 13px;
}
.mvfoot * { box-sizing: border-box; }
.mvfoot a { color: #8fa8c4; text-decoration: none; transition: color .2s; }
.mvfoot a:hover { color: #b689ff; }

/* ── Newsletter strip ───────────────────────────── */
.mvfoot-news {
    border-bottom: 1px solid #152c4a;
    padding: 30px 0;
}
.mvfoot-news-inner {
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    flex-wrap: wrap;
}
.mvfoot-news h3 {
    font-size: 17px;
    font-weight: 900;
    color: #f0f6ff;
    margin: 0 0 3px;
}
.mvfoot-news p { margin: 0; font-size: 12px; color: #6a8aaa; }
.mvfoot-news-form {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    flex-shrink: 0;
}
.mvfoot-news-form input {
    background: #06122a;
    border: 1px solid #1e3f65;
    border-radius: 9px;
    padding: 10px 16px;
    color: #dceaff;
    font: inherit;
    font-size: 12.5px;
    width: min(280px, 100%);
    outline: none;
    transition: border-color .2s;
}
.mvfoot-news-form input:focus { border-color: #5a7aff; }
.mvfoot-news-form input::placeholder { color: #3d5a78; }
.mvfoot-news-btn {
    background: linear-gradient(110deg, #7a38ff, #3f60ee);
    color: #fff;
    border: none;
    border-radius: 9px;
    padding: 10px 20px;
    font: inherit;
    font-size: 12.5px;
    font-weight: 800;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 7px;
    transition: opacity .2s, transform .2s;
}
.mvfoot-news-btn:hover { opacity: .88; transform: translateY(-2px); }

/* ── Main grid ──────────────────────────────────── */
.mvfoot-main {
    max-width: 1360px;
    margin: 0 auto;
    padding: 44px 24px 32px;
    display: grid;
    grid-template-columns: 1.6fr 1fr 1fr 1fr 1fr;
    gap: 28px;
}
/* ── Brand column ───────────────────────────────── */
.mvfoot-brand-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
    text-decoration: none;
}
.mvfoot-brand-mark {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: linear-gradient(135deg, #a64bff, #4b39ff 50%, #11b9ff);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    color: #fff;
    box-shadow: 0 0 18px rgba(115,60,255,.45);
    flex-shrink: 0;
}
.mvfoot-brand-name {
    display: flex;
    flex-direction: column;
    line-height: 1;
}
.mvfoot-brand-name strong {
    font-size: 17px;
    font-weight: 900;
    color: #fff;
    letter-spacing: .3px;
}
.mvfoot-brand-name small {
    font-size: 8.5px;
    font-weight: 700;
    letter-spacing: 2px;
    color: #6a8aaa;
    text-transform: uppercase;
    margin-top: 2px;
}
.mvfoot-tagline {
    font-size: 12px;
    line-height: 1.85;
    color: #6a8aaa;
    max-width: 240px;
    margin: 0 0 20px;
}
/* Socials */
.mvfoot-socials {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.mvfoot-social {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    border: 1px solid #1e3f65;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: #7eaacc;
    transition: border-color .2s, color .2s, background .2s;
}
.mvfoot-social:hover {
    border-color: #a67dff;
    color: #c5a4ff;
    background: rgba(130,70,255,.1);
}
/* ── Link columns ───────────────────────────────── */
.mvfoot-col h4 {
    font-size: 11.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: #d8e8f8;
    margin: 0 0 14px;
}
.mvfoot-links {
    display: flex;
    flex-direction: column;
    gap: 9px;
    list-style: none;
    margin: 0;
    padding: 0;
}
.mvfoot-links li a {
    font-size: 12.5px;
    color: #7a99b8;
}
.mvfoot-links li a:hover { color: #b689ff; }

/* ── Contact cards ──────────────────────────────── */
.mvfoot-contact-grid {
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 24px 32px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    border-top: 1px solid #152c4a;
    padding-top: 24px;
}
.mvfoot-contact-card {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #06122a;
    border: 1px solid #1a3a5e;
    border-radius: 13px;
    padding: 14px 16px;
    transition: border-color .2s;
}
.mvfoot-contact-card:hover { border-color: #4a6aaa; }
.mvfoot-contact-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg, #102848, #07182e);
    border: 1px solid #1e4268;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    color: #6aadff;
    flex-shrink: 0;
}
.mvfoot-contact-card h5 {
    margin: 0 0 2px;
    font-size: 11px;
    font-weight: 800;
    color: #c8daf0;
}
.mvfoot-contact-card p {
    margin: 0;
    font-size: 11px;
    color: #5a7a9a;
}

/* ── Copyright ──────────────────────────────────── */
.mvfoot-copy {
    max-width: 1360px;
    margin: 0 auto;
    padding: 16px 24px;
    border-top: 1px solid #152c4a;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    font-size: 11px;
    color: #3d5a78;
}
.mvfoot-copy-links {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.mvfoot-copy-links a { color: #3d5a78; }
.mvfoot-copy-links a:hover { color: #7a99b8; }

/* ── Responsive ─────────────────────────────────── */
@media(max-width: 1050px) {
    .mvfoot-main { grid-template-columns: 1fr 1fr 1fr; }
    .mvfoot-main > :first-child { grid-column: 1 / -1; }
    .mvfoot-contact-grid { grid-template-columns: 1fr 1fr; }
}
@media(max-width: 640px) {
    .mvfoot-main { grid-template-columns: 1fr 1fr; padding: 30px 18px 24px; }
    .mvfoot-main > :first-child { grid-column: 1 / -1; }
    .mvfoot-contact-grid { grid-template-columns: 1fr; }
    .mvfoot-news-inner { flex-direction: column; align-items: flex-start; }
    .mvfoot-copy { flex-direction: column; text-align: center; }
    .mvfoot-copy-links { justify-content: center; }
}
</style>

<footer class="mvfoot" aria-label="{{ $isAr ? 'التذييل' : 'Site Footer' }}">

  {{-- ── Newsletter strip ── --}}
  <!-- <div class="mvfoot-news">
    <div class="mvfoot-news-inner">
      <div>
        <h3>{{ $isAr ? 'اشترك في نشرتنا' : __('footer.newsletter_heading') }}</h3>
        <p>{{ $isAr ? 'كن أول من يعرف بالموديلات الجديدة والعروض الحصرية.' : __('footer.newsletter_text') }}</p>
      </div>
      <form class="mvfoot-news-form" method="POST" action="{{ route('contact.store') }}">
        @csrf
        <input type="hidden" name="enquiry_type" value="general">
        <input type="hidden" name="subject" value="{{ $isAr ? 'اشتراك في النشرة البريدية' : 'Newsletter Subscription' }}">
        <input type="hidden" name="name" value="Newsletter">
        <input type="hidden" name="message" value="{{ $isAr ? 'اشتراك في النشرة البريدية' : 'Newsletter Subscription' }}">
        <input type="email" name="email" required
               placeholder="{{ $isAr ? 'بريدك الإلكتروني' : __('footer.newsletter_placeholder') }}">
        <button type="submit" class="mvfoot-news-btn">
          <i class="fa-solid fa-paper-plane"></i>
          {{ $isAr ? 'اشترك الآن' : __('footer.newsletter_button') }}
        </button>
      </form>
    </div>
  </div> -->

  {{-- ── Main columns ── --}}
  <div class="mvfoot-main">

    {{-- Brand column --}}
    <div>
      <a href="{{ route('home') }}" class="mvfoot-brand-logo">
        <div class="mvfoot-brand-mark">
          <i class="fa-solid fa-motorcycle"></i>
        </div>
        <div class="mvfoot-brand-name">
          <strong>MOTOVEX</strong>
          <small>{{ $isAr ? 'عش حلمك' : 'RIDE YOUR DREAM' }}</small>
        </div>
      </a>
      <p class="mvfoot-tagline">{{ $isAr ? 'وجهتك الأولى لعالم الموتوسيكلات — موديلات، قطع غيار، خدمات، وكل ما تحتاجه في مكان واحد.' : __('footer.tagline') }}</p>
      <div class="mvfoot-socials">
        <a class="mvfoot-social" href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
        <a class="mvfoot-social" href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
        <a class="mvfoot-social" href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
        <a class="mvfoot-social" href="#" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
        <a class="mvfoot-social" href="#" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
      </div>
    </div>

    {{-- Explore --}}
    <div class="mvfoot-col">
      <h4>{{ $isAr ? 'استكشف' : __('footer.explore') }}</h4>
      <ul class="mvfoot-links">
        <li><a href="{{ route('motorcycles.index') }}">{{ $isAr ? 'جميع الموتوسيكلات' : __('footer.all_motorcycles') }}</a></li>
        <li><a href="{{ route('spare-parts.index') }}">{{ $isAr ? 'قطع الغيار' : 'Spare Parts' }}</a></li>
        <li><a href="{{ route('brands.index') }}">{{ $isAr ? 'العلامات التجارية' : __('footer.our_brands') }}</a></li>
        <li><a href="{{ route('promotions') }}">{{ $isAr ? 'العروض والتخفيضات' : 'Promotions' }}</a></li>
        <li><a href="{{ route('services') }}">{{ $isAr ? 'خدماتنا' : 'Services' }}</a></li>
      </ul>
    </div>

    {{-- Account --}}
    <div class="mvfoot-col">
      <h4>{{ $isAr ? 'حسابك' : __('footer.account') }}</h4>
      <ul class="mvfoot-links">
        <li><a href="{{ route('login') }}">{{ $isAr ? 'تسجيل الدخول' : 'Login' }}</a></li>
        <li><a href="{{ route('register') }}">{{ $isAr ? 'إنشاء حساب' : 'Register' }}</a></li>
        <li><a href="{{ route('account.dashboard') }}">{{ $isAr ? 'لوحة التحكم' : 'Dashboard' }}</a></li>
        <li><a href="{{ route('account.enquiries') }}">{{ $isAr ? 'استفساراتي' : __('footer.my_enquiries') }}</a></li>
        <li><a href="{{ route('cart.index') }}">{{ $isAr ? 'سلة التسوق' : 'My Cart' }}</a></li>
      </ul>
    </div>

    {{-- Top Brands --}}
    <div class="mvfoot-col">
      <h4>{{ $isAr ? 'الماركات' : __('footer.top_brands') }}</h4>
      <ul class="mvfoot-links">
        @forelse($footerBrands ?? [] as $fb)
          <li><a href="{{ route('brands.show', $fb->slug) }}">{{ $fb->name }}</a></li>
        @empty
          @foreach(['Honda','Yamaha','Kawasaki','BMW','KTM'] as $bn)
            <li><a href="{{ route('brands.index') }}">{{ $bn }}</a></li>
          @endforeach
        @endforelse
      </ul>
    </div>

    {{-- Support --}}
    <div class="mvfoot-col">
      <h4>{{ $isAr ? 'الدعم' : __('footer.customer_support') }}</h4>
      <ul class="mvfoot-links">
        <li><a href="{{ route('contact') }}">{{ $isAr ? 'تواصل معنا' : 'Contact Us' }}</a></li>
        <li><a href="{{ route('contact') }}">{{ $isAr ? 'احجز تجربة قيادة' : 'Book Test Ride' }}</a></li>
        <li><a href="#">{{ $isAr ? 'الأسئلة الشائعة' : 'FAQ' }}</a></li>
        <li><a href="#">{{ $isAr ? 'سياسة الإرجاع' : 'Returns Policy' }}</a></li>
        <li><a href="#">{{ $isAr ? 'الشروط والأحكام' : 'Terms & Conditions' }}</a></li>
      </ul>
    </div>

  </div>

  {{-- ── Contact cards ── --}}
  <div class="mvfoot-contact-grid">
    @foreach([
      ['fa-location-dot', $isAr ? 'عنواننا'       : __('footer.showroom_title'), __('footer.showroom_text')],
      ['fa-envelope',     $isAr ? 'راسلنا'         : __('footer.mail_title'),     __('footer.mail_text')],
      ['fa-phone',        $isAr ? 'اتصل بنا'       : __('footer.phone_title'),    __('footer.phone_text')],
      ['fa-motorcycle',   $isAr ? 'مجموعة MOTOVEX' : __('footer.group_title'),    __('footer.group_text')],
    ] as [$icon, $title, $text])
      <div class="mvfoot-contact-card">
        <div class="mvfoot-contact-icon"><i class="fa-solid {{ $icon }}"></i></div>
        <div>
          <h5>{{ $title }}</h5>
          <p>{{ $text }}</p>
        </div>
      </div>
    @endforeach
  </div>

  {{-- ── Copyright ── --}}
  <div class="mvfoot-copy">
    <span>
      &copy; {{ date('Y') }} MOTOVEX.
      {{ $isAr ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}
    </span>
    <span style="color:#3d5a78;font-size:11px">
      {{ $isAr ? 'تم التطوير بواسطة' : 'Developed by' }}
      <a href="https://www.linkedin.com/in/emam-medhat-18b708405" target="_blank" rel="noopener"
         style="color:#7a99b8;font-weight:700;margin:0 3px" title="LinkedIn">
        eng.emam medhat
      </a>
      &mdash; Cairo, Egypt &nbsp;
      <a href="tel:+201033643475" style="color:#5a7a9a;margin:0 3px" title="{{ $isAr ? 'اتصل' : 'Call' }}">+20 103 364 3475</a>
      <a href="mailto:emam.dev1@gmail.com" style="color:#5a7a9a;margin:0 3px" title="Email">emam.dev1@gmail.com</a>
      <a href="https://github.com/Emam-Medhat" target="_blank" rel="noopener" style="color:#5a7a9a;margin:0 3px" title="GitHub"><i class="fa-brands fa-github"></i></a>
      <a href="https://www.linkedin.com/in/emam-medhat-18b708405" target="_blank" rel="noopener" style="color:#5a7a9a;margin:0 3px" title="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
    </span>
    <div class="mvfoot-copy-links">
      <a href="#">{{ $isAr ? 'سياسة الخصوصية' : 'Privacy Policy' }}</a>
      <a href="#">{{ $isAr ? 'الشروط والأحكام' : 'Terms of Service' }}</a>
      <a href="#">{{ $isAr ? 'ملفات الارتباط' : 'Cookie Policy' }}</a>
    </div>
  </div>

</footer>
