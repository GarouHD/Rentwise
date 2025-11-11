{{-- resources/views/components/tenant-card.blade.php --}}

@props([
  'name' => '(No Name)',
  'unit' => '',
  'rentAmount' => '',
  'status' => '',
  'avatar' => null,
  'notifications' => 0,
  'id' => '',
])

@php
  $initials = collect(explode(' ', $name))
      ->map(fn($n) => strtoupper(mb_substr($n, 0, 1)))
      ->take(2)
      ->implode('');

  // uncomment this line if you want the profile picture
  // $avatarUrl = $avatarUrl = $avatar['url'] ?? null;
  $avatarUrl = null;

  // Status color logic example (optional)
  $statusColor = match($status) {
      'active' => 'from-green-400 to-green-600',
      'inactive' => 'from-red-400 to-red-600',
      default => 'from-blue-400 to-blue-600'
  };
@endphp

<div
  id="tenant-{{ $id }}"
  class="tenant-icon bg-gradient-to-br {{ $statusColor }}
         rounded-xl p-4 text-white cursor-pointer relative transition hover:scale-105"
  onclick="showTenantDetails('{{ $id }}')"
>
  @if($notifications > 0)
    <div class="notification-badge absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
      {{ $notifications }}
    </div>
  @endif

  <div class="text-center">
    <div class="bg-white/20 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-2 overflow-hidden">
      @if($avatarUrl)
        <img src="{{ $avatarUrl }}" alt="{{ $name }}" class="w-12 h-12 object-cover rounded-full" />
      @else
        <span class="text-xl font-bold">{{ $initials }}</span>
      @endif
    </div>

    <p class="font-semibold text-sm">{{ $name }}</p>
    <p class="text-xs opacity-80">{{ $unit }}</p>
    <p class="text-xs opacity-80">${{ number_format((float)$rentAmount, 2) }}/mo</p>
  </div>
</div>