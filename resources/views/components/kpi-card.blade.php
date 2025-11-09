@props([
  'title' => 'KPI',
  'color' => 'bg-blue-100',      // background circle color
  'icon' => 'users',             // name of SVG in resources/views/svg/
  'textColor' => 'text-blue-600',// icon color
  'metric' => null,              // e.g. active_tenants, monthly_revenue
])

@php
  // Get value from helper
  $value = $metric ? rentwise_kpi_value($metric) : null;

  // Format display
  if ($metric === 'monthly_revenue') {
      $formatted = '$' . number_format((float) $value);
  } elseif (is_numeric($value)) {
      $formatted = number_format((int) $value);
  } else {
      $formatted = $value ?? '—';
  }
@endphp

<div class="bg-white rounded-xl p-6 shadow-lg">
  <div class="flex items-center">
    <div class="{{ $color }} p-3 rounded-full">
      <x-icon name="{{ $icon }}" class="w-6 h-6 {{ $textColor }}" />
    </div>
    <div class="ml-4">
      <p class="text-gray-600">{{ $title }}</p>
      <p class="text-2xl font-bold text-gray-800">{{ $formatted }}</p>
    </div>
  </div>
</div>
