{{--
    checkout-modal.blade.php
    Pure HTML fragment — no layout, no <html>/<head>/<body>.
    Rendered and injected into the cart page via fetch().
    All form POSTs go to the real cart.place-order route.
--}}
@php
    $itemCount    = $motoItems->count() + $spItems->count();
    $cartCurrency = ($motoItems->isNotEmpty() ? ($motoItems->first()['motorcycle']->currency ?: null) : null)
        ?? ($spItems->isNotEmpty() ? ($spItems->first()['spare_part']->currency ?: null) : null)
        ?? 'USD';
    $token = session('cart_submission_token', '');
@endphp

{{-- ── Header ── --}}
<div class="cko-header">
    <div>
        <h2 class="cko-title" id="cko-title">{{ __('cart.checkout_heading') }}</h2>
        <p class="cko-subtitle">{{ __('cart.checkout_sub') }}</p>
        <span class="cko-secure-badge">
            <i class="fa-solid fa-lock" style="font-size:.62rem"></i>
            {{ __('cart.secure_enquiry') }}
        </span>
    </div>
</div>

{{-- ── Progress ── --}}
<div class="cko-steps" aria-label="Checkout progress">
    <div class="cko-step done">
        <div class="cko-step-dot"><i class="fa-solid fa-check" style="font-size:10px"></i></div>
        <span class="cko-step-lbl">{{ __('cart.step_browse') }}</span>
    </div>
    <div class="cko-step done">
        <div class="cko-step-dot"><i class="fa-solid fa-check" style="font-size:10px"></i></div>
        <span class="cko-step-lbl">{{ __('cart.step_cart') }}</span>
    </div>
    <div class="cko-step active">
        <div class="cko-step-dot">3</div>
        <span class="cko-step-lbl">{{ __('cart.step_checkout') }}</span>
    </div>
    <div class="cko-step">
        <div class="cko-step-dot">4</div>
        <span class="cko-step-lbl">{{ __('cart.step_confirm') }}</span>
    </div>
</div>

<div class="cko-divider"></div>

{{-- ── Flash ── --}}
@if(session('info'))
    <div class="cko-flash info" style="margin:.75rem 1.75rem 0">
        <i class="fa-solid fa-circle-info"></i> {{ session('info') }}
    </div>
@endif

{{-- ── Body ── --}}
<div class="cko-body">

    {{-- LEFT: Form --}}
    <div>
        <div class="cko-card">
            <div class="cko-card-head">
                <div class="cko-card-ico"><i class="fa-solid fa-user"></i></div>
                <div>
                    <div class="cko-card-title">{{ __('cart.your_information') }}</div>
                    <div class="cko-card-sub">{{ __('cart.info_sub') }}</div>
                </div>
            </div>
            <div class="cko-card-body">
                <form method="POST" action="{{ route('cart.place-order') }}" id="cko-form" novalidate>
                    @csrf
                    <input type="hidden" name="idempotency_key" value="{{ $token }}">

                    {{-- Name --}}
                    <div class="cko-field">
                        <label class="cko-label" for="cko-name">
                            {{ __('cart.full_name') }} <span class="cko-req">*</span>
                        </label>
                        <div class="cko-iw">
                            <i class="fa-solid fa-user"></i>
                            <input id="cko-name" type="text" name="name"
                                   value="{{ old('name', auth()->user()->name ?? '') }}"
                                   required placeholder="{{ __('cart.full_name_placeholder', ['default' => 'Your full name']) }}"
                                   class="cko-input {{ $errors->has('name') ? 'is-err' : '' }}"
                                   autocomplete="name">
                        </div>
                        @error('name')
                            <p class="cko-err">
                                <i class="fa-solid fa-circle-exclamation" style="font-size:.68rem"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Email + Phone --}}
                    <div class="cko-grid2">
                        <div class="cko-field">
                            <label class="cko-label" for="cko-email">
                                {{ __('cart.email_address') }} <span class="cko-req">*</span>
                            </label>
                            <div class="cko-iw">
                                <i class="fa-solid fa-envelope"></i>
                                <input id="cko-email" type="email" name="email"
                                       value="{{ old('email', auth()->user()->email ?? '') }}"
                                       required placeholder="you@example.com"
                                       class="cko-input {{ $errors->has('email') ? 'is-err' : '' }}"
                                       autocomplete="email">
                            </div>
                            @error('email')
                                <p class="cko-err">
                                    <i class="fa-solid fa-circle-exclamation" style="font-size:.68rem"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div class="cko-field">
                            <label class="cko-label" for="cko-phone">
                                {{ __('cart.phone_number') }} <span class="cko-req">*</span>
                            </label>
                            <div class="cko-iw">
                                <i class="fa-solid fa-phone"></i>
                                <input id="cko-phone" type="tel" name="phone"
                                       value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                       required placeholder="+20 1xx xxx xxxx"
                                       class="cko-input {{ $errors->has('phone') ? 'is-err' : '' }}"
                                       autocomplete="tel">
                            </div>
                            @error('phone')
                                <p class="cko-err">
                                    <i class="fa-solid fa-circle-exclamation" style="font-size:.68rem"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="cko-field">
                        <label class="cko-label" for="cko-msg">
                            {{ __('cart.additional_notes') }}
                            <span style="color:#3d5a78;font-weight:400"> {{ __('cart.optional_label') }}</span>
                        </label>
                        <div class="cko-iw" style="position:relative">
                            <i class="fa-solid fa-comment-dots"
                               style="position:absolute;left:12px;top:13px;color:#6b8aaa;font-size:12px;pointer-events:none;z-index:1;transform:none;"></i>
                            <textarea id="cko-msg" name="message" rows="3"
                                      placeholder="{{ __('cart.notes_placeholder_co') }}"
                                      class="cko-input"
                                      style="padding-left:2.4rem;padding-top:.75rem">{{ old('message') }}</textarea>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" id="cko-submit" class="cko-submit">
                        <i class="fa-solid fa-motorcycle"></i>
                        <span id="cko-submit-txt">{{ __('cart.place_order_enquiry') }}</span>
                        <i class="fa-solid fa-arrow-right arrow" style="font-size:.75rem"></i>
                    </button>

                    <div class="cko-trust">
                        <span><i class="fa-solid fa-lock"></i> {{ __('cart.secure_submission') }}</span>
                        <span><i class="fa-solid fa-shield-halved"></i> {{ __('cart.no_card_required') }}</span>
                        <span><i class="fa-solid fa-headset"></i> {{ __('cart.team_24h') }}</span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- RIGHT: Summary --}}
    <div>
        <div class="cko-card" style="margin-bottom:.85rem">
            <div class="cko-card-head">
                <div class="cko-card-ico"><i class="fa-solid fa-receipt"></i></div>
                <div>
                    <div class="cko-card-title">{{ __('cart.order_summary') }}</div>
                    <div class="cko-card-sub">{{ str_replace(':count', $itemCount, __('cart.items_selected')) }}</div>
                </div>
            </div>
            <div class="cko-card-body">

                @foreach($motoItems as $item)
                    @php $moto = $item['motorcycle']; @endphp
                    <div class="cko-item">
                        <div class="cko-item-img">
                            @if($moto->image_url)
                                <img src="{{ $moto->image_url }}" alt="{{ $moto->model_name }}" loading="lazy">
                            @else
                                <i class="fa-solid fa-motorcycle" style="color:#7c3aed;font-size:16px;"></i>
                            @endif
                        </div>
                        <div style="flex:1;min-width:0">
                            <div class="cko-item-name">{{ $moto->model_name }}</div>
                            <div class="cko-item-brand">{{ $moto->brand?->name }}</div>
                        </div>
                        <div class="cko-item-price">
                            @if($moto->price)
                                {{ $moto->currency ?: 'USD' }} {{ number_format($moto->price) }}
                            @else
                                <span style="font-size:.7rem;color:#3d5a78">{{ __('cart.on_request_text') }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach

                @foreach($spItems as $item)
                    @php $sp = $item['spare_part']; $qty = max(1,(int)$item['quantity']); @endphp
                    <div class="cko-item">
                        <div class="cko-item-img">
                            @if($sp->image_url)
                                <img src="{{ $sp->image_url }}" alt="{{ $sp->name }}" loading="lazy">
                            @else
                                <i class="fa-solid fa-gears" style="color:#7c3aed;font-size:16px;"></i>
                            @endif
                        </div>
                        <div style="flex:1;min-width:0">
                            <div class="cko-item-name">{{ $sp->name }}</div>
                            <div class="cko-item-brand">{{ $sp->brand?->name ?? 'MOTOVEX' }} · ×{{ $qty }}</div>
                        </div>
                        <div class="cko-item-price">
                            @if($sp->price)
                                {{ $sp->currency ?: 'USD' }} {{ number_format((float)$sp->price * $qty) }}
                            @else
                                <span style="font-size:.7rem;color:#3d5a78">{{ __('cart.on_request_text') }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach

                @if($total > 0)
                <div class="cko-total-row">
                    <span class="cko-total-lbl">{{ __('cart.total_label') }}</span>
                    <span class="cko-total-val">
                        <span class="cko-total-cur">{{ $cartCurrency }}</span>{{ number_format($total) }}
                    </span>
                </div>
                @endif

                <div class="cko-note">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>{{ __('cart.checkout_no_payment') }}</span>
                </div>
            </div>
        </div>

        {{-- Promises --}}
        <div class="cko-card">
            <div class="cko-card-body" style="padding:.9rem 1rem">
                <p style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#a78bfa;margin-bottom:.65rem">
                    {{ __('cart.our_promise') }}
                </p>
                <div class="cko-promises">
                    <div class="cko-promise">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>{{ __('cart.promise_certified') }}</span>
                    </div>
                    <div class="cko-promise">
                        <i class="fa-solid fa-truck-fast"></i>
                        <span>{{ __('cart.promise_delivery') }}</span>
                    </div>
                    <div class="cko-promise">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>{{ __('cart.promise_warranty') }}</span>
                    </div>
                    <div class="cko-promise">
                        <i class="fa-solid fa-headset"></i>
                        <span>{{ __('cart.promise_support') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>{{-- /.right --}}

</div>{{-- /.cko-body --}}
