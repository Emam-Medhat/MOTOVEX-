@extends('layouts.account')

@section('title', 'My Enquiries')
@section('heading', 'My Enquiries')

@section('content')

  @if($enquiries->isEmpty())

    {{-- ─── Empty state ─── --}}
    <div class="ac-card">
      <div class="ac-card-body">
        <div class="ac-empty">
          <div class="ac-empty-ico"><i class="fa-solid fa-envelope-open"></i></div>
          <h3>No enquiries yet</h3>
          <p>You haven't submitted any enquiries. Browse our catalogue and get in touch — we're ready to help.</p>
          <div style="display:flex;align-items:center;justify-content:center;gap:.85rem;flex-wrap:wrap;margin-top:1.25rem;">
            <a href="{{ route('motorcycles.index') }}" class="ac-btn-primary" style="font-size:.85rem;">
              <i class="fa-solid fa-motorcycle"></i>
              Browse Motorcycles
            </a>
            <a href="{{ route('contact') }}" class="ac-btn-ghost" style="font-size:.85rem;">
              Contact Us
              <i class="fa-solid fa-arrow-right" style="font-size:.7rem;"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

  @else

    {{-- ─── Enquiries table card ─── --}}
    <div class="ac-card" style="margin-bottom:1.5rem;">
      <div class="ac-card-header">
        <div class="ac-card-title">
          <i class="fa-solid fa-list-ul"></i>
          All Enquiries
          <small style="font-size:.72rem;color:#94a3b8;font-weight:400;margin-left:.25rem;">استفساراتي</small>
        </div>
        <span style="font-size:.8rem;color:#64748b;font-weight:600;">
          {{ $enquiries->total() }} total
        </span>
      </div>

      {{-- Desktop table --}}
      <div class="ac-table-wrap" style="display:block;">
        <table class="ac-table">
          <thead>
            <tr>
              <th>Ref #</th>
              <th>Subject</th>
              <th>Message</th>
              <th>Type</th>
              <th>Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($enquiries as $enquiry)
              @php
                $statusColors = [
                  'pending'   => 'pending',
                  'contacted' => 'contacted',
                  'completed' => 'completed',
                  'cancelled' => 'cancelled',
                ];
                $badgeClass = $statusColors[$enquiry->status] ?? 'pending';
                $statusLabels = [
                  'pending'   => 'Pending',
                  'contacted' => 'Contacted',
                  'completed' => 'Completed',
                  'cancelled' => 'Cancelled',
                ];
                $statusLabel = $statusLabels[$enquiry->status] ?? ucfirst($enquiry->status);
                $typeLabel = match($enquiry->enquiry_type) {
                  'test_drive' => 'Test Ride',
                  'general'    => 'General',
                  'purchase'   => 'Purchase',
                  'service'    => 'Service',
                  default      => ucfirst(str_replace('_', ' ', $enquiry->enquiry_type ?? 'General')),
                };
              @endphp
              <tr>
                <td>
                  <span style="font-size:.78rem;font-weight:700;color:#64748b;white-space:nowrap;">
                    #{{ str_pad($enquiry->id, 5, '0', STR_PAD_LEFT) }}
                  </span>
                </td>
                <td>
                  <span style="font-weight:700;color:#172033;font-size:.88rem;">
                    {{ $enquiry->subject ?? 'Enquiry' }}
                  </span>
                </td>
                <td style="max-width:220px;">
                  <span style="color:#64748b;font-size:.83rem;">
                    {{ \Illuminate\Support\Str::limit($enquiry->message ?? '', 80) }}
                  </span>
                </td>
                <td>
                  <span class="ac-type-tag">{{ $typeLabel }}</span>
                </td>
                <td>
                  <span style="font-size:.82rem;color:#64748b;white-space:nowrap;">
                    {{ $enquiry->created_at?->format('d M Y') }}
                  </span>
                </td>
                <td>
                  <span class="ac-badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    {{-- Mobile card view (shown at small screens as supplementary) --}}
    @foreach($enquiries as $enquiry)
      @php
        $statusColors = [
          'pending'   => 'pending',
          'contacted' => 'contacted',
          'completed' => 'completed',
          'cancelled' => 'cancelled',
        ];
        $badgeClass = $statusColors[$enquiry->status] ?? 'pending';
        $statusLabels = [
          'pending'   => 'Pending',
          'contacted' => 'Contacted',
          'completed' => 'Completed',
          'cancelled' => 'Cancelled',
        ];
        $statusLabel = $statusLabels[$enquiry->status] ?? ucfirst($enquiry->status);
        $typeLabel = match($enquiry->enquiry_type) {
          'test_drive' => 'Test Ride',
          'general'    => 'General',
          'purchase'   => 'Purchase',
          'service'    => 'Service',
          default      => ucfirst(str_replace('_', ' ', $enquiry->enquiry_type ?? 'General')),
        };
      @endphp
      <div class="ac-card" style="margin-bottom:.75rem;display:none;" data-mobile-card>
        <div class="ac-card-body" style="padding:1.1rem 1.25rem;">
          <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:.75rem;margin-bottom:.65rem;">
            <div>
              <div style="font-weight:800;font-size:.9rem;color:#172033;">
                {{ $enquiry->subject ?? 'Enquiry' }}
              </div>
              <div style="font-size:.74rem;color:#94a3b8;margin-top:.15rem;">
                #{{ str_pad($enquiry->id, 5, '0', STR_PAD_LEFT) }} · {{ $enquiry->created_at?->format('d M Y') }}
              </div>
            </div>
            <span class="ac-badge {{ $badgeClass }}">{{ $statusLabel }}</span>
          </div>
          <p style="font-size:.82rem;color:#64748b;margin:0 0 .75rem;">
            {{ \Illuminate\Support\Str::limit($enquiry->message ?? '', 100) }}
          </p>
          <span class="ac-type-tag">{{ $typeLabel }}</span>
        </div>
      </div>
    @endforeach

    {{-- Pagination --}}
    @if($enquiries->hasPages())
      <div class="ac-pager">
        {{ $enquiries->links() }}
      </div>
    @endif

  @endif

@endsection

@push('scripts')
<script>
  // Show mobile cards on small screens instead of table
  (function () {
    var mq = window.matchMedia('(max-width: 639px)');
    function toggleView(e) {
      var tableWrap = document.querySelector('.ac-table-wrap');
      var mobileCards = document.querySelectorAll('[data-mobile-card]');
      if (e.matches) {
        if (tableWrap) tableWrap.style.display = 'none';
        mobileCards.forEach(function(c){ c.style.display = 'block'; });
      } else {
        if (tableWrap) tableWrap.style.display = 'block';
        mobileCards.forEach(function(c){ c.style.display = 'none'; });
      }
    }
    mq.addEventListener('change', toggleView);
    toggleView(mq);
  })();
</script>
@endpush
