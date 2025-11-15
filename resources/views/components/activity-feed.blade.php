{{-- Activity Feed Component --}}
@php
  $activities = rentwise_get_recent_activities(10);
@endphp

@if (!empty($activities))
<section class="mb-8 px-6 py-6 bg-white rounded-xl shadow-lg">
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-semibold text-slate-800">Recent Activity</h2>
    <span class="text-sm text-slate-500">Last 30 days</span>
  </div>

  <div class="space-y-4">
    @foreach ($activities as $activity)
      @php
        $color_classes = match($activity['color']) {
          'green' => 'bg-green-100 text-green-700 border-green-200',
          'blue' => 'bg-blue-100 text-blue-700 border-blue-200',
          'orange' => 'bg-orange-100 text-orange-700 border-orange-200',
          'red' => 'bg-red-100 text-red-700 border-red-200',
          default => 'bg-slate-100 text-slate-700 border-slate-200'
        };
      @endphp

      <div class="flex items-start gap-4 p-4 rounded-lg border {{ $color_classes }} hover:shadow-md transition-all">
        {{-- Icon --}}
        <div class="flex-shrink-0 text-2xl">
          {{ $activity['icon'] }}
        </div>

        {{-- Content --}}
        <div class="flex-grow min-w-0">
          <h3 class="font-semibold text-sm">{{ $activity['title'] }}</h3>
          <p class="text-sm opacity-90 mt-0.5">{{ $activity['description'] }}</p>
        </div>

        {{-- Time --}}
        <div class="flex-shrink-0 text-xs opacity-75 whitespace-nowrap">
          {{ $activity['time_ago'] }}
        </div>
      </div>
    @endforeach
  </div>

  {{-- Empty State for when there are fewer than 10 activities --}}
  @if (count($activities) < 3)
    <div class="mt-6 text-center p-6 bg-slate-50 rounded-lg border border-slate-200">
      <p class="text-slate-600 text-sm">
        💡 <strong>Tip:</strong> Activity will appear here as you add tenants, record payments, and manage leases.
      </p>
    </div>
  @endif
</section>
@else
  {{-- Empty State for no activities --}}
  <section class="mb-8 px-6 py-8 bg-white rounded-xl shadow-lg">
    <div class="text-center">
      <div class="text-6xl mb-4">📊</div>
      <h2 class="text-xl font-semibold text-slate-800 mb-2">No Recent Activity</h2>
      <p class="text-slate-600">
        Start by adding tenants and recording payments to see your activity timeline here.
      </p>
    </div>
  </section>
@endif

