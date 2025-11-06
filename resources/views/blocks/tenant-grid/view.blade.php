@php
  // Get all tenants (TODO: make it user-specific later)
  $tenants = lzmk_get_all_tenants();
@endphp

<section class="tenant-grid-block my-8 bg-gray-100">
  <h2 class="text-2xl font-semibold mb-6">Tenant Directory</h2>

  @if (!empty($tenants))
    <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
      @foreach ($tenants as $tenant)
        @php
          // Get ACF fields for this tenant
          $name   = get_field('name', $tenant->ID);
          $unit   = get_field('unit', $tenant->ID);
          $rent   = get_field('rent_amount', $tenant->ID);
          $status = get_field('status', $tenant->ID);
          $avatar = get_field('avatar', $tenant->ID);
        @endphp

        {{-- Render the tenant card component using local variables --}}
        <x-tenant-card
          :id="$tenant->ID"
          :name="$name"
          :unit="$unit"
          :rentAmount="$rent"
          :status="$status"
          :avatar="$avatar"
          notifications="3"
        />
      @endforeach
    </div>
  @else
    <p class="text-slate-500">No tenants found.</p>
  @endif
  
</section>
