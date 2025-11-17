{{-- Activity Feed Component --}}
{{-- This component displays a timeline of recent activities on the dashboard --}}
{{-- It shows: payments received, new tenants added, and upcoming lease expirations --}}

@php
  // Call the backend function to get recent activities
  // Parameter: 10 = show maximum 10 activities
  $activities = rentwise_get_recent_activities(10);
@endphp

{{-- Only show activity feed if there are activities to display --}}
@if (!empty($activities))
{{-- Main container: white card with rounded corners and shadow --}}
<section class="mb-4 sm:mb-6 md:mb-8 px-4 sm:px-6 py-4 sm:py-6 bg-white rounded-xl shadow-lg">
  {{-- Header section with title and date range indicator --}}
  <div class="flex items-center justify-between mb-4 sm:mb-6">
    <h2 class="text-xl sm:text-2xl font-semibold text-slate-800">Recent Activity</h2>
    <span class="text-xs sm:text-sm text-slate-500">Last 30 days</span>
  </div>

  {{-- Activities list container with vertical spacing between items --}}
  <div class="space-y-4">
    {{-- Loop through each activity and display it as a card --}}
    @foreach ($activities as $activity)
      @php
        // {{-- Match activity color to Tailwind CSS classes --}}
        // {{-- This determines the background, text, and border colors for each activity card --}}
        $color_classes = match($activity['color']) {
          'green' => 'bg-green-100 text-green-700 border-green-200',    // Green for payments (money in)
          'blue' => 'bg-blue-100 text-blue-700 border-blue-200',        // Blue for new tenants
          'orange' => 'bg-orange-100 text-orange-700 border-orange-200', // Orange for leases expiring 31-60 days
          'red' => 'bg-red-100 text-red-700 border-red-200',             // Red for leases expiring ≤30 days (urgent)
          default => 'bg-slate-100 text-slate-700 border-slate-200'      // Default gray if color not recognized
        };
      @endphp

      {{-- Individual activity card --}}
      {{-- flex = horizontal layout, items-start = align items to top, gap-4 = space between icon/content/time --}}
      <div class="flex items-start gap-4 p-4 rounded-lg border {{ $color_classes }} hover:shadow-md transition-all">
        {{-- Left: Icon (emoji) - flex-shrink-0 prevents icon from shrinking --}}
        <div class="flex-shrink-0 text-2xl">
          {{ $activity['icon'] }}  {{-- Displays: 💰, 👤, ⚠️, or 📅 --}}
        </div>

        {{-- Middle: Content section - flex-grow makes it take available space --}}
        <div class="flex-grow min-w-0">
          {{-- Activity title (e.g., "Payment Received", "New Tenant Added") --}}
          <h3 class="font-semibold text-sm">{{ $activity['title'] }}</h3>
          {{-- Activity description (e.g., "$222.00 from James", "James at Sunset Apartments") --}}
          <p class="text-sm opacity-90 mt-0.5">{{ $activity['description'] }}</p>
        </div>

        {{-- Right: Time indicator - flex-shrink-0 prevents time from shrinking, whitespace-nowrap prevents wrapping --}}
        <div class="flex-shrink-0 text-xs opacity-75 whitespace-nowrap">
          {{ $activity['time_ago'] }}  {{-- Displays: "3 days ago", "Expires Dec 15, 2024", etc. --}}
        </div>
      </div>
    @endforeach
  </div>

  {{-- Empty State: Show helpful tip if there are very few activities (< 3) --}}
  {{-- This encourages users to add more data --}}
  @if (count($activities) < 3)
    <div class="mt-6 text-center p-6 bg-slate-50 rounded-lg border border-slate-200">
      <p class="text-slate-600 text-sm">
        💡 <strong>Tip:</strong> Activity will appear here as you add tenants, record payments, and manage leases.
      </p>
    </div>
  @endif
</section>
@else
  {{-- Empty State: Show message if there are NO activities at all --}}
  {{-- This appears when user first starts using the system --}}
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
