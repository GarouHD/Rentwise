@php
    // get all tenants (TODO make it user specific)
    $tenants = lzmk_get_all_tenants()
@endphp

<section class="tenant-grid-block my-8 bg-gray-100">
    <h2 class="text-2xl font-semibold mb-6">Tenant Directory</h2>

    @if (!empty($tenants))
    <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
      @foreach ($tenants as $tenant)
        @php
          // Get ACF fields for this tenant
          $name       = get_field('name', $tenant->ID);
          $unit       = get_field('unit', $tenant->ID);
          $rent       = get_field('rent_amount', $tenant->ID);
          $status     = get_field('status', $tenant->ID);
          $avatar     = get_field('avatar', $tenant->ID);
        @endphp

        {{-- We'll replace this with a real tenant-card later --}}
        <article class="p-4 border rounded-lg shadow-sm bg-white dark:bg-slate-800">
          <div class="flex items-center gap-3">
            @if ($avatar && !empty($avatar['url']))
              <img src="{{ esc_url($avatar['url']) }}" alt="{{ esc_attr($avatar['alt'] ?? $name) }}"
                   class="h-12 w-12 rounded-full object-cover" />
            @else
              <div class="h-12 w-12 rounded-full bg-slate-200 flex items-center justify-center font-semibold">
                {{ substr($name, 0, 1) }}
              </div>
            @endif
            <div>
              <h3 class="font-semibold">{{ $name ?: $tenant->post_title }}</h3>
              <p class="text-sm text-slate-500">{{ $unit ?: '—' }}</p>
            </div>
          </div>

          <div class="mt-2 text-sm text-slate-600 dark:text-slate-300">
            <p>Status: {{ $status ?: 'Unknown' }}</p>
            <p>Rent: {{ $rent ? '$' . number_format($rent, 0) : 'N/A' }}</p>
          </div>
        </article>
      @endforeach
    </div>
  @else
    <p class="text-slate-500">No tenants found.</p>
  @endif

</section>