@php
  // Fetch values from ACF fields
  $initials = get_field('initials') ?: 'JD';
  $name = get_field('name') ?: 'John Doe';
  $apartment = get_field('apartment') ?: 'Apt 101';
  $rent = get_field('rent') ? '$' . number_format(get_field('rent')) . '/mo' : '$1,200/mo';
  $notifications = get_field('notifications') ?: 0;
@endphp

<div id="tenantGrid" class="grid grid-cols-2 md:grid-cols-4 gap-4">
  <!-- Tenant Card -->
  <div class="tenant-icon bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl p-4 text-white cursor-pointer relative group hover:scale-[1.03] hover:shadow-blue-500/30 transition-all duration-300">
      
      {{-- Notification Badge --}}
      @if($notifications > 0)
        <div class="notification-badge absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
          {{ $notifications }}
        </div>
      @endif

      <div class="text-center">
          <div class="bg-white bg-opacity-20 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-2">
              <span class="text-xl font-bold">{{ $initials }}</span>
          </div>
          <p class="font-semibold text-sm">{{ $name }}</p>
          <p class="text-xs opacity-80">{{ $apartment }}</p>
          <p class="text-xs opacity-80">{{ $rent }}</p>
      </div>
  </div>
</div>
