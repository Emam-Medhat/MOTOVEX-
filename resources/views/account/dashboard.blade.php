@extends('layouts.account')

@section('title', 'Overview')
@section('heading', 'Overview')

@section('content')

  {{-- ─── Welcome banner ─── --}}
  <div class="ac-welcome">
    <div>
      <div class="ac-welcome-title">Welcome back, {{ auth()->user()->name }} 👋</div>
      <div class="ac-welcome-sub">
        Member since {{ auth()->user()->created_at?->format('F Y') ?? 'N/A' }}
      </div>
    </div>
    <i class="fa-solid fa-motorcycle ac-welcome-ico" aria-hidden="true"></i>
  </div>

  {{-- ─── Stat cards ─── --}}
  <div class="ac-stat-grid">
    <div class="ac-stat">
      <div class="ac-stat-ico">
        <i class="fa-solid fa-envelope" aria-hidden="true"></i>
      </div>
      <div>
        <div class="ac-stat-num">{{ $stats['total'] }}</div>
        <div class="ac-stat-label">Total Enquiries</div>
      </div>
    </div>
    <div class="ac-stat">
      <div class="ac-stat-ico">
        <i class="fa-solid fa-clock" aria-hidden="true"></i>
      </div>
      <div>
        <div class="ac-stat-num">{{ $stats['open'] }}</div>
        <div class="ac-stat-label">Awaiting Contact</div>
      </div>
    </div>
    <div class="ac-stat">
      <div class="ac-stat-ico">
        <i class="fa-solid fa-flag-checkered" aria-hidden="true"></i>
      </div>
      <div>
        <div class="ac-stat-num">{{ $stats['test_rides'] }}</div>
        <div class="ac-stat-label">Test Rides</div>
      </div>
    </div>
  </div>

  {{-- ─── Recent activity ─── --}}
  <div class="ac-card" style="margin-bottom:1.5rem;">
    <div class="ac-card-header">
      <div class="ac-card-title">
        <i class="fa-solid fa-clock-rotate-left"></i>
        Recent Activity
      </div>
      @if($enquiries->isNotEmpty())
        <a href="{{ route('account.enquiries') }}" class="ac-btn-ghost" style="padding:.5rem .95rem;font-size:.8rem;">
          View all <i class="fa-solid fa-arrow-right" style="font-size:.68rem;"></i>
        </a>
      @endif
    </div>
    <div class="ac-card-body" style="padding:0;">
      @if($enquiries->isEmpty())
        <div class="ac-empty" style="margin:1.5rem;border-radius:12px;">
          <div class="ac-empty-ico"><i class="fa-solid fa-envelope-open"></i></div>
          <h3>No enquiries yet</h3>
          <p>Browse our motorcycles and get in touch when something catches your eye.</p>
          <a href="{{ route('motorcycles.index') }}" class="ac-btn-ghost" style="margin-top:1rem;">
            Browse Motorcycles
            <i class="fa-solid fa-arrow-right" style="font-size:.7rem;"></i>
          </a>
        </div>
      @else
        <div style="padding:0 1.5rem;">
          @foreach($enquiries as $enquiry)
            @php
              $initial = strtoupper(substr($enquiry->name ?? 'E', 0, 1));
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
                'test_drive'  => 'Test Ride',
                'general'     => 'General',
                'purchase'    => 'Purchase',
                'service'     => 'Service',
                default       => ucfirst(str_replace('_', ' ', $enquiry->enquiry_type ?? 'General')),
              };
            @endphp
            <div class="ac-activity-row">
              <div class="ac-activity-avatar">{{ $initial }}</div>
              <div style="flex:1;min-width:0;">
                <div class="ac-activity-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                  {{ $enquiry->subject ?? 'Enquiry' }}
                </div>
                <div class="ac-activity-sub">{{ $enquiry->name }}</div>
              </div>
              <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.4rem;flex-shrink:0;">
                <span class="ac-type-tag">{{ $typeLabel }}</span>
                <span class="ac-badge {{ $badgeClass }}">{{ $statusLabel }}</span>
              </div>
              <div class="ac-activity-meta" style="min-width:5rem;">
                <div class="ac-activity-date">{{ $enquiry->created_at?->format('d M Y') }}</div>
                @if($enquiry->id)
                  <div style="font-size:.68rem;color:#94a3b8;margin-top:.15rem;">#{{ str_pad($enquiry->id, 5, '0', STR_PAD_LEFT) }}</div>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

  {{-- ─── Quick links ─── --}}
  <div class="ac-card">
    <div class="ac-card-header">
      <div class="ac-card-title">
        <i class="fa-solid fa-bolt"></i>
        Quick Links
      </div>
    </div>
    <div class="ac-card-body">
      <div class="ac-quick-grid">
        <a href="{{ route('motorcycles.index') }}" class="ac-quick-link">
          <div class="ac-quick-link-ico"><i class="fa-solid fa-motorcycle"></i></div>
          <div>
            <div class="ac-quick-link-title">Browse Motorcycles</div>
            <div class="ac-quick-link-sub">Explore our full catalogue</div>
          </div>
        </a>
        <a href="{{ route('contact') }}" class="ac-quick-link">
          <div class="ac-quick-link-ico"><i class="fa-solid fa-flag-checkered"></i></div>
          <div>
            <div class="ac-quick-link-title">Book a Test Ride</div>
            <div class="ac-quick-link-sub">Schedule a ride today</div>
          </div>
        </a>
        <a href="{{ route('account.profile') }}" class="ac-quick-link">
          <div class="ac-quick-link-ico"><i class="fa-solid fa-user-pen"></i></div>
          <div>
            <div class="ac-quick-link-title">Update Profile</div>
            <div class="ac-quick-link-sub">Edit your personal info</div>
          </div>
        </a>
        <a href="{{ route('brands.index') }}" class="ac-quick-link">
          <div class="ac-quick-link-ico"><i class="fa-solid fa-gem"></i></div>
          <div>
            <div class="ac-quick-link-title">Explore Brands</div>
            <div class="ac-quick-link-sub">Discover premium brands</div>
          </div>
        </a>
      </div>
    </div>
  </div>

@endsection
