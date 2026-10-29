@if ($paginator->hasPages())
<nav aria-label="Pagination" style="display:flex;justify-content:center;align-items:center;gap:6px;flex-wrap:wrap;margin-top:28px">

    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span style="display:inline-flex;align-items:center;justify-content:center;min-width:38px;height:38px;padding:0 10px;background:#111418;border:1px solid #1f2428;border-radius:9px;color:#2a2440;font-size:13px">
            <i class="fa-solid fa-chevron-right"></i>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
           style="display:inline-flex;align-items:center;justify-content:center;min-width:38px;height:38px;padding:0 10px;background:#111418;border:1px solid #1f2428;border-radius:9px;color:#9b96ad;font-size:13px;transition:border-color .15s,background .15s"
           onmouseover="this.style.borderColor='rgba(155,92,255,.5)';this.style.background='rgba(155,92,255,.08)';this.style.color='#fff'"
           onmouseout="this.style.borderColor='#1f2428';this.style.background='#111418';this.style.color='#9b96ad'">
            <i class="fa-solid fa-chevron-right"></i>
        </a>
    @endif

    {{-- Pages --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span style="display:inline-flex;align-items:center;justify-content:center;min-width:38px;height:38px;background:#111418;border:1px solid #1f2428;border-radius:9px;color:#4f4966;font-size:13px">…</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span aria-current="page"
                          style="display:inline-flex;align-items:center;justify-content:center;min-width:38px;height:38px;padding:0 10px;background:linear-gradient(105deg,#9b5cff,#dc5dbd);border:1px solid rgba(155,92,255,.5);border-radius:9px;color:#fff;font-size:13px;font-weight:700">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       style="display:inline-flex;align-items:center;justify-content:center;min-width:38px;height:38px;padding:0 10px;background:#111418;border:1px solid #1f2428;border-radius:9px;color:#9b96ad;font-size:13px;transition:border-color .15s,background .15s"
                       onmouseover="this.style.borderColor='rgba(155,92,255,.5)';this.style.background='rgba(155,92,255,.08)';this.style.color='#fff'"
                       onmouseout="this.style.borderColor='#1f2428';this.style.background='#111418';this.style.color='#9b96ad'">
                        {{ $page }}
                    </a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
           style="display:inline-flex;align-items:center;justify-content:center;min-width:38px;height:38px;padding:0 10px;background:#111418;border:1px solid #1f2428;border-radius:9px;color:#9b96ad;font-size:13px;transition:border-color .15s,background .15s"
           onmouseover="this.style.borderColor='rgba(155,92,255,.5)';this.style.background='rgba(155,92,255,.08)';this.style.color='#fff'"
           onmouseout="this.style.borderColor='#1f2428';this.style.background='#111418';this.style.color='#9b96ad'">
            <i class="fa-solid fa-chevron-left"></i>
        </a>
    @else
        <span style="display:inline-flex;align-items:center;justify-content:center;min-width:38px;height:38px;padding:0 10px;background:#111418;border:1px solid #1f2428;border-radius:9px;color:#2a2440;font-size:13px">
            <i class="fa-solid fa-chevron-left"></i>
        </span>
    @endif

</nav>
@endif
